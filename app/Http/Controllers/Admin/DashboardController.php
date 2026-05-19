<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Dataset, User, Creator, File};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_datasets' => Dataset::count(),
            'pending_datasets' => Dataset::where('status', 'pending')->count(),
            'approved_datasets' => Dataset::where('status', 'approved')->count(),
            'rejected_datasets' => Dataset::where('status', 'rejected')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::whereIn('role', ['admin', 'superadmin'])->count(),
        ];

        // Recent datasets pending review
        $pendingDatasets = Dataset::with(['creators', 'subjectArea', 'task'])
            ->where('status', 'pending')
            ->orderBy('donated_date', 'desc')
            ->take(10)
            ->get();

        // Recent activity
        $recentActivity = Dataset::with('contributors')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

        // Monthly submissions chart data
        $monthlySubmissions = Dataset::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse()
            ->values();

        return view('admin.dashboard', compact('stats', 'pendingDatasets', 'recentActivity', 'monthlySubmissions'));
    }
}