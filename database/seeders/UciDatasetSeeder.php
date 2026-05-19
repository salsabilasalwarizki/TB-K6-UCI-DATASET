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

class UciDatasetSeeder extends Seeder
{
    /**
     * CSV file path relative to database directory
     */
    protected string $csvPath = 'data/uci_datasets.csv';
    
    /**
     * Batch size for bulk inserts (memory optimization)
     */
    protected int $batchSize = 50;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fullPath = database_path($this->csvPath);
        
        if (!file_exists($fullPath)) {
            $this->command->error("CSV file not found: {$fullPath}");
            return;
        }
        
        $this->command->info("📦 Starting UCI Dataset import from: {$this->csvPath}");
        
        // Read CSV with League/csv (more robust than str_getcsv)
        $csv = Reader::createFromPath($fullPath, 'r');
        $csv->setHeaderOffset(0); // First row is header
        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');
        
        // Get records
        $records = (new Statement())->process($csv);
        $total = iterator_count($records);
        
        $this->command->info("📊 Found {$total} records to import");
        
        // Reset counters
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        
        // Start transaction for better performance
        DB::beginTransaction();
        
        try {
            foreach ($records as $index => $record) {
                try {
                    $this->importRecord($record);
                    $imported++;
                    
                    // Progress indicator
                    if (($index + 1) % 10 === 0) {
                        $this->command->info("✓ Processed " . ($index + 1) . "/{$total}");
                    }
                    
                    // Commit in batches to free memory
                    if (($index + 1) % $this->batchSize === 0) {
                        DB::commit();
                        DB::beginTransaction();
                        $this->command->info("💾 Batch committed: " . ($index + 1) . " records");
                    }
                    
                } catch (\Exception $e) {
                    $skipped++;
                    $this->command->warn("⚠️ Skipped row " . ($index + 2) . ": " . $e->getMessage());
                    
                    // Log detailed error for debugging
                    if ($this->command->getOutput()->isVerbose()) {
                        $this->command->error("Row data: " . json_encode(array_slice($record, 0, 5)));
                    }
                }
            }
            
            // Final commit
            DB::commit();
            
            // Summary
            $this->command->newLine();
            $this->command->info("✅ Import completed!");
            $this->command->table(
                ['Metric', 'Value'],
                [
                    ['Total Records', $total],
                    ['Successfully Imported', $imported],
                    ['Skipped (Errors)', $skipped],
                    ['Success Rate', round(($imported / max($total, 1)) * 100, 2) . '%'],
                ]
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Import failed: " . $e->getMessage());
            $this->command->error("Line: " . $e->getLine() . " | File: " . $e->getFile());
            throw $e;
        }
    }
    
