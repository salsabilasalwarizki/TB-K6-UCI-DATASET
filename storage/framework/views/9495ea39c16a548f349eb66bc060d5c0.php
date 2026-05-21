
<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-dashboard">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-1 text-primary fw-bold">
                <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
            </h1>
            <p class="text-muted mb-0 small">Manage datasets, users, and repository settings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-grid me-1"></i>View All Datasets
            </a>
            <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-primary btn-sm">
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
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($stats['total_datasets'] ?? 0)); ?></h3>
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
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($stats['pending_datasets'] ?? 0)); ?></h3>
                    </div>
                </div>
                <?php if(($stats['pending_datasets'] ?? 0) > 0): ?>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?php echo e(route('datasets.index', ['status' => 'pending'])); ?>" class="small text-warning text-decoration-none">
                        Review now <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <?php endif; ?>
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
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($stats['approved_datasets'] ?? 0)); ?></h3>
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
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($stats['total_users'] ?? 0)); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Charts & Tables -->
    <div class="row g-4">
        
        <!-- Left Column: Pending Datasets -->
        <div class="col-lg-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-inbox me-2 text-warning"></i>Pending Review (<?php echo e(count($pendingDatasets)); ?>)
                    </h5>
                    <a href="<?php echo e(route('datasets.index', ['status' => 'pending'])); ?>" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($pendingDatasets->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Dataset</th>
                                    <th>Submitted</th>
                                    <th>Donator</th>
                                    <th>Type</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingDatasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if($dataset->thumbnail_url): ?>
                                            <img src="<?php echo e($dataset->thumbnail_url); ?>" alt="" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                            <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-database text-primary"></i>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?php echo e(route('datasets.show', $dataset)); ?>" class="fw-semibold text-decoration-none text-dark">
                                                    <?php echo e(Str::limit($dataset->name, 30)); ?>

                                                </a>
                                                <?php if($dataset->subject_area): ?>
                                                <div class="small text-muted"><?php echo e($dataset->subject_area); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo e($dataset->donated_date?->format('M d, Y') ?? 'N/A'); ?>

                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                            $donator = $dataset->contributors->firstWhere('pivot.contribution_role', 'donor') 
                                                ?? $dataset->contributors->first();
                                        ?>
                                        <?php if($donator): ?>
                                        <small><?php echo e($donator->name); ?></small>
                                        <?php else: ?>
                                        <small class="text-muted">Unknown</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($dataset->data_type): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle"><?php echo e($dataset->data_type); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('datasets.show', $dataset)); ?>" class="btn btn-outline-secondary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.datasets.approve', $dataset)); ?>" method="POST" class="d-inline">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-outline-success" title="Approve" onclick="return confirm('Approve this dataset?')">
        <i class="bi bi-check"></i>
    </button>
</form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3 mb-0">No pending datasets</p>
                        <small class="text-muted">All caught up! 🎉</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Charts & Activity -->
        <div class="col-lg-4">
            
            <!-- Monthly Submissions Chart -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Submissions
                    </h5>
                </div>
                <div class="card-body px-4">
                    <canvas id="submissionsChart" height="200"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-clock-history me-2 text-info"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('datasets.show', $activity)); ?>" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-plus-circle text-success"></i>
                                        <span class="fw-semibold text-dark">
                                            <?php echo e(Str::limit($activity->name, 25)); ?>

                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Added <?php echo e($activity->created_at?->diffForHumans() ?? 'recently'); ?>

                                        <?php if($activity->status !== 'available'): ?>
                                        • <span class="badge bg-<?php echo e($activity->status === 'pending' ? 'warning' : 'secondary'); ?> bg-opacity-75">
                                            <?php echo e(ucfirst($activity->status)); ?>

                                        </span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-clock-history fs-4 d-block mb-2 opacity-50"></i>
                            No recent activity
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Quick Actions Footer -->
    <div class="mt-4 pt-3 border-top">
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i>Manage Users
            </a>
            <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid me-1"></i>Browse Datasets
            </a>
            <a href="<?php echo e(route('profile')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-gear me-1"></i>Settings
            </a>
            <button class="btn btn-outline-danger btn-sm" onclick="if(confirm('Clear cache?')) location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .admin-dashboard {
        background: #f8f9fa;
        min-height: calc(100vh - 60px);
        padding: 1.5rem 0;
    }
    
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-width: 2px !important;
        border-radius: 8px;
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
        border-radius: 12px;
    }
    
    .table th {
        font-weight: 600;
        color: var(--bs-primary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .list-group-item {
        border-left: 3px solid transparent;
        transition: border-color 0.2s ease, background-color 0.2s ease;
        cursor: pointer;
    }
    
    .list-group-item:hover {
        border-left-color: var(--bs-primary);
        background-color: rgba(0,119,182,0.03);
    }
    
    .list-group-item-action:hover {
        text-decoration: none;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
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
        
        .table-responsive {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 767px) {
        .page-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }
        
        .quick-actions .d-flex {
            justify-content: center;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Submissions Chart
    const ctx = document.getElementById('submissionsChart');
    if (ctx) {
        const monthlyData = <?php echo json_encode($monthlySubmissions ?? [], 15, 512) ?>;
        
        if (monthlyData.length > 0) {
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
        } else {
            // Show empty state for chart
            ctx.parentElement.innerHTML = '<p class="text-muted text-center small py-4">No submission data available</p>';
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>