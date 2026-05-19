<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display listing of users
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->withCount('datasets')
            ->with(['person' => fn($q) => $q->select('person_id', 'user_id', 'affiliation')]);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->whereNotNull('last_login_at')->whereNull('banned_at'),
                'inactive' => $query->whereNull('last_login_at')->whereNull('banned_at'),
                'banned' => $query->whereNotNull('banned_at'),
                default => null,
            };
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        // Stats for cards
        $stats = [
            'total' => User::count(),
            'active' => User::whereNotNull('last_login_at')->whereNull('banned_at')->count(),
            'pending' => User::whereNull('last_login_at')->whereNull('banned_at')->count(),
            'banned' => User::whereNotNull('banned_at')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    /**
     * Show user profile/details
     */
    public function show(User $user)
    {
        $user->load(['datasets' => fn($q) => $q->latest()->take(10), 'person']);
        
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'contributor', 'admin', 'superadmin'])],
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }
    
    /**
     * Toggle ban status (AJAX)
     */
    public function toggleBan(Request $request, User $user)
    {
        $request->validate(['ban' => 'required|boolean']);
        
        // Prevent banning self or superadmins
        if ($user->id === auth()->id() || $user->role === 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Action not allowed'], 403);
        }
        
        $user->update([
            'banned_at' => $request->boolean('ban') ? now() : null,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Bulk action on users
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'bulk_action' => 'required|in:activate,deactivate,ban,unban,promote_contributor,demote_user',
        ]);
        
        $userIds = collect($validated['user_ids'])->filter(fn($id) => $id !== auth()->id());
        
        if ($userIds->isEmpty()) {
            return back()->with('warning', 'No valid users selected');
        }
        
        DB::transaction(function() use ($userIds, $request) {
            match ($request->bulk_action) {
                'activate' => User::whereIn('id', $userIds)->update(['banned_at' => null]),
                'deactivate' => User::whereIn('id', $userIds)->update(['last_login_at' => null]),
                'ban' => User::whereIn('id', $userIds)->where('role', '!=', 'superadmin')->update(['banned_at' => now()]),
                'unban' => User::whereIn('id', $userIds)->update(['banned_at' => null]),
                'promote_contributor' => User::whereIn('id', $userIds)->where('role', 'user')->update(['role' => 'contributor']),
                'demote_user' => User::whereIn('id', $userIds)->whereIn('role', ['contributor', 'admin'])->update(['role' => 'user']),
            };
        });
        
        return back()->with('success', 'Bulk action applied successfully');
    }
    
    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        // Simple CSV export (consider using Laravel Excel for production)
        $users = User::withCount('datasets')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Datasets', 'Joined']);
            
            foreach ($users as $user) {
                $status = $user->banned_at ? 'Banned' : ($user->last_login_at ? 'Active' : 'Inactive');
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $status,
                    $user->datasets_count,
                    $user->created_at?->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}