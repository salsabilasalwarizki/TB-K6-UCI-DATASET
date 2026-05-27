<?php

namespace App\Http\Controllers;

use App\Models\{Dataset, Keyword, File, Variable, DatasetDescription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatasetController extends Controller
{
    /**
     * Display listing of datasets with filters
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $keywords = $request->get('keywords', []);
        $dataType = $request->get('data_type');
        $taskType = $request->get('task_type');
        $subjectAreas = $request->get('subject_area', []);
        $domains = $request->get('domain', []);
        $variableTypes = $request->get('variable_types', []);
        $hasMissing = $request->boolean('has_missing');
        $statuses = $request->get('status', ['available']);
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('subject_area', 'like', "%{$request->search}%")
                  ->orWhere('domain', 'like', "%{$request->search}%");
            });
        }
        
        // Range filters
        $instancesMin = $request->get('instances_min');
        $instancesMax = $request->get('instances_max') ?? $request->get('instances_range');
        $featuresMin = $request->get('features_min');
        $featuresMax = $request->get('features_max') ?? $request->get('features_range');
        
        // Sorting
        $sort = $request->get('sort', 'view_count');
        $order = $request->get('order', 'desc');
        
        // Build query with scopes
        $query = Dataset::query()
            ->search($search)
            ->filterByDataType($dataType)
            ->filterByTaskType($taskType)
            ->filterBySubjectArea($subjectAreas)
            ->filterByDomain($domains)
            ->filterByKeywords($keywords)
            ->filterByInstances($instancesMin, $instancesMax)
            ->filterByFeatures($featuresMin, $featuresMax)
            ->filterByVariableTypes($variableTypes)
            ->filterByHasMissing($hasMissing)
            ->filterByStatus($statuses)
            ->sortBy($sort, $order)
            ->with([
                'files' => fn($q) => $q->limit(1),
                'keywords' => fn($q) => $q->limit(5),
                'variables' => fn($q) => $q->limit(5),
                'user:id,name',
            ]);
        
        $datasets = $query->paginate(12)->withQueryString();
        
        // Get filter options for sidebar
        $filterData = $this->getFilterOptions();
        
        return view('datasets.index', array_merge([
            'datasets' => $datasets,
            'sort' => $sort,
            'order' => $order,
        ], $filterData));
    }
    
    /**
     * Display specified dataset
     */
    public function show(Request $request, Dataset $dataset)
    {
        // Track view (only once per session)
        if (!$request->session()->has("viewed_dataset_{$dataset->dataset_id}")) {
            $dataset->incrementView();
            $request->session()->put("viewed_dataset_{$dataset->dataset_id}", true);
        }
        
// ❌ SALAH - Jangan pakai orderBy('pivot.column') di closure
$dataset->load([
    'descriptionDetails',
    'files',
    'variables.categories',
    'keywords',
    
    // HAPUS 
    // 'papers' => fn($q) => $q->orderBy('pivot.is_primary', 'desc'),
    
   //orderByPivot:
    'papers' => fn($q) => $q->orderByPivot('is_primary', 'desc'),
    
    'contributors' => fn($q) => $q->orderByPivot('display_order'),
    'reviews.user:id,name',
    'license',
    'doi',
    'user:id,name',
]);
        
        return view('datasets.show', compact('dataset'));
    }
    
    /**
     * Download dataset file
     */
    // app/Http/Controllers/DatasetController.php

public function download($datasetId, $fileId)
{
    // Cari file di database
    $datasetFile = \DB::table('dataset_files')
        ->join('files', 'dataset_files.file_id', '=', 'files.file_id')
        ->where('dataset_files.dataset_id', $datasetId)
        ->where('files.file_id', $fileId)
        ->first();

    if (!$datasetFile) {
        abort(404, 'File not found');
    }

    // Path file di storage
    $filePath = storage_path('app/public/' . $datasetFile->file_path);

    // Cek apakah file ada
    if (!file_exists($filePath)) {
        abort(404, 'File tidak ditemukan di server');
    }

    // Catat download (opsional)
    \DB::table('downloads')->insert([
        'dataset_id' => $datasetId,
        'file_id' => $fileId,
        'user_id' => auth()->id(),
        'downloaded_at' => now(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);

    // Return file sebagai download
    return response()->download($filePath, $datasetFile->original_filename);
}
    
    /**
     * Track dataset view (AJAX endpoint)
     */

    public function trackView(Request $request, Dataset $dataset)
    {
        // Only track once per session
        $sessionKey = "viewed_dataset_{$dataset->dataset_id}";
        
        if (!$request->session()->has($sessionKey)) {
            $dataset->increment('view_count');
            $request->session()->put($sessionKey, true);
       ;
        }
        
        return response()->json(['success' => true, 'views' => $dataset->view_count]);
    }
    
    /**
     * Save dataset to user's collection (requires auth)
     */
    public function save(Request $request, Dataset $dataset)
    {
        $request->validate([
            'action' => 'required|in:add,remove'
        ]);
        
        $user = $request->user();
        $action = $request->input('action');
        
        // Requires user_collections pivot table
        if ($action === 'add') {
            $user->savedDatasets()->syncWithoutDetaching([$dataset->dataset_id]);
            $message = 'Dataset saved to your collection';
        } else {
            $user->savedDatasets()->detach($dataset->dataset_id);
            $message = 'Dataset removed from your collection';
        }
        
        return response()->json([
            'success' => true, 
            'message' => $message,
            'action' => $action
        ]);
    }
    
    /**
     * Quick preview data for AJAX loading (optional)
     */
    public function preview(Dataset $dataset)
    {
        return response()->json([
            'id' => $dataset->dataset_id,
            'name' => $dataset->name,
            'slug' => $dataset->slug,
            'description' => Str::limit($dataset->description, 200),
            'data_type' => $dataset->data_type,
            'task_type' => $dataset->task_type,
            'num_instances' => $dataset->num_instances,
            'num_features' => $dataset->num_features,
            'view_count' => $dataset->view_count,
            'download_count' => $dataset->download_count,
            'thumbnail_url' => $dataset->thumbnail_url,
        ]);
    }
    /**
     * Get filter options for sidebar (cached for performance)
     */
    private function getFilterOptions(): array
    {
        return Cache::remember('dataset_filters', 3600, function() {
            return [
                'keywords' => Keyword::withCount('datasets')
                    ->orderBy('datasets_count', 'desc')
                    ->take(50)
                    ->get(),
                    
                'subjectAreas' => DB::table('datasets')
                    ->select('subject_area', DB::raw('count(*) as datasets_count'))
                    ->whereNotNull('subject_area')
                    ->where('status', 'available')
                    ->groupBy('subject_area')
                    ->orderByDesc('datasets_count')
                    ->take(30)
                    ->get()
                    ->map(fn($item) => (object)[
                        'area_id' => crc32($item->subject_area),
                        'area_name' => $item->subject_area,
                        'datasets_count' => $item->datasets_count
                    ]),
                    
                'stats' => [
                    'min_instances' => 0,
                    'max_instances' => Dataset::where('status', 'available')->max('num_instances') ?? 100000,
                    'min_features' => 0,
                    'max_features' => Dataset::where('status', 'available')->max('num_features') ?? 1000,
                    'data_type_counts' => Dataset::selectRaw('data_type, count(*) as count')
                        ->where('status', 'available')
                        ->groupBy('data_type')
                        ->pluck('count', 'data_type')
                        ->toArray(),
                    'task_type_counts' => Dataset::selectRaw('task_type, count(*) as count')
                        ->where('status', 'available')
                        ->groupBy('task_type')
                        ->pluck('count', 'task_type')
                        ->toArray(),
                ],
                
                'domains' => Dataset::whereNotNull('domain')
                    ->where('status', 'available')
                    ->distinct()
                    ->pluck('domain')
                    ->toArray(),
            ];
        });
    }
}