<?php
namespace App\Http\Controllers;

use App\Models\{Dataset, Creator, Variable, File, Task, SubjectArea, License, Doi, Keyword, Paper};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
         $user = Auth::user();
        
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        // ... existing code ...
    }

    public function updatePassword(Request $request)
    {
        // ... existing code ...
    }

    /**
     * Show user's donated datasets
     */
  public function datasets()
{
    $user = Auth::user();
    
    // ✅ Filter langsung oleh user_id di tabel datasets (lebih simple & pasti works)
    $datasets = Dataset::where('user_id', $user->id)
        ->with(['task', 'subjectArea', 'creators', 'files'])
        ->orderBy('donated_date', 'desc')
        ->paginate(10);
    
    return view('profile.datasets', compact('datasets'));
}
   /**
 * Show dataset detail view
 */
public function showDataset(Dataset $dataset)
{
    $user = Auth::user();
    
    // ✅ Cek ownership: apakah user ini ada di daftar creators (via pivot table)
    $isOwner = $dataset->creators()->where('people.email', $user->email)
        ->orWhere('people.name', $user->name)
        ->exists();
    
    // Atau fallback: cek user_id di tabel datasets
    if (!$isOwner && $dataset->user_id !== $user->id) {
        abort(403, 'Unauthorized access.');
    }
    
    // Load all relationships
    $dataset->load([
        'task',
        'subjectArea', 
        'license',
        'doi',
        'creators' => function($query) {
            $query->withPivot('contribution_role');
        },
        'papers',
        'keywords',
        'files',
        'variables'
    ]);
    
    // Parse additional_info JSON
    $additionalInfo = json_decode($dataset->additional_info ?? '{}', true) ?? [];
    $descriptiveInfo = $additionalInfo['descriptive'] ?? [];
    
    // Calculate statistics
    $totalViews = $dataset->view_count ?? 0;
    $totalDownloads = $dataset->download_count ?? 0;
    $totalCitations = $dataset->citation_count ?? 0;
    
    return view('profile.dataset-detail', compact(
        'dataset',
        'descriptiveInfo',
        'totalViews',
        'totalDownloads',
        'totalCitations'
    ));
}

    /**
     * Update dataset status (for admin)
     */
    public function updateDatasetStatus(Request $request, Dataset $dataset)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $dataset->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);
        
        return redirect()->back()->with('success', 'Dataset status updated successfully.');
    }

    public function edits()
    {
        return view('profile.edits');
    }
}