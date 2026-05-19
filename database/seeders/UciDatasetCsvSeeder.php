<?php

namespace Database\Seeders;

use App\Models\{Dataset, Creator, Keyword, License, Doi, SubjectArea, Task};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UciDatasetCsvSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/import/uci_datasets.csv');
        
        if (!file_exists($filePath)) {
            $this->command->error("File CSV tidak ditemukan di: {$filePath}");
            return;
        }

        $this->command->info("📂 Membaca file CSV...");
        $handle = fopen($filePath, 'r');
        
        // Baca header
        $headers = fgetcsv($handle);
        
        $count = 0;
        $failed = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            try {
                // Gabungkan headers dengan row data
                $data = array_combine($headers, $row);
                
                // Parse data dari CSV
                $name = trim($data['item_page_title'] ?? $data['title'] ?? '');
                if (empty($name)) continue;
                
                // Parse description
                $description = trim($data['dataset_details'] ?? $data['Additional_Information'] ?? '');
                
                // Parse donation date
                $donatedDate = null;
                if (!empty($data['Donation_Date'])) {
                    $donatedDate = Carbon::parse($data['Donation_Date'])->startOfDay();
                }
                
                // Parse numeric fields
                $numInstances = $this->parseNumber($data['Total_Records'] ?? null);
                
                // Parse boolean
                $hasMissing = in_array(strtolower($data['has_missing_values'] ?? ''), ['yes', 'true', '1']);
                
                // Create or find related models
                $licenseId = $this->findOrCreateLicense($data['License'] ?? $data['license'] ?? null);
                $doiId = $this->findOrCreateDoi($data['DOI'] ?? null);
                
                // Prepare additional info
                $additionalInfo = [
                    'source_url' => $data['item_page_link'] ?? null,
                    'download_size' => $data['download_size'] ?? null,
                    'data_file_structure' => $data['Data_File_Structure'] ?? null,
                    'file_format_description' => $data['File_Format_Description'] ?? null,
                ];
                
                // Create dataset
                $dataset = Dataset::create([
                    'name' => $name,
                    'description' => $description,
                    'donated_date' => $donatedDate,
                    'last_updated' => now(),
                    'characteristics' => $data['data'] ?? null,
                    'feature_type' => $data['data_3'] ?? null,
                    'num_instances' => $numInstances,
                    'num_features' => null, // Tidak ada di CSV
                    'has_missing_values' => $hasMissing,
                    'additional_info' => $additionalInfo,
                    'attribute_info' => null,
                    'view_count' => $this->parseNumber(str_replace(' views', '', $data['views_count'] ?? '0')),
                    'download_count' => 0,
                    'citation_count' => $this->parseNumber(str_replace(' citations', '', $data['citation_count'] ?? '0')),
                    'task_id' => null, // Perlu mapping manual
                    'subject_area_id' => null, // Perlu mapping manual
                    'license_id' => $licenseId,
                    'doi_id' => $doiId,
                    'status' => 'approved',
                    'is_public' => true,
                ]);
                
                // Sync creators
                $this->syncCreators($dataset, $data);
                
                // Sync keywords
                $this->syncKeywords($dataset, $data);
                
                $count++;
                
                if ($count % 50 === 0) {
                    $this->command->info("✓ Imported {$count} datasets...");
                }
                
            } catch (\Exception $e) {
                $failed++;
                $this->command->error("✗ Failed: " . ($data['item_page_title'] ?? 'Unknown') . " - " . $e->getMessage());
            }
        }
        
        fclose($handle);
        
        $this->command->info("✅ Import completed!");
        $this->command->info("✓ Success: {$count} datasets");
        $this->command->info("✗ Failed: {$failed} datasets");
    }
    
    private function parseNumber($value)
    {
        if (empty($value) || $value === 'null' || $value === '-' || $value === '0 Instances') {
            return null;
        }
        
        // Remove text like " Instances", "K", "M"
        $value = strtoupper(trim($value));
        $value = str_replace([' INSTANCES', ' FEATURES', ',', ' '], '', $value);
        
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
        
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT) ?: null;
    }
    
    private function findOrCreateLicense(?string $name): ?int
    {
        if (empty($name) || $name === 'null') return null;
        
        $cleanName = trim($name);
        
        $license = License::firstOrCreate(
            ['name' => Str::limit($cleanName, 100)],
            ['description' => $cleanName]
        );
        
        return $license->license_id;
    }
    
    private function findOrCreateDoi(?string $doi): ?int
    {
        if (empty($doi) || $doi === 'null') return null;
        
        $doiString = trim(str_replace('DOI ', '', $doi));
        
        $doiModel = Doi::firstOrCreate(
            ['doi_string' => $doiString],
            ['url' => "https://doi.org/{$doiString}"]
        );
        
        return $doiModel->doi_id;
    }
    
    private function syncCreators(Dataset $dataset, array $data): void
    {
        $creatorFields = ['creators_1', 'creators_2', 'creators_3', 'creators_4', 'creators_5', 'Author'];
        
        foreach ($creatorFields as $field) {
            if (!empty($data[$field]) && $data[$field] !== 'null') {
                $creatorName = trim($data[$field]);
                
                $creator = Creator::firstOrCreate(
                    ['name' => $creatorName],
                    [
                        'first_name' => explode(' ', $creatorName)[0] ?? '',
                        'last_name' => implode(' ', array_slice(explode(' ', $creatorName), 1)) ?? '',
                        'email' => null,
                        'affiliation' => null,
                    ]
                );
                
                $dataset->creators()->syncWithoutDetaching([
                    $creator->creator_id => ['contribution_role' => 'author']
                ]);
            }
        }
    }
    
    private function syncKeywords(Dataset $dataset, array $data): void
    {
        // Extract keywords from characteristics or other fields
        $characteristics = $data['data'] ?? '';
        if (!empty($characteristics) && $characteristics !== 'null') {
            $keywords = array_filter(array_map('trim', explode(',', $characteristics)));
            
            foreach ($keywords as $kw) {
                if (empty($kw) || strlen($kw) < 2) continue;
                
                $keyword = Keyword::firstOrCreate(
                    ['keyword_name' => Str::lower($kw)],
                    ['description' => null]
                );
                
                $dataset->keywords()->syncWithoutDetaching($keyword->keyword_id);
            }
        }
    }
}