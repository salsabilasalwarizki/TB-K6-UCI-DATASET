<?php

namespace Database\Seeders;

use App\Models\{Dataset, Creator, License, Doi, Keyword};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UciScrapedDataSeeder extends Seeder
{
    public function run()
    {
        $filePath = database_path('seeders/data/uci_scraped_data.csv');
        
        if (!file_exists($filePath)) {
            $this->command->error("❌ File CSV tidak ditemukan di: {$filePath}");
            return;
        }

        $this->command->info("📂 Membaca file CSV...");
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            $this->command->error("❌ Gagal membuka file CSV.");
            return;
        }

        $headers = fgetcsv($handle);
        $count = 0;
        $skipped = 0;

        DB::transaction(function() use ($handle, $headers, &$count, &$skipped) {
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($headers, $row);
                
                try {
                    $this->importDataset($data);
                    $count++;
                    
                    if ($count % 50 === 0) {
                        $this->command->info("✅ Berhasil import {$count} dataset...");
                    }
                } catch (\Exception $e) {
                    $skipped++;
                    $this->command->warn("⚠️ Baris " . ($count + $skipped) . " dilewati: " . $e->getMessage());
                }
            }
        });

        fclose($handle);
        
        $this->command->info("🎉 Import selesai!");
        $this->command->info("✅ Berhasil: {$count} dataset");
        $this->command->info("⚠️ Dilewati: {$skipped} baris");
    }

    private function importDataset(array $row)
    {
        // 1. Parse nama dataset (wajib)
        $name = trim($row['item_page_title'] ?? '');
        if (empty($name) || $name === 'null') {
            throw new \Exception("Nama dataset kosong.");
        }

        // 2. Parse description
        $description = trim($row['dataset_details'] ?? $row['Additional_Information'] ?? '');
        $description = html_entity_decode(strip_tags($description));

        // 3. Parse donation date
        $donatedDate = null;
        if (!empty($row['Donation_Date']) && $row['Donation_Date'] !== 'null') {
            try {
                $donatedDate = Carbon::parse($row['Donation_Date'])->startOfDay();
            } catch (\Exception $e) {
                $donatedDate = null;
            }
        }

        // 4. Parse numeric fields
        $numInstances = $this->parseNumericValue($row['Total_Records'] ?? '');
        $viewCount    = $this->parseNumericValue($row['views_count'] ?? '0');
        $citationCount= $this->parseNumericValue($row['citation_count'] ?? '0');

        // 5. Parse boolean
        $hasMissing = in_array(strtolower(trim($row['has_missing_values'] ?? '')), ['yes', 'true', '1']);

        // 6. Handle License (gunakan license_name, BUKAN name)
        $licenseId = $this->findOrCreateLicense($row['License'] ?? $row['license'] ?? '');

        // 7. Handle DOI (gunakan resolution_url, BUKAN url)
        $doiId = $this->findOrCreateDoi($row['DOI'] ?? '');

        // 8. Prepare additional_info JSON
        $additionalInfo = json_encode([
            'source_url' => $row['item_page_link'] ?? null,
            'download_size' => $row['download_size'] ?? null,
            'file_structure' => $row['Data_File_Structure'] ?? null,
            'characteristics_raw' => $row['data'] ?? null,
        ]);

        // 9. Create Dataset
        $dataset = Dataset::create([
            'name' => $name,
            'description' => Str::limit($description, 2000),
            'donated_date' => $donatedDate,
            'last_updated' => now(),
            'characteristics' => trim($row['data'] ?? ''),
            'feature_type' => trim($row['data_3'] ?? ''),
            'num_instances' => $numInstances,
            'num_features' => null,
            'has_missing_values' => $hasMissing,
            'additional_info' => $additionalInfo,
            'attribute_info' => null,
            'view_count' => $viewCount,
            'download_count' => 0,
            'citation_count' => $citationCount,
            'task_id' => null,
            'subject_area_id' => null,
            'license_id' => $licenseId,
            'doi_id' => $doiId,
            'status' => 'approved',
            'is_public' => true,
        ]);

        // 10. Sync creators (dengan validasi ketat)
        $this->syncCreators($dataset, $row);

        // 11. Sync keywords dari characteristics
        $this->syncKeywords($dataset, $row);
    }

    /**
     * Find or create license - gunakan license_name (sesuai DB)
     */
    private function findOrCreateLicense(?string $rawText): ?int
    {
        if (empty($rawText) || $rawText === 'null') return null;
        
        $cleanName = trim($rawText);
        if (strpos($cleanName, 'Creative Commons') !== false) {
            $cleanName = 'CC BY 4.0';
        }
        
        // ✅ GUNAKAN license_name, BUKAN name
        $license = \App\Models\License::firstOrCreate(
            ['license_name' => Str::limit($cleanName, 100)],
            ['description' => $cleanName]
        );
        
        return $license->license_id;
    }

    /**
     * Find or create DOI - gunakan resolution_url (sesuai DB)
     */
    private function findOrCreateDoi(?string $rawText): ?int
    {
        if (empty($rawText) || $rawText === 'null') return null;
        
        $doiString = trim(str_replace('DOI ', '', $rawText));
        
        // ✅ GUNAKAN resolution_url, BUKAN url
        $doi = \App\Models\Doi::firstOrCreate(
            ['doi_string' => $doiString],
            ['resolution_url' => "https://doi.org/{$doiString}"]
        );
        
        return $doi->doi_id;
    }

    /**
     * Sync creators dengan validasi ketat untuk hindari data invalid
     */
    private function syncCreators(Dataset $dataset, array $row): void
    {
        $creatorFields = ['creators_1', 'creators_2', 'creators_3', 'creators_4', 'creators_5', 'Author'];
        
        foreach ($creatorFields as $field) {
            if (empty($row[$field]) || $row[$field] === 'null') continue;
            
            $rawName = trim($row[$field]);
            
            // Skip jika terlalu panjang (bukan nama orang)
            if (strlen($rawName) > 100) continue;
            
            // Skip jika berisi pattern file/metadata
            if (preg_match('/(Dataset Files|FileSize|\.csv|\.txt|\.xlsx|\.jpg|\.png|Rows per page|KB|MB|GB|http|www)/i', $rawName)) {
                continue;
            }
            
            // Skip jika hanya angka/special chars
            if (!preg_match('/[a-zA-Z]/', $rawName)) continue;
            
            // Split by comma/newline
            $names = preg_split('/[,|\n]/', $rawName);
            
            foreach ($names as $creatorName) {
                $creatorName = trim($creatorName);
                
                // Final validation
                if (empty($creatorName) || strlen($creatorName) > 100 || $creatorName === 'null') continue;
                
                // Skip obvious non-names
                if (preg_match('/^(Dataset|File|Rows|KB|MB|GB|http|www|\.)/i', $creatorName)) continue;
                
                try {
                    $creator = \App\Models\Creator::firstOrCreate(
                        ['name' => Str::limit($creatorName, 100)],
                        ['email' => null, 'affiliation' => null]
                    );
                    
                    $dataset->creators()->syncWithoutDetaching([
                        $creator->creator_id => ['contribution_role' => 'author']
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("  ⚠️ Could not add creator '{$creatorName}': " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Sync keywords dari characteristics column
     */
/**
 * Sync keywords dari characteristics column
 */
private function syncKeywords(Dataset $dataset, array $row): void
{
    $characteristics = $row['data'] ?? '';
    if (empty($characteristics) || $characteristics === 'null') return;
    
    $keywords = array_filter(array_map('trim', explode(',', $characteristics)));
    
    foreach ($keywords as $kw) {
        if (empty($kw) || strlen($kw) < 2) continue;
        
        // Skip keywords yang terlalu panjang atau berisi pattern invalid
        if (strlen($kw) > 50 || preg_match('/(Dataset|File|\.csv|\.txt)/i', $kw)) continue;
        
        // ✅ FIX: HAPUS 'description' dari array kedua firstOrCreate
        // Karena tabel keywords tidak punya kolom description
        $keyword = \App\Models\Keyword::firstOrCreate(
            ['keyword_name' => Str::lower($kw)],
            ['description' => null]
        );
        
        $dataset->keywords()->syncWithoutDetaching($keyword->keyword_id);
    }
}
    /**
     * Parse numeric values like "1.45K", "3652 views", "0 citations"
     */
    private function parseNumericValue($value): ?int
    {
        if (empty($value) || $value === 'null' || $value === '-' || $value === '0') {
            return 0;
        }
        
        $value = strtoupper(trim($value));
        // Hapus teks tambahan seperti " INSTANCES", " VIEWS", " citations"
        $value = preg_replace('/[^0-9.KMB]/', '', $value);
        
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
        
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT) ?: 0;
    }
}