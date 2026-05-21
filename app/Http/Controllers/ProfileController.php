<?php

namespace App\Http\Controllers;

use App\Models\{Dataset, Person, Variable, File, Task, SubjectArea, License, Doi, Keyword, Paper};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show user profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('profile.index', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'affiliation' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                \Illuminate\Support\Facades\Storage::delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        }
        
        $user->update($validated);
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($validated['password']),
        ]);
        
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Show user's donated datasets
     */
    public function datasets()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        // ✅ Load relationships dengan null safety
        $datasets = Dataset::where('user_id', $user->id)
            ->with(['task', 'subjectArea', 'contributors', 'files', 'license', 'doi'])
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
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        // ✅ Cek ownership dengan null safety
        $isOwner = false;
        
        if ($dataset->user_id === $user->id) {
            $isOwner = true;
        } else {
            // Cek via contributors
            $isOwner = $dataset->contributors()
                ->where('people.email', $user->email)
                ->orWhere('people.name', $user->name)
                ->exists();
        }
        
        if (!$isOwner) {
            abort(403, 'Unauthorized access.');
        }
        
        // ✅ Load all relationships
        $dataset->load([
            'task',
            'subjectArea', 
            'license',
            'doi',
            'contributors' => function($query) {
                $query->withPivot('contribution_role');
            },
            'papers',
            'keywords',
            'files',
            'variables'
        ]);
        
        // Parse additional_info JSON dengan null safety
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
        $user = Auth::user();
        
        // Check admin access
        if (!($user->is_admin ?? false)) {
            abort(403, 'Admin access required.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,available',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $updateData = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ];
        
        if ($validated['status'] === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = Auth::id();
        }
        
        if ($validated['status'] === 'rejected') {
            $updateData['rejected_at'] = now();
            $updateData['rejected_by'] = Auth::id();
        }
        
        $dataset->update($updateData);
        
        return redirect()->back()->with('success', 'Dataset status updated successfully.');
    }

    /**
     * Show user's dataset edits/submissions
     */
    // app/Http/Controllers/ProfileController.php

public function edits()
{
    // Ambil dataset yang:
    // 1. Dimiliki oleh user yang login
    // 2. Statusnya 'approved' atau 'available' (bisa diedit)
    $datasets = auth()->user()->datasets()
        ->whereIn('status', ['approved', 'available'])
        ->with(['files', 'keywords', 'contributors'])
        ->orderBy('updated_at', 'desc')
        ->paginate(10);
    
    return view('profile.edits', compact('datasets'));
}
    /**
 * Update dataset visibility
 */
public function updateVisibility(Dataset $dataset)
{
    // Pastikan user adalah pemilik dataset
    if ($dataset->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $validated = request()->validate([
        'is_public' => 'required|boolean',
    ]);

    // Update visibility (asumsikan ada kolom is_public atau similar)
    // Jika tidak ada kolom is_public, mungkin menggunakan status atau kolom lain
    $dataset->update([
        'status' => $validated['is_public'] ? 'available' : 'pending',
        // atau jika ada kolom is_public:
        // 'is_public' => $validated['is_public'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Visibility updated successfully',
    ]);
}
} 