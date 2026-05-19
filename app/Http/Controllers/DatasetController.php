<?php

namespace App\Http\Controllers;

use App\Models\{Dataset, Keyword, SubjectArea, Task, File};
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        $query = Dataset::query()->with(['task', 'subjectArea', 'files']);

        // Search
        if ($request->filled('search') || $request->filled('q')) {
            $query->search($request->input('search', $request->q));
        }

        // Filters
        if ($request->filled('task')) $query->withTask($request->task);
        if ($request->filled('area')) $query->withSubjectArea($request->area);
        if ($request->filled('dataType')) $query->withDataType($request->dataType);
        if ($request->filled('instances_min')) {
            $query->withInstancesRange($request->instances_min, $request->instances_max ?? null);
        }
        if ($request->filled('features_min')) {
            $query->withFeaturesRange($request->features_min, $request->features_max ?? null);
        }

        // Sorting
        $sortBy = $request->input('sort', 'recent');
        $sortOrder = $request->input('order', 'desc');
        $allowedSorts = [
            'recent' => 'donated_date', 'popular' => 'view_count', 
            'downloads' => 'download_count', 'name' => 'name',
            'instances' => 'num_instances', 'features' => 'num_features',
        ];
        $query->orderBy($allowedSorts[$sortBy] ?? 'donated_date', $sortOrder);

        $datasets = $query->paginate(20)->withQueryString();

        // ✅ AMBIL DATA UNTUK FILTER (dari tabel yang SUDAH ADA)
        $keywords = Keyword::orderBy('keyword_name')->take(50)->get();
        $subjectAreas = SubjectArea::orderBy('area_name')->take(20)->get();
        $tasks = Task::orderBy('task_name')->get();

        $stats = [
            'min_instances' => Dataset::min('num_instances') ?? 0,
            'max_instances' => Dataset::max('num_instances') ?? 1000000,
            'min_features' => Dataset::min('num_features') ?? 0,
            'max_features' => Dataset::max('num_features') ?? 10000,
        ];

        // ✅ KIRIM SEMUA KE VIEW
        return view('datasets.index', compact('datasets', 'keywords', 'subjectAreas', 'tasks', 'stats'));
    }

    public function show(Dataset $dataset)
    {
        $dataset->load(['task', 'subjectArea', 'license', 'doi', 'creators', 'papers', 'files', 'variables']);
        $dataset->incrementViewCount();
        return view('datasets.show', compact('dataset'));
    }

    public function download(Dataset $dataset, File $file)
    {
        abort_unless($dataset->files->contains('file_id', $file->file_id), 403);
        $dataset->incrementDownloadCount();
        return response()->download(storage_path('app/public/' . $file->file_path), $file->original_filename);
    }
}