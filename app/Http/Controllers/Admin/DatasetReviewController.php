<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatasetReviewController extends Controller
{
    /**
     * Show all datasets for review
     */
    public function index(Request $request)
    {
        $query = Dataset::with(['creators', 'subjectArea', 'task', 'user']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sortBy = $request->get('sort', 'donated_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $datasets = $query->paginate(20);
        
        return view('admin.datasets.index', compact('datasets'));
    }

    /**
     * Show dataset detail for review
     */
    public function show(Dataset $dataset)
    {
        $dataset->load([
            'creators' => function($query) {
                $query->withPivot('contribution_role');
            },
            'papers',
            'keywords',
            'files',
            'variables',
            'subjectArea',
            'task',
            'license',
            'doi',
            'user'
        ]);
        
        // Parse additional info
        $additionalInfo = json_decode($dataset->additional_info ?? '{}', true) ?? [];
        
        return view('admin.datasets.review', compact('dataset', 'additionalInfo'));
    }

    /**
     * Approve dataset
     */
    public function approve(Dataset $dataset, Request $request)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $dataset->update([
            'status' => 'approved',
            'is_public' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);
        
        // Optional: Send email notification to dataset owner
        
        return redirect()->back()->with('success', 'Dataset approved successfully!');
    }

    /**
     * Reject dataset
     */
    public function reject(Dataset $dataset, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        $dataset->update([
            'status' => 'rejected',
            'is_public' => false,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'admin_notes' => $validated['rejection_reason'],
        ]);
        
        // Optional: Send email notification to dataset owner
        
        return redirect()->back()->with('success', 'Dataset rejected.');
    }

    /**
     * Set dataset to pending
     */
    public function setPending(Dataset $dataset)
    {
        $dataset->update([
            'status' => 'pending',
            'is_public' => false,
        ]);
        
        return redirect()->back()->with('success', 'Dataset status set to pending.');
    }

    /**
     * Bulk approve datasets
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'dataset_ids' => 'required|array',
            'dataset_ids.*' => 'exists:datasets,dataset_id',
        ]);
        
        $count = Dataset::whereIn('dataset_id', $validated['dataset_ids'])
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'is_public' => true,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        
        return redirect()->back()->with('success', "{$count} datasets approved successfully!");
    }
}