    /**
     * Import a single CSV record into database
     */
    protected function importRecord(array $record): void
    {
        // === Parse CSV fields ===
        $name = trim($record['name'] ?? $record['dataset_name'] ?? '');
        if (empty($name)) {
            throw new \Exception("Dataset name is required");
        }
        
        $slug = Str::slug($record['slug'] ?? $name);
        $uciId = $record['uci_id'] ?? $record['dataset_id'] ?? null;
        
        // Check if already exists (by slug or uci_id)
        if (Dataset::where('slug', $slug)->orWhere('uci_id', $uciId)->exists()) {
            throw new \Exception("Dataset already exists: {$name}");
        }
        
        // === Prepare Dataset Data ===
        $datasetData = [
            'uci_id' => $uciId,
            'slug' => $slug,
            'name' => $name,
            'display_name' => trim($record['display_name'] ?? $record['title'] ?? ''),
            'description' => trim($record['description'] ?? $record['abstract'] ?? ''),
            'abstract' => trim($record['abstract'] ?? ''),
            'summary' => trim($record['summary'] ?? ''),
            
            // Numeric fields - parse safely
            'num_instances' => $this->parseInt($record['num_instances'] ?? $record['instances'] ?? null),
            'num_features' => $this->parseInt($record['num_features'] ?? $record['features'] ?? null),
            'num_classes' => $this->parseInt($record['num_classes'] ?? $record['classes'] ?? null),
            'file_size_bytes' => $this->parseFileSize($record['file_size'] ?? $record['size'] ?? null),
            
            // Enum fields - validate against allowed values
            'data_type' => $this->validateEnum($record['data_type'] ?? null, config('datasets.filters.data_types')),
            'task_type' => $this->validateEnum($record['task_type'] ?? $record['associated_tasks'] ?? null, config('datasets.filters.task_types')),
            
            // String fields
            'subject_area' => trim($record['subject_area'] ?? $record['subject'] ?? ''),
            'domain' => trim($record['domain'] ?? ''),
            'dataset_url' => $this->validateUrl($record['dataset_url'] ?? $record['url'] ?? null),
            'detail_url' => $this->validateUrl($record['detail_url'] ?? null),
            'thumbnail_url' => $this->validateUrl($record['thumbnail_url'] ?? $record['thumbnail'] ?? null),
            'large_image_url' => $this->validateUrl($record['large_image_url'] ?? $record['large_image'] ?? null),
            
            // Status & dates
            'status' => $this->parseStatus($record['status'] ?? 'available'),
            'donated_date' => $this->parseDate($record['donated_date'] ?? $record['date_donated'] ?? null),
            'linked_date' => $this->parseDate($record['linked_date'] ?? null),
            
            // Counters
            'view_count' => $this->parseInt($record['view_count'] ?? $record['views'] ?? 0),
            'download_count' => $this->parseInt($record['download_count'] ?? $record['downloads'] ?? 0),
            'citation_count' => $this->parseInt($record['citation_count'] ?? $record['citations'] ?? 0),
            
            // Boolean
            'has_missing_values' => $this->parseBoolean($record['has_missing_values'] ?? $record['missing_values'] ?? false),
            
            // Foreign keys (will be resolved later)
            'user_id' => null, // Default to null or find by email if available
            'license_id' => null,
            'doi_id' => null,
        ];
        
        // === Create Dataset ===
        $dataset = Dataset::create($datasetData);
        
        // === Create Dataset Description ===
        $this->createDatasetDescription($dataset, $record);
        
        // === Create/Attach Keywords ===
        $this->attachKeywords($dataset, $record);
        
        // === Create/Attach Contributors ===
        $this->attachContributors($dataset, $record);
        
        // === Create Variables (if available) ===
        $this->createVariables($dataset, $record);
        
        // === Create/Attach Papers ===
        $this->attachPapers($dataset, $record);
        
        // === Create DOI record ===
        $this->createDoi($dataset, $record);
        
        // === Create License record ===
        $this->createLicense($dataset, $record);
        
        // === Create File records ===
        $this->createFiles($dataset, $record);
        
        // === Create Image records ===
        $this->createImages($dataset, $record);
    }
    
    /**
     * Create dataset_description record
     */
    protected function createDatasetDescription(Dataset $dataset, array $record): void
    {
        $description = array_filter([
            'dataset_id' => $dataset->dataset_id,
            'purpose' => trim($record['purpose'] ?? $record['dataset_purpose'] ?? ''),
            'funding' => trim($record['funding'] ?? ''),
            'instances_represent' => trim($record['instances_represent'] ?? $record['what_instances_represent'] ?? ''),
            'data_splits' => trim($record['data_splits'] ?? $record['recommended_splits'] ?? ''),
            'sensitive_data' => trim($record['sensitive_data'] ?? ''),
            'preprocessing' => trim($record['preprocessing'] ?? ''),
            'additional_info' => trim($record['additional_info'] ?? $record['notes'] ?? ''),
            'citation_requests' => trim($record['citation_requests'] ?? $record['how_to_cite'] ?? ''),
        ]);
        
        if (!empty($description)) {
            DatasetDescription::create($description);
        }
    }
    
