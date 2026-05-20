<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDatasetController extends Controller
{
    public function index(Request $request)
    {
        $query = Dataset::with(['user', 'keywords', 'files']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('subject_area', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->order ?? 'desc');
        } else {
            $query->latest();
        }

        $datasets = $query->paginate(15)->withQueryString();
        $stats = [
            'total' => Dataset::count(),
            'pending' => Dataset::where('status', 'pending')->count(),
            'approved' => Dataset::where('status', 'approved')->count(),
            'rejected' => Dataset::where('status', 'rejected')->count(),
        ];

        return view('admin.datasets.index', compact('datasets', 'stats'));
    }

    public function edit(Dataset $dataset)
    {
        return view('admin.datasets.edit', compact('dataset'));
    }

    public function update(Request $request, Dataset $dataset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abstract' => 'required|string|max:2000',
            'status' => 'required|in:pending,approved,rejected,available,deprecated',
            'subject_area' => 'nullable|string|max:100',
            'data_type' => 'nullable|string|max:50',
            'task_type' => 'nullable|string|max:50',
            'num_instances' => 'nullable|integer|min:0',
            'num_features' => 'nullable|integer|min:0',
        ]);

        $dataset->update($validated);
        return redirect()->route('admin.datasets.index')->with('success', 'Dataset updated successfully.');
    }

    public function destroy(Dataset $dataset)
    {
        $dataset->delete();
        return back()->with('success', 'Dataset deleted.');
    }

    public function approve(Dataset $dataset)
    {
        $dataset->update(['status' => 'approved', 'approved_at' => now()]);
        return back()->with('success', 'Dataset approved.');
    }

    public function reject(Dataset $dataset)
    {
        $dataset->update(['status' => 'rejected']);
        return back()->with('success', 'Dataset rejected.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'dataset_ids' => 'required|array',
            'action' => 'required|in:approve,reject,delete'
        ]);

        DB::transaction(function() use ($validated) {
            $ids = $validated['dataset_ids'];
            match($validated['action']) {
                'approve' => Dataset::whereIn('dataset_id', $ids)->update(['status' => 'approved', 'approved_at' => now()]),
                'reject' => Dataset::whereIn('dataset_id', $ids)->update(['status' => 'rejected']),
                'delete' => Dataset::whereIn('dataset_id', $ids)->delete(),
            };
        });

        return back()->with('success', 'Bulk action applied successfully.');
    }
}