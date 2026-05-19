<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Dataset, User, Creator, File, Variable};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Overall statistics
        $stats = [
            'datasets' => [
                'total' => Dataset::count(),
                'approved' => Dataset::where('status', 'approved')->count(),
                'pending' => Dataset::where('status', 'pending')->count(),
                'rejected' => Dataset::where('status', 'rejected')->count(),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'admins' => User::whereIn('role', ['admin', 'superadmin'])->count(),
            ],
            'content' => [
                'total_files' => File::count(),
                'total_variables' => Variable::count(),
                'total_creators' => Creator::count(),
            ],
        ];

        // Datasets by subject area
        $datasetsByArea = DB::table('datasets')
            ->join('subject_areas', 'datasets.subject_area_id', '=', 'subject_areas.area_id')
            ->select('subject_areas.area_name', DB::raw('COUNT(*) as count'))
            ->groupBy('subject_areas.area_id', 'subject_areas.area_name')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Datasets by task
        $datasetsByTask = DB::table('datasets')
            ->join('tasks', 'datasets.task_id', '=', 'tasks.task_id')
            ->select('tasks.task_name', DB::raw('COUNT(*) as count'))
            ->groupBy('tasks.task_id', 'tasks.task_name')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly activity (last 12 months)
        $monthlyActivity = Dataset::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->reverse()
            ->values();

        // Top contributors
        $topContributors = DB::table('creators')
            ->join('dataset_creator', 'creators.creator_id', '=', 'dataset_creator.creator_id')
            ->select('creators.name', 'creators.affiliation', DB::raw('COUNT(*) as dataset_count'))
            ->groupBy('creators.creator_id', 'creators.name', 'creators.affiliation')
            ->orderBy('dataset_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.statistics', compact(
            'stats',
            'datasetsByArea',
            'datasetsByTask',
            'monthlyActivity',
            'topContributors'
        ));
    }
}