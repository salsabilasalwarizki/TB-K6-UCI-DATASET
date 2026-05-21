<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('datasets');
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        
        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter status - SESUAIKAN DENGAN KOLOM YANG ADA
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    // Gunakan is_active jika ada, atau asumsikan semua user aktif
                    $query->where('is_active', 1);
                    break;
                case 'inactive':
                    $query->where('is_active', 0);
                    break;
                // Hapus case 'banned' dan 'unverified' jika kolom tidak ada
            }
        }
        
        $users = $query->latest()->paginate(15)->withQueryString();
        
        // Statistics - SESUAIKAN DENGAN KOLOM YANG ADA
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', 1)->count(),
            'inactive' => User::where('is_active', 0)->count(),
            'admins' => User::whereIn('role', ['admin', 'superadmin'])->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,contributor,admin,superadmin',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:user,contributor,admin,superadmin',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        
        return back()->with('success', 'User deleted successfully.');
    }
    
    // Hapus atau update method toggleBan jika kolom banned_at tidak ada
    public function toggleBan(User $user)
    {
        // Jika kolom is_active ada, gunakan itu
        if (\Schema::hasColumn('users', 'is_active')) {
            $user->update([
                'is_active' => $user->is_active ? 0 : 1
            ]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Ban feature not available'], 400);
    }
    
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,demote,delete'
        ]);
        
        $ids = collect($validated['user_ids'])
            ->filter(fn($id) => $id != auth()->id());
        
        if ($ids->isEmpty()) {
            return back()->with('error', 'No valid users selected.');
        }
        
        switch ($validated['action']) {
            case 'activate':
                if (\Schema::hasColumn('users', 'is_active')) {
                    User::whereIn('id', $ids)->update(['is_active' => 1]);
                }
                break;
            case 'deactivate':
                if (\Schema::hasColumn('users', 'is_active')) {
                    User::whereIn('id', $ids)->update(['is_active' => 0]);
                }
                break;
            case 'demote':
                User::whereIn('id', $ids)
                    ->where('role', '!=', 'superadmin')
                    ->update(['role' => 'user']);
                break;
            case 'delete':
                User::whereIn('id', $ids)->delete();
                break;
        }
        
        return back()->with('success', 'Bulk action completed successfully.');
    }
    
    public function export(Request $request)
    {
        $users = User::withCount('datasets')->get();
        
        $filename = 'users_'.now()->format('Y-m-d_H-i-s').'.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Role', 'Status', 
            'Datasets', 'Created At'
        ]);
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->is_active ?? 1 ? 'Active' : 'Inactive',
                $user->datasets_count,
                $user->created_at->format('Y-m-d')
            ]);
        }
        
        fclose($output);
        exit;
    }
}