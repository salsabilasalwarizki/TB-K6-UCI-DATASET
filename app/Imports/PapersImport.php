<?php

namespace App\Imports;

use App\Models\Paper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PapersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Paper([
            'title' => $row['title'],
            'authors' => $row['authors'] ?? null,
            'venue' => $row['venue'] ?? null,
            'publication_year' => $row['year'] ?? null,
            'doi' => $row['doi'] ?? null,
            'abstract' => $row['abstract'] ?? null,
        ]);
    }
}