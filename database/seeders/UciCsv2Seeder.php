<?php

namespace Database\Seeders;

use App\Models\{
    Dataset, DatasetDescription, File, Keyword, Person, 
    Variable, VariableCategory, Paper, Doi, License, Image
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;

class UciCsv2Seeder extends Seeder
{
    protected string $csvPath = 'data/archive-ics-uci-edu-2026-05-09-2 (1).csv';
    
    /**
     * Valid values for enum fields
     */
    protected array $validDataTypes = [
        'Multivariate', 'Text', 'Image', 'Time-Series', 'Sequential', 
        'Tabular', 'Relational', 'Domain-Theory', 'Data-Generator', 'Univariate', 'Spatiotemporal', 'Other'
    ];
    
    protected array $validTaskTypes = [
        'Classification', 'Regression', 'Clustering', 
        'Causal-Discovery', 'Relational-Learning', 'Other'
    ];
    
    protected array $validVariableTypes = [
        'Categorical', 'Integer', 'Real', 'Text', 'Binary', 'Ordinal', 'Nominal', 'DateTime'
    ];

    public function run(): void
    {
        $fullPath = database_path($this->csvPath);
        
        if (!file_exists($fullPath)) {
            $this->command->error("❌ File CSV tidak ditemukan: {$fullPath}");
            $this->command->warn("Pastikan file ada di: database/data/");
            return;
        }

        $this->command->info("📦 Starting UCI CSV import...");
        $this->command->info("📂 File: {$this->csvPath}");
        
        // Read CSV
        $csv = Reader::createFromPath($fullPath, 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');
        
        $records = iterator_to_array((new Statement())->process($csv));
        $total = count($records);
        
        $this->command->info("📊 Found {$total} records in CSV");
        
        // Filter valid records (skip empty rows)
        $validRecords = array_filter($records, function($row) {
            return !empty(trim($row['item_page_title'] ?? ''));
        });
        
        $validTotal = count($validRecords);
        $this->command->info("✅ {$validTotal} valid records to import");
        
        if ($validTotal === 0) {
            $this->command->error("❌ No valid records found!");
            return;
        }
        
        // Import counters
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $startTime = microtime(true);
        
        DB::beginTransaction();
        
        try {
            // Get default license
            $defaultLicense = License::firstOrCreate(
                ['license_name' => 'Creative Commons Attribution 4.0 International'],
                [
                    'description' => 'This allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given.',
                    'license_url' => 'https://creativecommons.org/licenses/by/4.0/',
                ]
            );
            
            foreach ($validRecords as $index => $row) {
                try {
                    $this->importDataset($row, $defaultLicense);
                    $imported++;
                    
                    // Progress indicator every 10 records
                    if ($imported % 10 === 0) {
                        $elapsed = round((microtime(true) - $startTime) / 60, 2);
                        $this->command->info("  ✓ Imported {$imported}/{$validTotal} ({$elapsed} mins elapsed)");
                    }
                    
                    // Commit every 50 records to save memory
                    if ($imported % 50 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                        $this->command->info("  💾 Batch committed: {$imported} records");
                    }
                    
                } catch (\Exception $e) {
                    $skipped++;
                    $title = $row['item_page_title'] ?? 'Unknown';
                    $errors[] = "Row " . ($index + 2) . " [{$title}]: " . $e->getMessage();
                    
                    if ($this->command->getOutput()->isVerbose()) {
                        $this->command->warn("  ⚠️ Skipped: {$title}");
                    }
                }
            }
            
            DB::commit();
            
            $totalTime = round((microtime(true) - $startTime) / 60, 2);
            
            $this->command->newLine();
            $this->command->info("✅ Import completed in {$totalTime} minutes!");
            
            $this->command->table(
                ['Metric', 'Value'],
                [
                    ['Total Records in CSV', $total],
                    ['Valid Records', $validTotal],
                    ['Successfully Imported', $imported],
                    ['Skipped (Errors)', $skipped],
                    ['Success Rate', round(($imported / max($validTotal, 1)) * 100, 2) . '%'],
                    ['Total Time', "{$totalTime} mins"],
                ]
            );
            
            // Show errors if verbose
            if (!empty($errors) && $this->command->getOutput()->isVerbose()) {
                $this->command->newLine();
                $this->command->warn("📋 Errors (first 10):");
                foreach (array_slice($errors, 0, 10) as $error) {
                    $this->command->warn("  • {$error}");
                }
                if (count($errors) > 10) {
                    $this->command->warn("  ... and " . (count($errors) - 10) . " more errors");
                }
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Import failed: " . $e->getMessage());
            $this->command->error("Line: " . $e->getLine() . " | File: " . $e->getFile());
            throw $e;
        }
    }
    
    /**
     * Import a single dataset record
     */
    protected function importDataset(array $row, License $defaultLicense): void
    {
        // Parse basic fields
        $name = $this->cleanString($row['item_page_title'] ?? '');
        if (empty($name)) {
            throw new \Exception("Dataset name is required");
        }
        
        $slug = Str::slug($name);
        
        // Check duplicate
        if (Dataset::where('slug', $slug)->orWhere('uci_id', $row['web_scraper_order'] ?? null)->exists()) {
            throw new \Exception("Dataset already exists: {$name}");
        }
        
        // Parse numeric fields
        $numInstances = $this->parseNumericValue($row['Total_Records'] ?? $row['data4'] ?? null);
        $numFeatures = $this->parseNumericValue($row['data5'] ?? null);
        $fileSizeBytes = $this->parseFileSize($row['download_size'] ?? null);
        
        // Parse dates
        $donatedDate = $this->parseDate($row['Donation_Date'] ?? null);
        
        // Parse boolean
        $hasMissing = $this->parseBoolean($row['has_missing_values'] ?? $row['data6'] ?? null);
        
        // Parse data type and task type
        $dataType = $this->parseDataType($row['Data_File_Structure'] ?? $row['data3'] ?? null);
        $taskType = $this->parseTaskType($row['data2'] ?? null);
        
        // Parse DOI
        $doiString = $this->cleanString($row['DOI'] ?? null);
        $doiId = null;
        if ($doiString && Str::startsWith($doiString, '10.')) {
            $doi = Doi::firstOrCreate(
                ['doi_string' => $doiString],
                ['resolution_url' => "https://doi.org/{$doiString}"]
            );
            $doiId = $doi->doi_id;
        }
        
        // Parse creators
        $creators = [];
        for ($i = 1; $i <= 5; $i++) {
            $creatorName = $this->cleanString($row["creators_{$i}"] ?? null);
            if (!empty($creatorName)) {
                $creators[] = $creatorName;
            }
        }
        
        // Create Dataset
        $dataset = Dataset::create([
            // Identifiers
            'uci_id' => $row['web_scraper_order'] ?? null,
            'slug' => $slug,
            'name' => $name,
            'display_name' => $name,
            
            // Descriptions
            'description' => $this->cleanString($row['Additional_Information'] ?? ''),
            'abstract' => $this->cleanString($row['dataset_details'] ?? ''),
            'summary' => $this->cleanString($row['title'] ?? ''),
            
            // Numeric
            'num_instances' => $numInstances,
            'num_features' => $numFeatures,
            'file_size_bytes' => $fileSizeBytes,
            
            // Enum fields
            'data_type' => $dataType,
            'task_type' => $taskType,
            
            // String fields
            'subject_area' => $this->cleanString($row['data'] ?? 'Other'),
            'domain' => null,
            'dataset_url' => $this->cleanString($row['item_page_link'] ?? null),
            'detail_url' => $this->cleanString($row['item_page_link'] ?? null),
            'thumbnail_url' => $this->cleanString($row['image_1'] ?? $row['image'] ?? null),
            'large_image_url' => null,
            
            // Status & dates
            'status' => 'available',
            'donated_date' => $donatedDate,
            
            // Counters
            'view_count' => $this->parseNumericValue($row['views_count'] ?? 0),
            'download_count' => 0,
            'citation_count' => $this->parseNumericValue($row['citation_count'] ?? 0),
            
            // Boolean
            'has_missing_values' => $hasMissing,
            
            // Foreign keys
            'license_id' => $defaultLicense->license_id,
            'doi_id' => $doiId,
        ]);
        
        // Create Dataset Description
        $this->createDatasetDescription($dataset, $row);
        
        // Attach Keywords
        $this->attachKeywords($dataset, $row);
        
        // Attach Creators
        $this->attachCreators($dataset, $creators);
        
        // Create File record (placeholder)
        $this->createFileRecord($dataset, $row);
        
        // Create Image record
        $this->createImageRecord($dataset, $row);
    }
    
    /**
     * Create dataset description
     */
    protected function createDatasetDescription(Dataset $dataset, array $row): void
    {
        $data = array_filter([
            'dataset_id' => $dataset->dataset_id,
            'purpose' => $this->cleanString($row['dataset_details'] ?? ''),
            'additional_info' => $this->cleanString($row['Additional_Information'] ?? ''),
        ]);
        
        if (!empty($data)) {
            DatasetDescription::create($data);
        }
    }
    
    /**
     * Attach keywords
     */
    protected function attachKeywords(Dataset $dataset, array $row): void
    {
        $keywords = [];
        
        // From Data_File_Structure
        if (!empty($row['Data_File_Structure'])) {
            $keywords = array_merge($keywords, 
                array_filter(array_map('trim', explode(',', $row['Data_File_Structure'])))
            );
        }
        
        // From subject area
        if (!empty($row['data'])) {
            $keywords[] = $row['data'];
        }
        
        // From task type
        if (!empty($row['data2'])) {
            $keywords[] = $row['data2'];
        }
        
        foreach (array_unique($keywords) as $kwName) {
            $kwName = trim($kwName);
            if (empty($kwName) || strlen($kwName) < 2) continue;
            
            $keyword = Keyword::firstOrCreate(
                ['keyword_name' => $kwName],
                ['slug' => Str::slug($kwName)]
            );
            
            $dataset->keywords()->syncWithoutDetaching([$keyword->keyword_id]);
        }
    }
    
    /**
     * Attach creators
     */
    protected function attachCreators(Dataset $dataset, array $creators): void
    {
        foreach ($creators as $index => $creatorName) {
            $person = Person::firstOrCreate(
                ['name' => $creatorName],
                ['affiliation' => null]
            );
            
            $dataset->contributors()->syncWithoutDetaching([
                $person->person_id => [
                    'contribution_role' => $index === 0 ? 'creator' : 'contributor',
                    'display_order' => $index,
                ]
            ]);
        }
    }
    
    /**
     * Create file record (placeholder)
     */
    protected function createFileRecord(Dataset $dataset, array $row): void
    {
        $filename = Str::slug($dataset->name) . '.csv';
        $fileSize = $this->parseFileSize($row['download_size'] ?? null);
        
        $file = File::firstOrCreate(
            ['filename' => $filename, 'file_path' => "datasets/{$dataset->slug}/{$filename}"],
            [
                'original_filename' => $dataset->name . '.data',
                'file_size_bytes' => $fileSize,
                'file_format' => 'csv',
                'mime_type' => 'text/csv',
                'description' => 'Main dataset file (placeholder)',
            ]
        );
        
        $dataset->files()->syncWithoutDetaching([
            $file->file_id => [
                'file_role' => 'data',
                'is_default' => true,
                'display_order' => 0,
            ]
        ]);
    }
    
    /**
     * Create image record
     */
    protected function createImageRecord(Dataset $dataset, array $row): void
    {
        $imageUrl = $this->cleanString($row['image_1'] ?? $row['image'] ?? null);
        if (empty($imageUrl)) return;
        
        $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? $imageUrl);
        
        $image = Image::firstOrCreate(
            ['file_path' => $imageUrl],
            [
                'filename' => $pathInfo['basename'] ?? 'thumbnail.jpg',
                'original_filename' => $pathInfo['basename'] ?? 'thumbnail.jpg',
                'file_size_bytes' => null,
                'mime_type' => 'image/' . ($pathInfo['extension'] ?? 'jpg'),
                'alt_text' => $dataset->name,
                'image_type' => 'thumbnail',
            ]
        );
        
        $dataset->images()->syncWithoutDetaching([
            $image->image_id => [
                'role' => 'thumbnail',
                'is_primary' => true,
                'display_order' => 0,
            ]
        ]);
        
        // Update dataset thumbnail_url
        $dataset->thumbnail_url = $imageUrl;
        $dataset->saveQuietly();
    }
    
    // === Helper Methods ===
    
    protected function cleanString(?string $value): ?string
    {
        if ($value === null) return null;
        
        // Clean HTML entities, special characters, and extra whitespace
        $cleaned = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleaned = preg_replace('/[^\p{L}\p{N}\p{P}\p{S}\p{Z}\s]/u', '', $cleaned);
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
        
        return !empty($cleaned) ? $cleaned : null;
    }
    
    protected function parseNumericValue($value): ?int
    {
        if ($value === null || $value === '' || $value === '-' || $value === 'null' || $value === 'N/A') return null;
        
        $value = strtoupper(trim($value));
        
        // Handle "58K", "1.2M", etc.
        if (preg_match('/^([\d.]+)\s*([KMB])?$/', $value, $matches)) {
            $num = (float) $matches[1];
            $suffix = $matches[2] ?? null;
            return match($suffix) {
                'K' => (int) ($num * 1000),
                'M' => (int) ($num * 1000000),
                'B' => (int) ($num * 1000000000),
                default => (int) $num,
            };
        }
        
        // Fallback: extract digits only
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return $cleaned !== '' ? (int) $cleaned : null;
    }
    
    protected function parseFileSize($value): ?int
    {
        if (empty($value)) return null;
        
        $value = strtoupper(trim($value));
        // Remove parentheses: "(3.3 MB)" -> "3.3 MB"
        $value = preg_replace('/[()]/', '', $value);
        
        $multipliers = ['B' => 1, 'KB' => 1024, 'MB' => 1048576, 'GB' => 1073741824];
        
        foreach ($multipliers as $unit => $mult) {
            if (str_contains($value, $unit)) {
                if (preg_match('/([\d.]+)/', $value, $matches)) {
                    return (int) ((float) $matches[1] * $mult);
                }
            }
        }
        
        return null;
    }
    
    protected function parseDate($value): ?string
    {
        if (empty($value)) return null;
        
        $value = trim($value);
        
        // Try various date formats
        $formats = ['Y-m-d', 'n/j/Y', 'd/m/Y', 'F j, Y', 'M d, Y', 'Y'];
        
        foreach ($formats as $format) {
            try {
                $date = \DateTime::createFromFormat($format, $value);
                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception) {
                continue;
            }
        }
        
        // Fallback: let Carbon handle it
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
    
    protected function parseBoolean($value): bool
    {
        if (is_bool($value)) return $value;
        if ($value === null) return false;
        
        $lower = strtolower(trim((string) $value));
        return in_array($lower, ['1', 'true', 'yes', 'y', 'on']);
    }
    
    protected function parseDataType($value): ?string
    {
        if (empty($value)) return null;
        
        $value = trim($value);
        
        // Check if value matches any valid data type
        foreach ($this->validDataTypes as $type) {
            if (stripos($value, $type) !== false) {
                return $type;
            }
        }
        
        // Check for common variations
        if (stripos($value, 'tabular') !== false) return 'Tabular';
        if (stripos($value, 'multivariate') !== false) return 'Multivariate';
        if (stripos($value, 'text') !== false) return 'Text';
        if (stripos($value, 'image') !== false) return 'Image';
        if (stripos($value, 'time-series') !== false || stripos($value, 'timeseries') !== false) return 'Time-Series';
        if (stripos($value, 'sequential') !== false) return 'Sequential';
        
        return null;
    }
    
    protected function parseTaskType($value): ?string
    {
        if (empty($value)) return null;
        
        $value = trim($value);
        
        // Check if value matches any valid task type
        foreach ($this->validTaskTypes as $type) {
            if (stripos($value, $type) !== false) {
                return $type;
            }
        }
        
        // Check for common variations
        if (stripos($value, 'classification') !== false) return 'Classification';
        if (stripos($value, 'regression') !== false) return 'Regression';
        if (stripos($value, 'clustering') !== false) return 'Clustering';
        
        return null;
    }
}