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

class UciCsvDuplicateSafeSeeder extends Seeder
{
    protected string $csvPath = 'data/archive-ics-uci-edu-2026-05-14-2 (1).csv';
    
    protected array $validDataTypes = [
        'Multivariate', 'Text', 'Image', 'Time-Series', 'Sequential', 
        'Tabular', 'Relational', 'Domain-Theory', 'Data-Generator', 'Univariate', 'Spatiotemporal', 'Other'
    ];
    
    protected array $validTaskTypes = [
        'Classification', 'Regression', 'Clustering', 
        'Causal-Discovery', 'Relational-Learning', 'Other'
    ];

    public function run(): void
    {
        $fullPath = database_path($this->csvPath);
        
        if (!file_exists($fullPath)) {
            $this->command->error("❌ File CSV tidak ditemukan: {$fullPath}");
            return;
        }

        $this->command->info("📦 Starting UCI CSV import (Duplicate-Safe Mode)...");
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
        
        // Filter valid records
        $validRecords = array_filter($records, function($row) {
            return !empty(trim($row['item_page_title'] ?? ''));
        });
        
        $validTotal = count($validRecords);
        $this->command->info("✅ {$validTotal} valid records to process");
        
        if ($validTotal === 0) {
            $this->command->error("❌ No valid records found!");
            return;
        }
        
        // Statistics
        $imported = 0;
        $skipped = 0;
        $updated = 0;
        $errors = [];
        $startTime = microtime(true);
        
        DB::beginTransaction();
        
        try {
            // Get or create default license
            $defaultLicense = License::firstOrCreate(
                ['license_name' => 'Creative Commons Attribution 4.0 International'],
                [
                    'description' => 'This allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given.',
                    'license_url' => 'https://creativecommons.org/licenses/by/4.0/',
                ]
            );
            
            foreach ($validRecords as $index => $row) {
                try {
                    $result = $this->importDatasetSafe($row, $defaultLicense);
                    
                    if ($result === 'created') {
                        $imported++;
                    } elseif ($result === 'skipped') {
                        $skipped++;
                    } elseif ($result === 'updated') {
                        $updated++;
                    }
                    
                    // Progress indicator
                    if (($index + 1) % 10 === 0) {
                        $elapsed = round((microtime(true) - $startTime) / 60, 2);
                        $this->command->info("  ✓ Processed " . ($index + 1) . "/{$validTotal} (Created: {$imported}, Skipped: {$skipped}, Updated: {$updated}) - {$elapsed} mins");
                    }
                    
                    // Batch commit
                    if (($index + 1) % 50 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                        $this->command->info("  💾 Batch committed: " . ($index + 1) . " records");
                    }
                    
                } catch (\Exception $e) {
                    $title = $row['item_page_title'] ?? 'Unknown';
                    $errors[] = "Row " . ($index + 2) . " [{$title}]: " . $e->getMessage();
                    
                    if ($this->command->getOutput()->isVerbose()) {
                        $this->command->warn("  ⚠️ Error: {$title}");
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
                    ['New Datasets Created', $imported],
                    ['Skipped (Already Exist)', $skipped],
                    ['Updated (Existing)', $updated],
                    ['Errors', count($errors)],
                    ['Success Rate', round((($imported + $updated) / max($validTotal, 1)) * 100, 2) . '%'],
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
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Import failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Import dataset safely (check for duplicates)
     * Returns: 'created', 'skipped', or 'updated'
     */
    protected function importDatasetSafe(array $row, License $defaultLicense): string
    {
        $name = $this->cleanString($row['item_page_title'] ?? '');
        if (empty($name)) {
            throw new \Exception("Dataset name is required");
        }
        
        $slug = Str::slug($name);
        $uciId = $row['web_scraper_order'] ?? null;
        
        // Check if dataset already exists
        $existingDataset = Dataset::where('slug', $slug)
            ->orWhere(function($query) use ($uciId) {
                if ($uciId) {
                    $query->orWhere('uci_id', $uciId);
                }
            })
            ->first();
        
        if ($existingDataset) {
            $this->command->warn("  ⏭️ Skipped (exists): {$name}");
            return 'skipped';
        }
        
        // Parse data
        $numInstances = $this->parseNumericValue($row['Total_Records'] ?? $row['data4'] ?? null);
        $numFeatures = $this->parseNumericValue($row['data5'] ?? null);
        $fileSizeBytes = $this->parseFileSize($row['download_size'] ?? null);
        $donatedDate = $this->parseDate($row['Donation_Date'] ?? null);
        $hasMissing = $this->parseBoolean($row['has_missing_values'] ?? $row['data6'] ?? null);
        $dataType = $this->parseDataType($row['Data_File_Structure'] ?? $row['data3'] ?? null);
        $taskType = $this->parseTaskType($row['data2'] ?? null);
        
        // Handle DOI
        $doiString = $this->cleanString($row['DOI'] ?? null);
        $doiId = null;
        if ($doiString && Str::startsWith($doiString, '10.')) {
            $doi = Doi::firstOrCreate(
                ['doi_string' => $doiString],
                ['resolution_url' => "https://doi.org/{$doiString}"]
            );
            $doiId = $doi->doi_id;
        }
        
        // Create Dataset
        $dataset = Dataset::create([
            'uci_id' => $uciId,
            'slug' => $slug,
            'name' => $name,
            'display_name' => $name,
            'description' => $this->cleanString($row['Additional_Information'] ?? ''),
            'abstract' => $this->cleanString($row['dataset_details'] ?? ''),
            'summary' => $this->cleanString($row['title'] ?? ''),
            'num_instances' => $numInstances,
            'num_features' => $numFeatures,
            'file_size_bytes' => $fileSizeBytes,
            'data_type' => $dataType,
            'task_type' => $taskType,
            'subject_area' => $this->cleanString($row['data'] ?? 'Other'),
            'dataset_url' => $this->cleanString($row['item_page_link'] ?? null),
            'detail_url' => $this->cleanString($row['item_page_link'] ?? null),
            'thumbnail_url' => $this->cleanString($row['image_1'] ?? $row['image'] ?? null),
            'status' => 'available',
            'donated_date' => $donatedDate,
            'view_count' => $this->parseNumericValue($row['views_count'] ?? 0),
            'download_count' => 0,
            'citation_count' => $this->parseNumericValue($row['citation_count'] ?? 0),
            'has_missing_values' => $hasMissing,
            'license_id' => $defaultLicense->license_id,
            'doi_id' => $doiId,
        ]);
        
        // Create related records
        $this->createDatasetDescription($dataset, $row);
        $this->attachKeywords($dataset, $row);
        $this->attachCreators($dataset, $row);
        $this->createFileRecord($dataset, $row);
        $this->createImageRecord($dataset, $row);
        
        return 'created';
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
        
        if (!empty($row['Data_File_Structure'])) {
            $keywords = array_merge($keywords, 
                array_filter(array_map('trim', explode(',', $row['Data_File_Structure'])))
            );
        }
        
        if (!empty($row['data'])) {
            $keywords[] = $row['data'];
        }
        
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
            
            // Check if already attached
            if (!$dataset->keywords()->where('keyword_id', $keyword->keyword_id)->exists()) {
                $dataset->keywords()->attach($keyword->keyword_id);
            }
        }
    }
    
    /**
     * Attach creators
     */
    protected function attachCreators(Dataset $dataset, array $row): void
    {
        $creators = [];
        for ($i = 1; $i <= 5; $i++) {
            $creatorName = $this->cleanString($row["creators_{$i}"] ?? null);
            if (!empty($creatorName)) {
                $creators[] = $creatorName;
            }
        }
        
        foreach ($creators as $index => $creatorName) {
            $person = Person::firstOrCreate(
                ['name' => $creatorName],
                ['affiliation' => null]
            );
            
            // Check if already attached
            if (!$dataset->contributors()->where('person_id', $person->person_id)->exists()) {
                $dataset->contributors()->attach($person->person_id, [
                    'contribution_role' => $index === 0 ? 'creator' : 'contributor',
                    'display_order' => $index,
                ]);
            }
        }
    }
    
    /**
     * Create file record
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
        
        // Check if already attached
        if (!$dataset->files()->where('file_id', $file->file_id)->exists()) {
            $dataset->files()->attach($file->file_id, [
                'file_role' => 'data',
                'is_default' => true,
                'display_order' => 0,
            ]);
        }
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
        
        // Check if already attached
        if (!$dataset->images()->where('image_id', $image->image_id)->exists()) {
            $dataset->images()->attach($image->image_id, [
                'role' => 'thumbnail',
                'is_primary' => true,
                'display_order' => 0,
            ]);
            
            // Update dataset thumbnail_url
            $dataset->thumbnail_url = $imageUrl;
            $dataset->saveQuietly();
        }
    }
    
    // === Helper Methods (same as before) ===
    
    protected function cleanString(?string $value): ?string
    {
        if ($value === null) return null;
        $cleaned = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleaned = preg_replace('/[^\p{L}\p{N}\p{P}\p{S}\p{Z}\s]/u', '', $cleaned);
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
        return !empty($cleaned) ? $cleaned : null;
    }
    
    protected function parseNumericValue($value): ?int
    {
        if ($value === null || $value === '' || $value === '-' || $value === 'null' || $value === 'N/A') return null;
        $value = strtoupper(trim($value));
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
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return $cleaned !== '' ? (int) $cleaned : null;
    }
    
    protected function parseFileSize($value): ?int
    {
        if (empty($value)) return null;
        $value = strtoupper(trim($value));
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
        foreach ($this->validDataTypes as $type) {
            if (stripos($value, $type) !== false) {
                return $type;
            }
        }
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
        foreach ($this->validTaskTypes as $type) {
            if (stripos($value, $type) !== false) {
                return $type;
            }
        }
        if (stripos($value, 'classification') !== false) return 'Classification';
        if (stripos($value, 'regression') !== false) return 'Regression';
        if (stripos($value, 'clustering') !== false) return 'Clustering';
        return null;
    }
}