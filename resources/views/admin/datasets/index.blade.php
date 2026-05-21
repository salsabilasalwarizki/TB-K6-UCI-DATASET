@extends('layouts.admin')
@section('title', 'Manage Datasets')
@section('page-title', 'Dataset Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Datasets</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.datasets.export') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <a href="{{ route('admin.datasets.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add New
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-primary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-database fs-4 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Pending</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Approved</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['approved'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Rejected</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-info h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-eye fs-4 text-info"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Available</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['available'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Monthly Submissions</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control form-control-sm" 
               placeholder="Search datasets..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="from_date" class="form-control form-control-sm" 
               value="{{ request('from_date') }}" placeholder="From">
    </div>
    <div class="col-md-2">
        <input type="date" name="to_date" class="form-control form-control-sm" 
               value="{{ request('to_date') }}" placeholder="To">
    </div>
    <div class="col-md-2">
        <select name="sort" class="form-select form-select-sm">
            <option value="created_at" {{ request('sort')=='created_at'?'selected':'' }}>Newest</option>
            <option value="name" {{ request('sort')=='name'?'selected':'' }}>Name A-Z</option>
            <option value="num_instances" {{ request('sort')=='num_instances'?'selected':'' }}>Instances</option>
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
    </div>
</form>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.datasets.bulk-action') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll"></th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Instances</th>
                            <th>Status</th>
                            <th>Contributor</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datasets as $ds)
                        <tr>
                            <td><input type="checkbox" name="dataset_ids[]" value="{{ $ds->dataset_id }}"></td>
                            <td>
                                <a href="{{ route('datasets.show', $ds) }}" class="fw-semibold text-decoration-none">
                                    {{ $ds->name }}
                                </a>
                            </td>
                            <td>{{ $ds->subject_area }}</td>
                            <td>{{ number_format($ds->num_instances ?? 0) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $ds->status==='approved'?'success':
                                    ($ds->status==='rejected'?'danger':
                                    ($ds->status==='available'?'info':'warning'))
                                }}">
                                    {{ ucfirst($ds->status) }}
                                </span>
                            </td>
                            <td>{{ $ds->user->name ?? 'N/A' }}</td>
                            <td class="small text-muted">{{ $ds->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.datasets.edit', $ds) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($ds->status==='pending')
                                    <button type="button" onclick="quickAction('approve', {{ $ds->dataset_id }})" 
                                            class="btn btn-outline-success" title="Approve">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    @endif
                                    <button type="button" onclick="confirmDelete('{{ route('admin.datasets.destroy', $ds) }}')" 
                                            class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No datasets found.</td>
                        </tr>
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
                        <option value="mark_available">Mark as Available</option>
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

<form id="deleteForm" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Submissions Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyData->pluck('month')) !!},
        datasets: [{
            label: 'Datasets',
            data: {!! json_encode($monthlyData->pluck('count')) !!},
            borderColor: '#0077b6',
            backgroundColor: 'rgba(0, 119, 182, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($statusData->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode($statusData->pluck('count')) !!},
            backgroundColor: ['#ffc107', '#28a745', '#dc3545', '#17a2b8', '#6c757d']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Select all
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="dataset_ids[]"]').forEach(cb => cb.checked = this.checked);
});

// Quick action
function quickAction(action, id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/datasets/${id}/${action}`;
    form.innerHTML = '@csrf';
    document.body.appendChild(form);
    form.submit();
}

// Confirm delete
function confirmDelete(url) {
    if (confirm('Are you sure you want to delete this dataset?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush