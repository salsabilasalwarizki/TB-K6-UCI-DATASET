<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $datasets = Dataset::whereHas('creators', function($q) use ($user) {
            $q->where('creators.id', $user->id)
              ->orWhere('creators.email', $user->email);
        })->with(['task', 'subjectArea'])->get();
        
        return view('admin.users.show', compact('user', 'datasets'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin,superadmin',
        ]);
        
        // Prevent self-demotion
        if ($user->id === auth()->id() && $validated['role'] !== 'superadmin') {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }
        
        $user->update($validated);
        
        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active,
        ]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()->with('success', "User {$status} successfully!");
    }
}