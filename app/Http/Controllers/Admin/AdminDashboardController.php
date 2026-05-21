<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use App\Models\Paper;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Tampilan Utama Admin Dashboard
     */
    public function index()
    {
        // 1. STATISTIK UTAMA (Stats Cards)
        $stats = [
            'total_datasets' => Dataset::count(),
            'pending_datasets' => Dataset::where('status', 'pending')->count(),
            'approved_datasets' => Dataset::where('status', 'approved')->count(),
            'rejected_datasets' => Dataset::where('status', 'rejected')->count(),
            'total_users' => User::count(),
        ];

        // 2. DAFTAR DATASET PENDING (Tabel di Kiri)
        // Mengambil 10 dataset terbaru yang statusnya pending
        $pendingDatasets = Dataset::with(['contributors', 'files']) // Load relasi
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 3. AKTIVITAS TERAKHIR (Timeline di Kanan)
        // Mengambil 5 dataset terbaru (apapun statusnya) untuk feed aktivitas
        $recentActivity = Dataset::with(['user', 'contributors'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. DATA CHART (Grafik Bulanan)
        // Mengambil jumlah dataset per bulan (6 bulan terakhir)
        $monthlySubmissions = Dataset::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse()
            ->values(); // Reverse agar grafik berjalan dari kiri ke kanan

        // Return View
        return view('admin.dashboard', compact(
            'stats', 
            'pendingDatasets', 
            'recentActivity', 
            'monthlySubmissions'
        ));
    }

    /**
     * Metode tambahan untuk Update Statistik Real-time (Opsional)
     */
    public function getStats()
    {
        $stats = [
            'total_datasets' => Dataset::count(),
            'pending_datasets' => Dataset::where('status', 'pending')->count(),
            'approved_datasets' => Dataset::where('status', 'approved')->count(),
            'rejected_datasets' => Dataset::where('status', 'rejected')->count(),
            'total_users' => User::count(),
        ];

        return response()->json($stats);
    }
}