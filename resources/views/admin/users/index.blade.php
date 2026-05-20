@extends('layouts.admin')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Users</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add User</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card stat-card border-primary h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3"><i class="bi bi-people fs-4 text-primary"></i></div><div><h6 class="text-muted small mb-1">Total</h6><h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-success h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3"><i class="bi bi-check-circle fs-4 text-success"></i></div><div><h6 class="text-muted small mb-1">Active</h6><h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-danger h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3"><i class="bi bi-x-circle fs-4 text-danger"></i></div><div><h6 class="text-muted small mb-1">Banned</h6><h3 class="fw-bold mb-0">{{ $stats['banned'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-warning h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3"><i class="bi bi-clock-history fs-4 text-warning"></i></div><div><h6 class="text-muted small mb-1">Unverified</h6><h3 class="fw-bold mb-0">{{ $stats['unverified'] }}</h3></div></div></div></div>
</div>

<!-- Filters -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search users..." value="{{ request('search') }}"></div>
    <div class="col-md-3"><select name="role" class="form-select form-select-sm"><option value="">All Roles</option><option value="user" {{ request('role')=='user'?'selected':'' }}>User</option><option value="contributor" {{ request('role')=='contributor'?'selected':'' }}>Contributor</option><option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option></select></div>
    <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option><option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option><option value="banned" {{ request('status')=='banned'?'selected':'' }}>Banned</option></select></div>
    <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
</form>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.users.bulk-action') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light"><tr><th width="40"><input type="checkbox" id="selectAll"></th><th>User</th><th>Role</th><th>Datasets</th><th>Status</th><th width="150">Actions</th></tr></thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}" {{ $user->id===auth()->id()?'disabled':'' }}></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-weight:600;">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                    <div><a href="{{ route('admin.users.edit', $user) }}" class="fw-semibold text-decoration-none">{{ $user->name }}</a><div class="small text-muted">{{ $user->email }}</div></div>
                                </div>
                            </td>
                            <td><span class="badge bg-{{ $user->role==='admin'?'primary':($user->role==='contributor'?'info':'secondary') }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->datasets_count }}</td>
                            <td><span class="badge bg-{{ $user->banned_at?'danger':'success' }}">{{ $user->banned_at?'Banned':'Active' }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <button type="button" onclick="toggleBan({{ $user->id }})" class="btn btn-outline-{{ $user->banned_at?'success':'warning' }}"><i class="bi bi-{{ $user->banned_at?'unlock':'ban' }}"></i></button>
                                    <button type="button" onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}')" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->count())
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <select name="action" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Bulk Actions...</option>
                        <option value="activate">Activate</option>
                        <option value="ban">Ban</option>
                        <option value="unban">Unban</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div>
                {{ $users->withQueryString()->links() }}
            </div>
            @endif
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', e => document.querySelectorAll('input[name="user_ids[]"]').forEach(c => !c.disabled && (c.checked = e.target.checked)));
function toggleBan(id) { fetch(`{{ url('admin/users') }}/${id}/toggle-ban`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}}).then(()=>location.reload()); }
function confirmDelete(url) { if(confirm('Delete this user?')) { document.getElementById('deleteForm').action = url; document.getElementById('deleteForm').submit(); } }
</script>
@endpush