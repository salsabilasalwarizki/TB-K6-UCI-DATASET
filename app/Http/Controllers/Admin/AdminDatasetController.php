<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDatasetController extends Controller
{
    /**
     * Display list of datasets
     */
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
        
        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $datasets = $query->paginate(15)->withQueryString();
        

        $stats = [
            'total' => Dataset::count(),
            'pending' => Dataset::where('status', 'pending')->count(),
            'approved' => Dataset::where('status', 'approved')->count(),
            'rejected' => Dataset::where('status', 'rejected')->count(),
        ];
        // dd($stats);
        // 6. Return View dengan variabel 'datasets' DAN 'stats'
        return view('admin.datasets.index', compact('datasets', 'stats'));
    }
    
    /**
     * Export datasets to CSV
     */
    public function export(Request $request)
    {
        $query = Dataset::with(['user', 'keywords']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $datasets = $query->get();
        
        $filename = 'datasets_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'ID',
            'Name',
            'Subject Area',
            'Data Type',
            'Status',
            'Instances',
            'Features',
            'User',
            'Created At'
        ]);
        
        foreach ($datasets as $dataset) {
            fputcsv($output, [
                $dataset->dataset_id,
                $dataset->name,
                $dataset->subject_area,
                $dataset->data_type,
                $dataset->status,
                $dataset->num_instances,
                $dataset->num_features,
                $dataset->user->name ?? 'N/A',
                $dataset->created_at->format('Y-m-d')
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Show edit form
     */
    public function edit(Dataset $dataset)
    {
        return view('admin.datasets.edit', compact('dataset'));
    }
    
    /**
     * Update dataset
     */
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
    
    /**
     * Delete dataset
     */
    public function destroy(Dataset $dataset)
    {
        try {
            $dataset->delete();
            return back()->with('success', 'Dataset deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete dataset: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve dataset
     */
    public function approve(Dataset $dataset)
    {
        $dataset->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
        
        return back()->with('success', 'Dataset approved successfully.');
    }
    
    /**
     * Reject dataset
     */
    public function reject(Request $request, Dataset $dataset)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000'
        ]);
        
        $dataset->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'admin_notes' => $request->rejection_reason
        ]);
        
        return back()->with('success', 'Dataset rejected.');
    }
    
    /**
     * Bulk action on datasets
     */
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
                        ->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'updated_at' => now()
                        ]);
                    break;
                case 'reject':
                    Dataset::whereIn('dataset_id', $ids)
                        ->update([
                            'status' => 'rejected',
                            'rejected_at' => now(),
                            'updated_at' => now()
                        ]);
                    break;
                case 'delete':
                    Dataset::whereIn('dataset_id', $ids)->delete();
                    break;
                case 'mark_available':
                    Dataset::whereIn('dataset_id', $ids)
                        ->update([
                            'status' => 'available',
                            'updated_at' => now()
                        ]);
                    break;
            }
        });
        
        return back()->with('success', 'Bulk action completed successfully.');
    }
}