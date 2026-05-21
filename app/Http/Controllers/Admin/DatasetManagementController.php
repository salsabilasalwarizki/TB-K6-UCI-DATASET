<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetManagementController extends Controller
{
    public function index()
    {
        $datasets = Dataset::with('user')->latest()->paginate(20);
        return view('admin.datasets.index', compact('datasets'));
    }
    
    public function edit(Dataset $dataset)
    {
        return view('admin.datasets.edit', compact('dataset'));
    }
    
    public function update(Request $request, Dataset $dataset)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,available',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $dataset->update($validated);
        
        return redirect()->route('admin.datasets.index')
            ->with('success', 'Dataset updated successfully.');
    }
    
    public function approve(Dataset $dataset)
    {
        $dataset->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        
        return redirect()->back()->with('success', 'Dataset approved.');
    }
    
    public function reject(Request $request, Dataset $dataset)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $dataset->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'admin_notes' => $request->rejection_reason,
        ]);
        
        return redirect()->back()->with('success', 'Dataset rejected.');
    }
    
    public function destroy(Dataset $dataset)
    {
        $dataset->delete();
        return redirect()->route('admin.datasets.index')
            ->with('success', 'Dataset deleted.');
    }
}