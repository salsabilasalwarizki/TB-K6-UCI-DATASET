<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use Maatwebsite\Excel\Facades\Excel;

class DatasetSeeder extends Seeder
{
    public function run()
    {
        Excel::import(new DatasetsImport, storage_path('app/datasets/archive-ics-uci-edu-2026-05-09-2.xlsx'));
    }
}