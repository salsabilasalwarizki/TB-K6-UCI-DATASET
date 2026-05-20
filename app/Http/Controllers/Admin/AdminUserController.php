<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('datasets');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('status')) {
            match($request->status) {
                'active' => $query->whereNull('banned_at')->whereNotNull('email_verified_at'),
                'banned' => $query->whereNotNull('banned_at'),
                'unverified' => $query->whereNull('email_verified_at'),
            };
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $stats = [
            'total' => User::count(),
            'active' => User::whereNull('banned_at')->whereNotNull('email_verified_at')->count(),
            'banned' => User::whereNotNull('banned_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,contributor,admin,superadmin',
        ]);

        User::create(array_merge($validated, ['password' => Hash::make($validated['password'])]));
        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user) { return view('admin.users.edit', compact('user')); }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,contributor,admin,superadmin',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot delete yourself.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) return response()->json(['error' => 'Self-ban not allowed'], 403);
        $user->update(['banned_at' => $user->banned_at ? null : now()]);
        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'action' => 'required|in:activate,ban,unban,demote,delete'
        ]);

        $ids = collect($validated['user_ids'])->filter(fn($id) => $id != auth()->id());
        
        match($validated['action']) {
            'activate' => User::whereIn('id', $ids)->update(['banned_at' => null, 'email_verified_at' => now()]),
            'ban' => User::whereIn('id', $ids)->where('role', '!=', 'superadmin')->update(['banned_at' => now()]),
            'unban' => User::whereIn('id', $ids)->update(['banned_at' => null]),
            'demote' => User::whereIn('id', $ids)->where('role', '!=', 'superadmin')->update(['role' => 'user']),
            'delete' => User::whereIn('id', $ids)->delete(),
        };

        return back()->with('success', 'Bulk action applied.');
    }
}