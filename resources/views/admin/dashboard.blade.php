@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Datasets -->
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary text-white">
                    <i class="bi bi-database"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Datasets</h6>
                    <h3 class="mb-0">{{ $stats['total_datasets'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Review -->
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning text-dark">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Pending Review</h6>
                    <h3 class="mb-0">{{ $stats['pending_datasets'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approved -->
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success text-white">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Approved</h6>
                    <h3 class="mb-0">{{ $stats['approved_datasets'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Users -->
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info text-white">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Users</h6>
                    <h3 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Datasets for Review -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Pending Review ({{ $pendingDatasets->count() }})
                </h5>
                <a href="{{ route('admin.datasets.index', ['status' => 'pending']) }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                @if($pendingDatasets->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle display-4 d-block mb-3"></i>
                        <p>No datasets pending review. Great job!</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Dataset</th>
                                    <th>Donor</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDatasets as $dataset)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.datasets.review', $dataset) }}" class="text-decoration-none">
                                            {{ Str::limit($dataset->name, 40) }}
                                        </a>
                                    </td>
                                    <td>
                                        @foreach($dataset->creators->take(1) as $creator)
                                            {{ $creator->name }}
                                        @endforeach
                                    </td>
                                    <td>{{ $dataset->donated_date?->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.datasets.review', $dataset) }}" class="btn btn-sm btn-outline-primary">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Activity & Chart -->
    <div class="col-lg-4">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($recentActivity->take(5) as $activity)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong class="d-block">{{ Str::limit($activity->name, 25) }}</strong>
                                <small class="text-muted">
                                    {{ $activity->creators->first()?->name ?? 'Unknown' }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $activity->status === 'approved' ? 'success' : ($activity->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($activity->status ?? 'pending') }}
                            </span>
                        </div>
                        <small class="text-muted">
                            {{ $activity->created_at?->diffForHumans() }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Monthly Submissions Chart -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Monthly Submissions
                </h5>
            </div>
            <div class="card-body">
                <canvas id="submissionsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Monthly Submissions Chart
const ctx = document.getElementById('submissionsChart');
if (ctx) {
    const data = @json($monthlySubmissions);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.month),
            datasets: [{
                label: 'Datasets Submitted',
                data: data.map(d => d.count),
                borderColor: '#0077b6',
                backgroundColor: 'rgba(0, 119, 182, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
</script>
@endpush