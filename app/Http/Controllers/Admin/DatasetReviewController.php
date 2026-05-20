<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Dataset, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DatasetReviewController extends Controller
{
    // List Dataset dengan Filter
    public function index(Request $request)
    {
        $query = Dataset::with(['contributors', 'user']);

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $datasets = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.datasets.index', compact('datasets'));
    }

    // Detail Review Dataset
    public function review($datasetId)
    {
        $dataset = Dataset::with([
            'contributors', 
            'variables', 
            'files', 
            'keywords',
            'papers',
            'description'
        ])->findOrFail($datasetId);

        return view('admin.datasets.review', compact('dataset'));
    }

    // Approve Dataset
    public function approve($datasetId, Request $request)
    {
        $dataset = Dataset::findOrFail($datasetId);
        
        DB::beginTransaction();
        try {
            $dataset->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'admin_notes' => $request->admin_notes ?? null,
            ]);

            // Optional: Send notification to donor here

            DB::commit();
            return redirect()->back()->with('success', 'Dataset approved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to approve: ' . $e->getMessage()]);
        }
    }

    // Reject Dataset
    public function reject($datasetId, Request $request)
    {
        $dataset = Dataset::findOrFail($datasetId);

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $dataset->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'admin_notes' => $request->rejection_reason,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Dataset rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to reject: ' . $e->getMessage()]);
        }
    }

    // Bulk Approve
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'dataset_ids' => 'required|array',
            'dataset_ids.*' => 'exists:datasets,dataset_id'
        ]);

        Dataset::whereIn('dataset_id', $request->dataset_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

        return back()->with('success', 'Datasets approved successfully!');
    }
}