    /**
     * Attach keywords to dataset
     */
    protected function attachKeywords(Dataset $dataset, array $record): void
    {
        $keywordsRaw = $record['keywords'] ?? $record['tags'] ?? $record['keyword'] ?? '';
        if (empty($keywordsRaw)) return;
        
        // Parse comma or pipe separated keywords
        $keywords = preg_split('/[,|;]\s*/', $keywordsRaw, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($keywords as $kwName) {
            $kwName = trim($kwName);
            if (empty($kwName)) continue;
            
            // Find or create keyword
            $keyword = Keyword::firstOrCreate(
                ['keyword_name' => $kwName],
                [
                    'slug' => Str::slug($kwName),
                    'category' => 'other',
                ]
            );
            
            // Attach to dataset (avoid duplicates)
            $dataset->keywords()->syncWithoutDetaching([$keyword->keyword_id]);
        }
    }
    
    /**
     * Attach contributors/creators to dataset
     */
    protected function attachContributors(Dataset $dataset, array $record): void
    {
        // Parse creators (format: "Name1, Name2 | Affiliation")
        $creatorsRaw = $record['creators'] ?? $record['authors'] ?? $record['donated_by'] ?? '';
        if (empty($creatorsRaw)) return;
        
        $contributors = preg_split('/[,|]\s*/', $creatorsRaw, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($contributors as $index => $contribRaw) {
            $contribRaw = trim($contribRaw);
            if (empty($contribRaw)) continue;
            
            // Parse name and affiliation (if present)
            $parts = explode('|', $contribRaw, 2);
            $name = trim($parts[0]);
            $affiliation = trim($parts[1] ?? '');
            
            // Find or create person
            $person = Person::firstOrCreate(
                ['name' => $name, 'affiliation' => $affiliation ?: null],
                ['email' => null, 'orcid' => null]
            );
            
            // Determine role
            $role = ($index === 0) ? 'creator' : 'contributor';
            
            // Attach with pivot data
            $dataset->contributors()->syncWithoutDetaching([
                $person->person_id => [
                    'contribution_role' => $role,
                    'display_order' => $index,
                ]
            ]);
        }
    }
    
    /**
     * Create variables for dataset
     */
    protected function createVariables(Dataset $dataset, array $record): void
    {
        // Parse variable info (format: "var1:type:role:desc|var2:type:role:desc")
        $variablesRaw = $record['variables'] ?? $record['variable_info'] ?? $record['attributes'] ?? '';
        if (empty($variablesRaw)) return;
        
        $varEntries = preg_split('/[|;]\s*/', $variablesRaw, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($varEntries as $index => $varRaw) {
            $parts = explode(':', trim($varRaw), 4);
            if (count($parts) < 2) continue;
            
            $varName = trim($parts[0]);
            $varType = trim($parts[1]);
            $role = trim($parts[2] ?? 'feature');
            $description = trim($parts[3] ?? '');
            
            // Validate variable type
            $validTypes = config('datasets.filters.variable_types', []);
            if (!in_array($varType, $validTypes)) {
                $varType = 'Other'; // Fallback
            }
            
            $variable = Variable::create([
                'dataset_id' => $dataset->dataset_id,
                'variable_name' => $varName,
                'display_name' => $varName,
                'role' => in_array($role, ['feature', 'target', 'id', 'metadata', 'other']) ? $role : 'feature',
                'variable_type' => $varType,
                'description' => $description,
                'display_order' => $index,
                'is_visible' => true,
            ]);
            
            // Parse categories for categorical variables (format: "cat1,cat2,cat3")
            if ($varType === 'Categorical' && !empty($parts[3] ?? '')) {
                $categories = preg_split('/,\s*/', $description, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($categories as $catIndex => $catValue) {
                    VariableCategory::create([
                        'variable_id' => $variable->variable_id,
                        'category_value' => trim($catValue),
                        'category_label' => trim($catValue),
                        'display_order' => $catIndex,
                    ]);
                }
            }
        }
    }
    
    /**
     * Attach papers to dataset
     */
    protected function attachPapers(Dataset $dataset, array $record): void
    {
        // Parse paper info (format: "Title|Authors|Venue|Year|DOI|URL")
        $papersRaw = $record['papers'] ?? $record['citations'] ?? $record['introductory_paper'] ?? '';
        if (empty($papersRaw)) return;
        
        $paperEntries = preg_split('/[|;]\s*#?\s*/', $papersRaw, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($paperEntries as $paperRaw) {
            $parts = explode('|', trim($paperRaw), 6);
            if (count($parts) < 1) continue;
            
            $title = trim($parts[0] ?? '');
            if (empty($title)) continue;
            
            // Find or create paper
            $paper = Paper::firstOrCreate(
                ['title' => $title],
                [
                    'authors' => trim($parts[1] ?? ''),
                    'venue' => trim($parts[2] ?? ''),
                    'publication_year' => $this->parseInt($parts[3] ?? null),
                    'doi' => $this->validateDoi($parts[4] ?? null),
                    'url' => $this->validateUrl($parts[5] ?? null),
                    'abstract' => trim($record['paper_abstract'] ?? ''),
                ]
            );
            
            // Attach with citation type
            $citationType = 'citing'; // Default
            if (stripos($paperRaw, 'introductory') !== false) $citationType = 'introductory';
            elseif (stripos($paperRaw, 'related') !== false) $citationType = 'related';
            
            $dataset->papers()->syncWithoutDetaching([
                $paper->paper_id => [
                    'citation_type' => $citationType,
                    'is_primary' => ($citationType === 'introductory'),
                ]
            ]);
        }
    }
    
    /**
     * Create DOI record and attach to dataset
     */
    protected function createDoi(Dataset $dataset, array $record): void
    {
        $doiString = trim($record['doi'] ?? $record['doi_string'] ?? '');
        if (empty($doiString)) return;
        
        $doi = Doi::firstOrCreate(
            ['doi_string' => $doiString],
            ['resolution_url' => $this->validateUrl($record['doi_url'] ?? "https://doi.org/{$doiString}")]
        );
        
        $dataset->doi_id = $doi->doi_id;
        $dataset->saveQuietly();
    }
    
    /**
     * Create License record and attach to dataset
     */
    protected function createLicense(Dataset $dataset, array $record): void
    {
        $licenseName = trim($record['license'] ?? $record['license_name'] ?? 'Creative Commons Attribution 4.0 International');
        if (empty($licenseName)) return;
        
        $license = License::firstOrCreate(
            ['license_name' => $licenseName],
            [
                'description' => trim($record['license_description'] ?? $record['license_info'] ?? ''),
                'license_url' => $this->validateUrl($record['license_url'] ?? null),
            ]
        );
        
        $dataset->license_id = $license->license_id;
        $dataset->saveQuietly();
    }
    
    /**
     * Create file records for dataset
     */
    protected function createFiles(Dataset $dataset, array $record): void
    {
        // Parse file info (format: "filename|format|size|role|path")
        $filesRaw = $record['files'] ?? $record['file_info'] ?? $record['download_files'] ?? '';
        if (empty($filesRaw)) return;
        
        $fileEntries = preg_split('/[|;]\s*#?\s*/', $filesRaw, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($fileEntries as $index => $fileRaw) {
            $parts = explode('|', trim($fileRaw), 5);
            if (count($parts) < 1) continue;
            
            $filename = trim($parts[0] ?? '');
            if (empty($filename)) continue;
            
            $fileFormat = strtolower(trim($parts[1] ?? pathinfo($filename, PATHINFO_EXTENSION)));
            $fileSize = $this->parseFileSize($parts[2] ?? null);
            $fileRole = in_array($parts[3] ?? 'data', ['data', 'documentation', 'code', 'example', 'test', 'other']) 
                ? $parts[3] : 'data';
            $filePath = trim($parts[4] ?? "datasets/{$dataset->slug}/{$filename}");
            
            // Create or find file
            $file = File::firstOrCreate(
                ['filename' => $filename, 'file_path' => $filePath],
                [
                    'original_filename' => $filename,
                    'file_size_bytes' => $fileSize,
                    'file_format' => $fileFormat,
                    'mime_type' => $this->getMimeType($fileFormat),
                    'description' => trim($record['file_description'] ?? ''),
                ]
            );
            
            // Attach to dataset via pivot
            $dataset->files()->syncWithoutDetaching([
                $file->file_id => [
                    'file_role' => $fileRole,
                    'is_default' => ($index === 0), // First file is default
                    'display_order' => $index,
                ]
            ]);
        }
    }
    
    /**
     * Create image records for dataset
     */
    protected function createImages(Dataset $dataset, array $record): void
    {
        // Handle thumbnail/large images
        $imageUrl = $record['thumbnail_url'] ?? $record['large_image_url'] ?? null;
        if (empty($imageUrl)) return;
        
        $image = Image::firstOrCreate(
            ['file_path' => parse_url($imageUrl, PHP_URL_PATH) ?? $imageUrl],
            [
                'filename' => basename(parse_url($imageUrl, PHP_URL_PATH) ?? $imageUrl),
                'original_filename' => basename(parse_url($imageUrl, PHP_URL_PATH) ?? $imageUrl),
                'file_size_bytes' => null,
                'mime_type' => 'image/' . strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION) ?? 'jpg'),
                'alt_text' => trim($record['image_alt'] ?? $dataset->name),
                'caption' => trim($record['image_caption'] ?? ''),
                'image_type' => !empty($record['thumbnail_url']) ? 'thumbnail' : 'large',
            ]
        );
        
        $dataset->images()->syncWithoutDetaching([
            $image->image_id => [
                'role' => $image->image_type,
                'is_primary' => true,
                'display_order' => 0,
            ]
        ]);
    }
    
    // === Helper Methods ===
    
    protected function parseInt($value): ?int
    {
        if ($value === null || $value === '') return null;
        $cleaned = preg_replace('/[^0-9]/', '', (string) $value);
        return $cleaned !== '' ? (int) $cleaned : null;
    }
    
    protected function parseFileSize($value): ?int
    {
        if ($value === null || $value === '') return null;
        
        $value = strtoupper(trim($value));
        $multipliers = ['B' => 1, 'KB' => 1024, 'MB' => 1048576, 'GB' => 1073741824, 'TB' => 1099511627776];
        
        foreach ($multipliers as $unit => $mult) {
            if (str_ends_with($value, $unit)) {
                $num = (float) preg_replace('/[^0-9.]/', '', $value);
                return (int) ($num * $mult);
            }
        }
        
        return $this->parseInt($value);
    }
    
    protected function validateEnum($value, array $allowed): ?string
    {
        if (empty($value)) return null;
        $value = trim($value);
        return in_array($value, $allowed) ? $value : null;
    }
    
    protected function validateUrl($value): ?string
    {
        if (empty($value)) return null;
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : null;
    }
    
    protected function validateDoi($value): ?string
    {
        if (empty($value)) return null;
        $value = trim($value);
        // DOI format: 10.xxxx/xxxx
        return preg_match('/^10\.\d{4,}\/.+$/', $value) ? $value : null;
    }
    
    protected function parseDate($value): ?string
    {
        if (empty($value)) return null;
        
        // Try various date formats
        $formats = ['Y-m-d', 'n/j/Y', 'd/m/Y', 'F j, Y', 'Y-m-d H:i:s'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, trim($value));
            if ($date && $date->format($format) === trim($value)) {
                return $date->format('Y-m-d');
            }
        }
        
        // Fallback: let Laravel handle it
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
    
    protected function parseBoolean($value): bool
    {
        if (is_bool($value)) return $value;
        $lower = strtolower(trim((string) $value));
        return in_array($lower, ['1', 'true', 'yes', 'y', 'on']);
    }
    
    protected function parseStatus($value): string
    {
        $status = strtolower(trim($value));
        $valid = ['pending', 'approved', 'rejected', 'available', 'deprecated'];
        return in_array($status, $valid) ? $status : 'available';
    }
    
    protected function getMimeType(string $format): string
    {
        return match ($format) {
            'csv' => 'text/csv',
            'json' => 'application/json',
            'xlsx', 'xls' => 'application/vnd.ms-excel',
            'arff' => 'text/plain',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            'tar.gz' => 'application/gzip',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream',
        };
    }
}