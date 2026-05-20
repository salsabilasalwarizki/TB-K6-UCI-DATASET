@extends('layouts.admin')
@section('title', 'Manage Datasets')
@section('page-title', 'Dataset Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Datasets</h2>
    <a href="{{ route('contribute.policy') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Add New
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card stat-card border-primary h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3"><i class="bi bi-database fs-4 text-primary"></i></div><div><h6 class="text-muted small mb-1">Total</h6><h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-warning h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3"><i class="bi bi-clock-history fs-4 text-warning"></i></div><div><h6 class="text-muted small mb-1">Pending</h6><h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-success h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3"><i class="bi bi-check-circle fs-4 text-success"></i></div><div><h6 class="text-muted small mb-1">Approved</h6><h3 class="fw-bold mb-0">{{ $stats['approved'] }}</h3></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card stat-card border-danger h-100"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3"><i class="bi bi-x-circle fs-4 text-danger"></i></div><div><h6 class="text-muted small mb-1">Rejected</h6><h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3></div></div></div></div>
</div>

<!-- Filters -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search datasets..." value="{{ request('search') }}"></div>
    <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option></select></div>
    <div class="col-md-3"><select name="sort" class="form-select form-select-sm"><option value="">Sort By</option><option value="created_at" {{ request('sort')=='created_at'?'selected':'' }}>Newest</option><option value="name" {{ request('sort')=='name'?'selected':'' }}>Name A-Z</option><option value="view_count" {{ request('sort')=='view_count'?'selected':'' }}>Most Viewed</option></select></div>
    <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
</form>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.datasets.bulk-action') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light"><tr><th width="40"><input type="checkbox" id="selectAll"></th><th>Name</th><th>Subject</th><th>Status</th><th>Date</th><th width="150">Actions</th></tr></thead>
                    <tbody>
                        @forelse($datasets as $ds)
                        <tr>
                            <td><input type="checkbox" name="dataset_ids[]" value="{{ $ds->dataset_id }}"></td>
                            <td><a href="{{ route('datasets.show', $ds) }}" class="fw-semibold text-decoration-none">{{ $ds->name }}</a></td>
                            <td>{{ $ds->subject_area }}</td>
                            <td><span class="badge bg-{{ $ds->status==='approved'?'success':($ds->status==='rejected'?'danger':'warning') }}">{{ ucfirst($ds->status) }}</span></td>
                            <td class="small text-muted">{{ $ds->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.datasets.edit', $ds) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                    @if($ds->status==='pending')<button type="button" onclick="quickAction('approve', {{ $ds->dataset_id }})" class="btn btn-outline-success" title="Approve"><i class="bi bi-check"></i></button>@endif
                                    <button type="button" onclick="confirmDelete('{{ route('admin.datasets.destroy', $ds) }}')" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No datasets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($datasets->count())
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <select name="action" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Bulk Actions...</option>
                        <option value="approve">Approve Selected</option>
                        <option value="reject">Reject Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div>
                {{ $datasets->withQueryString()->links() }}
            </div>
            @endif
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="d-none">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', e => document.querySelectorAll('input[name="dataset_ids[]"]').forEach(c => c.checked = e.target.checked));
function quickAction(action, id) { fetch(`{{ url('admin/datasets') }}/${id}/${action}`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload()); }
function confirmDelete(url) { if(confirm('Are you sure?')) { document.getElementById('deleteForm').action = url; document.getElementById('deleteForm').submit(); } }
</script>
@endpush