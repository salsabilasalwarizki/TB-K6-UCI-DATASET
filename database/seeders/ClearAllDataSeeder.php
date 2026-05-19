<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearAllDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate all tables
        DB::table('dataset_keyword')->truncate();
        DB::table('dataset_paper')->truncate();
        DB::table('dataset_creator')->truncate();
        DB::table('variables')->truncate();
        DB::table('files')->truncate();
        DB::table('keywords')->truncate();
        DB::table('papers')->truncate();
        DB::table('creators')->truncate();
        DB::table('datasets')->truncate();
        DB::table('tasks')->truncate();
        DB::table('subject_areas')->truncate();
        DB::table('licenses')->truncate();
        DB::table('dois')->truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "✅ All data has been cleared!\n";
    }
}