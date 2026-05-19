<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Task, SubjectArea, License, Keyword};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ TASKS - Pakai firstOrCreate agar aman di-run berulang
        $tasks = [
            ['Classification', 'Predict categorical class labels'],
            ['Regression', 'Predict continuous target values'],
            ['Clustering', 'Group similar instances together'],
            ['Other', 'Other machine learning tasks'],
        ];
        
        foreach ($tasks as [$name, $desc]) {
            Task::firstOrCreate(
                ['task_name' => $name],  // ✅ Condition: cek berdasarkan task_name
                ['description' => $desc] // ✅ Values: insert jika belum ada
            );
        }

        // ✅ SUBJECT AREAS
        $areas = ['Biology', 'Health & Medicine', 'Computer Science', 'Social Sciences', 'Physical Sciences'];
        foreach ($areas as $area) {
            SubjectArea::firstOrCreate(['area_name' => $area]);
        }

        // ✅ LICENSES
        $licenses = [
            ['CC0 1.0 Universal', 'https://creativecommons.org/publicdomain/zero/1.0/', 'Public domain dedication'],
            ['CC BY 4.0', 'https://creativecommons.org/licenses/by/4.0/', 'Attribution required'],
            ['Public Domain', null, 'No known copyright restrictions'],
        ];
        
        foreach ($licenses as [$name, $url, $desc]) {
            License::firstOrCreate(
                ['license_name' => $name],
                ['license_url' => $url, 'description' => $desc]
            );
        }

        // ✅ KEYWORDS
        collect(['ecology', 'image', 'text', 'time-series', 'sensor', 'finance'])
            ->each(fn($k) => Keyword::firstOrCreate(['keyword_name' => $k]));

        $this->call([
        // ... seeders lainnya
        UciDatasetSeeder::class,
    ]);
    }
}