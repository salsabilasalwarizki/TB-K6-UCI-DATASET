<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Popular datasets - use direct fields, NOT relationships
        $popularDatasets = Dataset::query()
            ->where('status', 'available')
            ->with(['files' => fn($q) => $q->limit(1)])
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get(['dataset_id', 'slug', 'name', 'display_name', 'description', 
                   'data_type', 'task_type', 'subject_area', 'num_instances', 
                   'num_features', 'view_count', 'thumbnail_url']);
        
        // New datasets
        $newDatasets = Dataset::query()
            ->where('status', 'available')
            ->with(['files' => fn($q) => $q->limit(1)])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get(['dataset_id', 'slug', 'name', 'display_name', 'description',
                   'data_type', 'task_type', 'subject_area', 'created_at', 
                   'thumbnail_url']);
        
        // Stats (cached)
        $stats = Cache::remember('home:stats', 3600, function() {
            return [
                'total' => Dataset::where('status', 'available')->count(),
                'by_data_type' => Dataset::selectRaw('data_type, count(*) as count')
                    ->where('status', 'available')
                    ->groupBy('data_type')
                    ->pluck('count', 'data_type'),
                'by_task_type' => Dataset::selectRaw('task_type, count(*) as count')
                    ->where('status', 'available')
                    ->groupBy('task_type')
                    ->pluck('count', 'task_type'),
                'recent_downloads' => Dataset::where('status', 'available')
                    ->where('download_count', '>', 0)
                    ->orderBy('download_count', 'desc')
                    ->take(3)
                    ->get(['dataset_id', 'slug', 'name', 'download_count']),
            ];
        });
        
        return view('home', compact('popularDatasets', 'newDatasets', 'stats'));
    }
    
    public function about() { return view('about'); }
    public function whoWeAre() { return view('about.who-we-are'); }
    public function citation() { return view('about.citation'); }
    public function contact() { return view('about.contact'); }
}