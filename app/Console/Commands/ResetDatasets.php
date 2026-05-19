<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatasets extends Command
{
    protected $signature = 'datasets:reset 
                            {--force : Run without confirmation}
                            {--seed : Run seeder after reset}';
    
    protected $description = 'Truncate all dataset-related tables';

    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️ This will DELETE all dataset data. Continue?')) {
                return Command::FAILURE;
            }
        }
        
        $this->info('🗑️ Truncating dataset tables...');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'dataset_keywords', 'dataset_contributors', 'dataset_papers',
            'dataset_files', 'dataset_images', 'dataset_descriptions',
            'variable_categories', 'variables', 'reviews', 'downloads',
            'papers', 'files', 'images', 'keywords', 'people', 'dois', 'licenses',
            'datasets',
        ];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
                $this->line("  ✓ Truncated: {$table}");
            } catch (\Exception $e) {
                $this->warn("  ✗ Failed: {$table} - {$e->getMessage()}");
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Reset auto-increment
        DB::statement('ALTER TABLE datasets AUTO_INCREMENT = 1;');
        
        $this->info('✅ Tables truncated successfully');
        
        // Optional: run seeder
        if ($this->option('seed')) {
            $this->info('🌱 Running seeder...');
            $this->call('db:seed', [
                '--class' => 'UciOriginalDataSeeder',
                '--force' => true,
            ]);
        }
        
        return Command::SUCCESS;
    }
}