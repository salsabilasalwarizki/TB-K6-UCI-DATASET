<?php

namespace Database\Seeders;

use App\Models\{Dataset, Task, SubjectArea, License, Creator, Keyword, Doi};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UciOriginalDataSeeder extends Seeder
{
    public function run()
    {
        $tablePath = database_path('seeders/data/uci_table.csv');
        $databasePath = database_path('seeders/data/uci_database.csv');
        
        if (!file_exists($tablePath) || !file_exists($databasePath)) {
            $this->command->error("❌ File CSV tidak ditemukan!");
            return;
        }

        $this->command->info("📂 Membaca file CSV...");
        
        // Baca UCI database.csv untuk mapping abstract
        $dbData = $this->readCSV($databasePath);
        $dbIndexed = collect($dbData)->keyBy('Name');
        
        // Baca UCI table.csv
        $tableData = $this->readCSV($tablePath);
        
        $count = 0;
        $skipped = 0;

        // Get or create default license
        $defaultLicense = License::firstOrCreate(
            ['license_name' => 'CC BY 4.0'],
            ['description' => 'Creative Commons Attribution 4.0 International']
        );

        DB::transaction(function() use ($tableData, $dbIndexed, $defaultLicense, &$count, &$skipped) {
            foreach ($tableData as $index => $row) {
                try {
                    $this->importDataset($row, $dbIndexed, $defaultLicense);
                    $count++;
                    
                    if ($count % 50 === 0) {
                        $this->command->info("✅ Berhasil import {$count} dataset...");
                    }
                } catch (\Exception $e) {
                    $skipped++;
                    $name = $row['Name'] ?? 'Unknown';
                    $this->command->warn("⚠️ Baris " . ($index + 1) . " ({$name}) dilewati: " . $e->getMessage());
                }
            }
        });

        $this->command->info("🎉 Import selesai!");
        $this->command->info("✅ Berhasil: {$count} dataset");
        $this->command->info("⚠️ Dilewati: {$skipped} baris");
    }

    private function importDataset(array $row, $dbIndexed, $defaultLicense)
    {
        // 1. Parse nama dataset
        $name = trim($row['Name'] ?? '');
        if (empty($name)) {
            throw new \Exception("Nama dataset kosong.");
        }

        // 2. Ambil data tambahan dari database.csv
        $dbRow = $dbIndexed->get($name, []);
        $description = trim($dbRow['Abstract'] ?? $row['Name']);
        
        // 3. Parse tahun ke tanggal
        $donatedDate = null;
        if (!empty($row['Year']) && is_numeric($row['Year'])) {
            $donatedDate = Carbon::createFromFormat('Y', (int)$row['Year'])->startOfYear();
        }

        // 4. Parse numeric fields
        $numInstances = $this->parseNumericValue($row['Number of Instances'] ?? null);
        $numFeatures = $this->parseNumericValue($row['Number of Attributes'] ?? null);

        // 5. Handle Task
        $taskId = null;
        if (!empty($row['Default Task']) && $row['Default Task'] !== 'Other/Unknown') {
            $task = Task::firstOrCreate(['task_name' => trim($row['Default Task'])]);
            $taskId = $task->task_id;
        }

        // 6. Handle Subject Area (default ke Computer Science)
        $subjectArea = SubjectArea::firstOrCreate(['area_name' => 'Computer Science']);
        
        // 7. Handle DOI jika ada di database.csv
        $doiId = null;
        if (!empty($dbRow['Identifier string']) && Str::startsWith($dbRow['Identifier string'], '10.')) {
            $doi = Doi::firstOrCreate(
                ['doi_string' => $dbRow['Identifier string']],
                ['resolution_url' => "https://doi.org/{$dbRow['Identifier string']}"]
            );
            $doiId = $doi->doi_id;
        }

        // 8. Prepare additional_info JSON
        $additionalInfo = json_encode([
            'identifier' => $dbRow['Identifier string'] ?? null,
            'source_url' => $dbRow['Datapage URL'] ?? null,
        ]);

        // 9. Create Dataset
        $dataset = Dataset::create([
            'name' => $name,
            'description' => Str::limit($description, 2000),
            'donated_date' => $donatedDate,
            'last_updated' => now(),
            'characteristics' => trim($row['Data Types'] ?? ''),
            'feature_type' => trim($row['Attribute Types'] ?? ''),
            'num_instances' => $numInstances,
            'num_features' => $numFeatures,
            'has_missing_values' => false,
            'additional_info' => $additionalInfo,
            'attribute_info' => null,
            'view_count' => 0,
            'download_count' => 0,
            'citation_count' => 0,
            'task_id' => $taskId,
            'subject_area_id' => $subjectArea->area_id,
            'license_id' => $defaultLicense->license_id,
            'doi_id' => $doiId,
            'status' => 'approved',
            'is_public' => true,
        ]);

        // 10. Add default creator (UCI)
        $creator = Creator::firstOrCreate(
            ['name' => 'UCI Machine Learning Repository'],
            ['email' => 'archive@ics.uci.edu']
        );
        $dataset->creators()->syncWithoutDetaching([
            $creator->creator_id => ['contribution_role' => 'Donor']
        ]);

        // 11. Sync keywords dari characteristics
        $this->syncKeywords($dataset, $row);
    }

    private function syncKeywords(Dataset $dataset, array $row)
    {
        $characteristics = $row['Data Types'] ?? '';
        if (empty($characteristics)) return;
        
        $keywords = array_filter(array_map('trim', explode(',', $characteristics)));
        
        foreach ($keywords as $kw) {
            if (empty($kw) || strlen($kw) < 2) continue;
            if (strlen($kw) > 50) continue;
            
            $keyword = Keyword::firstOrCreate(
                ['keyword_name' => Str::lower($kw)],
                [] // ✅ Kosongkan karena tabel keywords tidak punya kolom description
            );
            
            $dataset->keywords()->syncWithoutDetaching($keyword->keyword_id);
        }
    }

    private function parseNumericValue($value): ?int
    {
        if (empty($value) || $value === '-' || $value === 'null') return null;
        
        $value = strtoupper(trim($value));
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
        
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT) ?: null;
    }

    private function readCSV($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($headers) === count($row)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}