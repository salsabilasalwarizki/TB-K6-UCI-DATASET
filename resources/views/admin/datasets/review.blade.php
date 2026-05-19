@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">User Management</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.export') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Search users..." value="{{ request('search') }}">
            <select name="role" class="form-select form-select-sm">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="contributor" {{ request('role') === 'contributor' ? 'selected' : '' }}>Contributor</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
            </select>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-primary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total Users</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['total'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Active</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['active'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Pending</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['pending'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Banned</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['banned'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.users.bulk-action') }}">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll"></th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Datasets</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                @if($user->id !== auth()->id()) {{-- Prevent self-action --}}
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 600;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user) }}" class="fw-semibold text-decoration-none">
                                            {{ $user->name }}
                                        </a>
                                        @if($user->person?->affiliation)
                                        <div class="small text-muted">{{ $user->person->affiliation }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none small">
                                    {{ $user->email }}
                                </a>
                                @if($user->email_verified_at)
                                <i class="bi bi-check-circle-fill text-success ms-1" title="Email verified"></i>
                                @else
                                <i class="bi bi-x-circle-fill text-muted ms-1" title="Email not verified"></i>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' || $user->role === 'superadmin' ? 'primary' : ($user->role === 'contributor' ? 'info' : 'secondary') }} bg-opacity-75">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_banned)
                                <span class="badge bg-danger">Banned</span>
                                @elseif($user->last_login_at)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}#datasets" class="text-decoration-none">
                                    {{ $user->datasets_count ?? 0 }} dataset{{ ($user->datasets_count ?? 0) != 1 ? 's' : '' }}
                                </a>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-{{ $user->is_banned ? 'success' : 'danger' }}" 
                                            title="{{ $user->is_banned ? 'Unban' : 'Ban' }}"
                                            onclick="toggleBan({{ $user->id }}, '{{ $user->name }}', {{ $user->is_banned ? 'false' : 'true' }})">
                                        <i class="bi bi-{{ $user->is_banned ? 'unlock' : 'ban' }}"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 opacity-25 d-block mb-3"></i>
                                No users found matching your filters.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->isNotEmpty())
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <select name="bulk_action" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Bulk Actions...</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="ban">Ban Selected</option>
                        <option value="unban">Unban Selected</option>
                        <option value="promote_contributor">Promote to Contributor</option>
                        <option value="demote_user">Demote to User</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Apply bulk action to selected users?')">
                        Apply
                    </button>
                </div>
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        flex-shrink: 0;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-width: 2px !important;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="user_ids[]"]').forEach(cb => {
        if (cb.disabled) return; // Skip disabled checkboxes (self)
        cb.checked = this.checked;
    });
});

// Toggle ban status via AJAX
function toggleBan(userId, userName, ban) {
    const action = ban ? 'ban' : 'unban';
    const confirmMsg = ban 
        ? `Are you sure you want to BAN ${userName}? They will not be able to login.`
        : `Are you sure you want to UNBAN ${userName}?`;
    
    if (!confirm(confirmMsg)) return;
    
    fetch(`/admin/users/${userId}/toggle-ban`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ ban: ban })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${userName} has been ${ban ? 'banned' : 'unbanned'} successfully.`);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update user status'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to connect to server');
    });
}

// Auto-submit filter form on change (optional)
// document.querySelectorAll('select[name="role"], select[name="status"]').forEach(select => {
//     select.addEventListener('change', () => select.closest('form').submit());
// });
</script>
@endpush