@extends('layouts.app')
@section('title', 'Admin Dashboard - UCI Machine Learning Repository')

@section('content')
<div class="admin-dashboard">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-primary fw-bold">
                <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
            </h1>
            <p class="text-muted mb-0">Manage datasets, users, and repository settings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-grid me-1"></i>View All Datasets
            </a>
            <a href="{{ route('contribute.policy') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>New Donation
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Datasets -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-primary h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-database fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Total Datasets</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_datasets'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Review -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-warning h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-clock-history fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Pending Review</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['pending_datasets'] ?? 0) }}</h3>
                    </div>
                </div>
                @if(($stats['pending_datasets'] ?? 0) > 0)
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('datasets.index', ['status' => 'pending']) }}" class="small text-warning text-decoration-none">
                        Review now <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Approved -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-success h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Approved</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['approved_datasets'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Users -->
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-info h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-1">Total Users</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Charts & Tables -->
    <div class="row g-4">
        
        <!-- Left Column: Pending Datasets -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-inbox me-2 text-warning"></i>Pending Review ({{ count($pendingDatasets) }})
                    </h5>
                    <a href="{{ route('datasets.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($pendingDatasets->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Dataset</th>
                                    <th>Submitted</th>
                                    <th>Donator</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDatasets as $dataset)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($dataset->thumbnail_url)
                                            <img src="{{ $dataset->thumbnail_url }}" alt="" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-database text-primary"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('datasets.show', $dataset) }}" class="fw-semibold text-decoration-none">
                                                    {{ Str::limit($dataset->name, 30) }}
                                                </a>
                                                @if($dataset->subject_area)
                                                <div class="small text-muted">{{ $dataset->subject_area }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $dataset->donated_date?->format('M d, Y') ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $donator = $dataset->contributors->firstWhere('pivot.contribution_role', 'donor') 
                                                ?? $dataset->contributors->first();
                                        @endphp
                                        @if($donator)
                                        <small>{{ $donator->name }}</small>
                                        @else
                                        <small class="text-muted">Unknown</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dataset->data_type)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $dataset->data_type }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('datasets.show', $dataset) }}" class="btn btn-outline-secondary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('profile.dataset.update-status', $dataset) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="status" value="approved" class="btn btn-outline-success" title="Approve" onclick="return confirm('Approve this dataset?')">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button type="submit" name="status" value="rejected" class="btn btn-outline-danger" title="Reject" onclick="return confirm('Reject this dataset?')">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3 mb-0">No pending datasets</p>
                        <small class="text-muted">All caught up! 🎉</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Column: Charts & Activity -->
        <div class="col-lg-4">
            
            <!-- Monthly Submissions Chart -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Submissions
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="submissionsChart" height="200"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-clock-history me-2 text-info"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivity as $activity)
                        <div class="list-group-item px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-plus-circle text-success"></i>
                                        <a href="{{ route('datasets.show', $activity) }}" class="fw-semibold text-decoration-none">
                                            {{ Str::limit($activity->name, 25) }}
                                        </a>
                                    </div>
                                    <small class="text-muted">
                                        Added {{ $activity->created_at?->diffForHumans() ?? 'recently' }}
                                        @if($activity->status !== 'available')
                                        • <span class="badge bg-{{ $activity->status === 'pending' ? 'warning' : 'secondary' }} bg-opacity-75">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                        @endif
                                    </small>
                                </div>
                                <a href="{{ route('datasets.show', $activity) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="p-3 text-center text-muted small">
                            No recent activity
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Quick Actions Footer -->
    <div class="mt-4 pt-3 border-top">
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i>Manage Users
            </a>
            <a href="{{ route('datasets.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid me-1"></i>Browse Datasets
            </a>
            <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-gear me-1"></i>Settings
            </a>
            <button class="btn btn-outline-danger btn-sm" onclick="if(confirm('Clear cache?')) location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-dashboard {
        background: #f8f9fa;
        min-height: calc(100vh - 60px);
        padding: 1.5rem 0;
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
    
    .list-group-item {
        border-left: 3px solid transparent;
        transition: border-color 0.2s, background 0.2s;
    }
    
    .list-group-item:hover {
        border-left-color: var(--bs-primary);
        background: rgba(0,119,182,0.03);
    }
    
    /* Chart container */
    #submissionsChart {
        max-width: 100%;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .admin-dashboard {
            padding: 1rem 0;
        }
        
        .stat-card .card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Submissions Chart
    const ctx = document.getElementById('submissionsChart');
    if (ctx) {
        const monthlyData = @json($monthlySubmissions);
        
        const labels = monthlyData.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month - 1).toLocaleString('default', { month: 'short', year: '2-digit' });
        });
        
        const data = monthlyData.map(item => item.count);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Datasets Submitted',
                    data: data,
                    borderColor: '#0077b6',
                    backgroundColor: 'rgba(0, 119, 182, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0077b6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
    
    // Auto-refresh pending count badge (optional)
    // setInterval(() => {
    //     fetch('/api/admin/stats')
    //         .then(r => r.json())
    //         .then(data => {
    //             // Update badge counts if needed
    //         });
    // }, 30000);
});
</script>
@endpush