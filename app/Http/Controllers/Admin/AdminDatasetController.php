<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDatasetController extends Controller
{
    public function index(Request $request)
{
    $query = Dataset::with(['user', 'keywords', 'files']);
    
    // Search
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('subject_area', 'like', "%{$request->search}%")
              ->orWhere('domain', 'like', "%{$request->search}%");
        });
    }
    
    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Filter date range
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }
    
    // Sort
    $sort = $request->get('sort', 'created_at');
    $order = $request->get('order', 'desc');
    $query->orderBy($sort, $order);
    
    $datasets = $query->paginate(15)->withQueryString();
    
    // Statistics
    $stats = [
        'total' => Dataset::count(),
        'pending' => Dataset::where('status', 'pending')->count(),
        'approved' => Dataset::where('status', 'approved')->count(),
        'rejected' => Dataset::where('status', 'rejected')->count(),
        'available' => Dataset::where('status', 'available')->count(),
    ];
    
    // ✅ CHART DATA - Monthly submissions
    $monthlyData = Dataset::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->limit(12)
        ->get();
    
    // ✅ CHART DATA - Status distribution
    $statusData = Dataset::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get();
    
    // ✅ CHART DATA - Top contributors
    $topContributors = User::withCount('datasets')
        ->orderBy('datasets_count', 'desc')
        ->limit(5)
        ->get();
    
    return view('admin.datasets.index', compact(
        'datasets', 
        'stats', 
        'monthlyData',      // ✅ Added
        'statusData',       // ✅ Added
        'topContributors'   // ✅ Added
    ));
}
    public function edit(Dataset $dataset)
    {
        return view('admin.datasets.edit', compact('dataset'));
    }
    
    public function update(Request $request, Dataset $dataset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_area' => 'nullable|string|max:100',
            'data_type' => 'nullable|string|max:50',
            'task_type' => 'nullable|string|max:50',
            'num_instances' => 'nullable|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
            'status' => 'required|in:pending,approved,rejected,available,deprecated',
        ]);
        
        $dataset->update($validated);
        
        return redirect()->route('admin.datasets.index')
            ->with('success', 'Dataset updated successfully.');
    }
    
    public function destroy(Dataset $dataset)
    {
        $dataset->delete();
        return back()->with('success', 'Dataset deleted successfully.');
    }
    
 public function approve(Dataset $dataset)
{
    // Pastikan user adalah admin
    if (!auth()->user()->isAdmin()) {
        abort(403, 'ADMIN ACCESS REQUIRED.');
    }
    
    $dataset->update([
        'status' => 'approved',
        'approved_at' => now(),
        'approved_by' => auth()->id()
    ]);
    
    return redirect()->back()->with('success', 'Dataset approved successfully.');
}
    
    public function reject(Request $request, Dataset $dataset)
    {
        $dataset->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'admin_notes' => $request->input('rejection_reason')
        ]);
        
        return back()->with('success', 'Dataset rejected.');
    }
    
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'dataset_ids' => 'required|array',
            'dataset_ids.*' => 'exists:datasets,dataset_id',
            'action' => 'required|in:approve,reject,delete,mark_available'
        ]);
        
        $ids = $validated['dataset_ids'];
        
        DB::transaction(function() use ($ids, $validated) {
            switch ($validated['action']) {
                case 'approve':
                    Dataset::whereIn('dataset_id', $ids)
                        ->update(['status' => 'approved', 'approved_at' => now()]);
                    break;
                case 'reject':
                    Dataset::whereIn('dataset_id', $ids)
                        ->update(['status' => 'rejected', 'rejected_at' => now()]);
                    break;
                case 'delete':
                    Dataset::whereIn('dataset_id', $ids)->delete();
                    break;
                case 'mark_available':
                    Dataset::whereIn('dataset_id', $ids)
                        ->update(['status' => 'available']);
                    break;
            }
        });
        
        return back()->with('success', 'Bulk action completed successfully.');
    }
}