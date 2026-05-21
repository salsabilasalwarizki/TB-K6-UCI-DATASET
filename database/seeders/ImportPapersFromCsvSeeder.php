<?php

namespace Database\Seeders;

use App\Models\Dataset;
use App\Models\Paper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportPapersFromCsvSeeder extends Seeder
{
    protected string $csvFolderPath;
    
    public function __construct()
    {
        // Path ke folder CSV papers
        $this->csvFolderPath = base_path('database/data/papers');
    }
    
    public function run(): void
    {
        if (!is_dir($this->csvFolderPath)) {
            $this->command->error("❌ Folder tidak ditemukan: {$this->csvFolderPath}");
            $this->command->info("💡 Buat folder: mkdir -p {$this->csvFolderPath}");
            return;
        }
        
        $this->command->info("📂 Scanning folder: {$this->csvFolderPath}");
        
        $files = glob($this->csvFolderPath . '/*.csv');
        $total = count($files);
        
        if ($total === 0) {
            $this->command->error("❌ Tidak ada file CSV ditemukan di {$this->csvFolderPath}");
            return;
        }
        
        $this->command->info("📊 Ditemukan {$total} file CSV");
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($files as $index => $file) {
                try {
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $this->command->info("📄 [" . ($index + 1) . "/{$total}] Processing: {$filename}");
                    
                    $result = $this->importPaperFromCsv($file, $filename);
                    
                    if ($result === 'created') {
                        $imported++;
                    } elseif ($result === 'skipped') {
                        $skipped++;
                    }
                    
                } catch (\Exception $e) {
                    $errors++;
                    $this->command->error("  ❌ Error: " . $e->getMessage());
                }
            }
            
            DB::commit();
            
            $this->command->newLine();
            $this->command->info("✅ Import selesai!");
            $this->command->table(
                ['Metric', 'Value'],
                [
                    ['Total Files', $total],
                    ['Papers Imported', $imported],
                    ['Skipped (Already Exist)', $skipped],
                    ['Errors', $errors],
                    ['Success Rate', round(($imported / max($total, 1)) * 100, 2) . '%'],
                ]
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Import gagal: " . $e->getMessage());
            throw $e;
        }
    }
    
    protected function importPaperFromCsv(string $filePath, string $datasetName): string
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        
        // Cek apakah ada header di baris pertama
        $csv->setHeaderOffset(0);
        $records = iterator_to_array((new Statement())->process($csv));
        
        // Jika gagal baca header atau array kosong, coba baca sebagai raw rows
        if (empty($records)) {
            $csv->setHeaderOffset(-1); // Hapus header mapping
            $rawRows = iterator_to_array((new Statement())->process($csv));
            
            if (!empty($rawRows)) {
                // Asumsikan baris pertama adalah data, mapping manual
                $row = $rawRows[0] ?? [];
                $data = [];
                // Mapping manual berdasarkan urutan kolom umum: title, authors, venue, year, doi, url, abstract
                $headers = ['title', 'authors', 'venue', 'year', 'doi', 'url', 'abstract'];
                foreach ($headers as $i => $header) {
                    $data[$header] = $row[$i] ?? null;
                }
            } else {
                throw new \Exception("File CSV kosong atau tidak valid");
            }
        } else {
            // Gunakan header otomatis
            $row = $records[0];
            $data = array_change_key_case($row, CASE_LOWER);
        }
        
        // Extract data dengan fallback
        $title = $this->clean($data['title'] ?? $data['paper_title'] ?? $data['name'] ?? null);
        $authors = $this->clean($data['authors'] ?? $data['author'] ?? null);
        $venue = $this->clean($data['venue'] ?? $data['journal'] ?? null);
        $year = $this->parseYear($data['year'] ?? $data['publication_year'] ?? null);
        $doi = $this->clean($data['doi'] ?? null);
        $url = $this->clean($data['url'] ?? null);
        $abstract = $this->clean($data['abstract'] ?? $data['description'] ?? null);
        
        // Fallback title dari nama file jika kosong
        if (empty($title)) {
            $title = ucwords(str_replace(['-', '_', '+'], ' ', $datasetName));
        }
        
        if (empty($title)) {
            throw new \Exception("Paper title is required");
        }
        
        // Cari dataset
        $dataset = $this->findDataset($datasetName);
        if (!$dataset) {
            $this->command->warn("  ⚠️ Dataset tidak ditemukan: {$datasetName}. Paper dibuat tanpa link.");
        }
        
        // Create/Find Paper
        $paper = Paper::firstOrCreate(
            ['title' => $title, 'doi' => $doi],
            [
                'authors' => $authors,
                'venue' => $venue,
                'publication_year' => $year,
                'url' => $url,
                'abstract' => $abstract,
                'bibtex' => $this->generateBibtex(['title' => $title, 'authors' => $authors, 'year' => $year, 'venue' => $venue, 'doi' => $doi, 'url' => $url]),
            ]
        );
        
        // Link ke dataset
        if ($dataset) {
            $linked = DB::table('dataset_papers')->updateOrInsert(
                ['dataset_id' => $dataset->dataset_id, 'paper_id' => $paper->paper_id],
                ['citation_type' => 'citing', 'is_primary' => false, 'updated_at' => now()]
            );
            if (!$linked) return 'skipped';
        }
        
        return 'created';
    }
    
    protected function findDataset(string $name): ?Dataset
    {
        $slug = Str::slug($name);
        return Dataset::where('slug', $slug)->first()
            ?? Dataset::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%'])->first();
    }
    
    protected function clean(?string $val): ?string
    {
        if (empty($val) || in_array(strtolower(trim($val)), ['null', 'n/a', '-', ''])) return null;
        return trim(preg_replace('/\s+/', ' ', html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
    
    protected function parseYear($val): ?int
    {
        if (empty($val)) return null;
        if (preg_match('/\b(19|20)\d{2}\b/', (string)$val, $m)) return (int)$m[0];
        return is_numeric($val) && strlen($val) === 4 ? (int)$val : null;
    }
    
    protected function generateBibtex(array $d): ?string
    {
        if (empty($d['title'])) return null;
        $key = substr(Str::slug($d['title'], '-'), 0, 50);
        return <<<BIBTEX
@article{$key,
  title = {{$d['title']}},
  author = {$d['authors']},
  year = {$d['year']},
  journal = {$d['venue']},
  doi = {$d['doi']},
  url = {$d['url']}
}
BIBTEX;
    }
}