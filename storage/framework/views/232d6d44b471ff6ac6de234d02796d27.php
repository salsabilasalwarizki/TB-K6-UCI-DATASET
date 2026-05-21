
<?php $__env->startSection('title', 'Your Donated Datasets'); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <a href="<?php echo e(route('profile')); ?>" class="nav-link">
            <i class="bi bi-person-fill"></i> Profile
        </a>
        <a href="<?php echo e(route('profile.datasets')); ?>" class="nav-link active">
            <i class="bi bi-grid-3x3-gap-fill"></i> Datasets
        </a>
        <a href="<?php echo e(route('profile.edits')); ?>" class="nav-link">
            <i class="bi bi-pencil-fill"></i> Edits
        </a>
    </aside>

    <!-- Main Content -->
    <div class="profile-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-database me-2 text-primary"></i>
                Your Donated Datasets
            </h2>
            <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Donate New Dataset
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($datasets->isEmpty()): ?>
            <!-- Empty State -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-database display-1 text-muted mb-4 d-block"></i>
                    <h4 class="text-muted mb-3">No donations yet</h4>
                    <p class="text-muted mb-4">
                        You haven't donated any datasets yet. 
                        Start contributing to the UCI Machine Learning Repository!
                    </p>
                    <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-upload me-2"></i>Donate a Dataset
                    </a>
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Your datasets will appear here after submission
                        </small>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Filters & Sorting -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sortOptions">
                                <i class="bi bi-funnel me-2"></i>SORT BY DATE DONATED, DESC
                            </button>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="filterApproved" checked>
                                    <label class="form-check-label" for="filterApproved">APPROVED</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="filterPending" checked>
                                    <label class="form-check-label" for="filterPending">PENDING</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="filterRejected" checked>
                                    <label class="form-check-label" for="filterRejected">REJECTED</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datasets Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Dataset Name</th>
                                    <th>Date Donated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('profile.dataset.show', $dataset)); ?>" class="text-decoration-none">
                                            <?php echo e($dataset->name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($dataset->donated_date?->format('n/j/Y') ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                            $status = $dataset->status ?? 'pending';
                                            $statusClass = [
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger'
                                            ][$status] ?? 'secondary';
                                            
                                            $statusIcon = [
                                                'approved' => 'check-circle-fill',
                                                'pending' => 'exclamation-circle-fill',
                                                'rejected' => 'x-circle-fill'
                                            ][$status] ?? 'question-circle-fill';
                                        ?>
                                        <span class="badge bg-<?php echo e($statusClass); ?> d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-<?php echo e($statusIcon); ?>"></i>
                                            <?php echo e(strtoupper($status)); ?>

                                        </span>
                                    </td>
                                    <td>
    <div class="btn-group btn-group-sm">
        
        <a href="<?php echo e(route('profile.dataset.show', $dataset)); ?>" 
           class="btn btn-outline-primary" 
           title="View Details">
            <i class="bi bi-eye"></i>
        </a>
        
        
        <?php
            $primaryFile = $dataset->files->first();
        ?>
        
        <?php if($primaryFile): ?>
            <a href="<?php echo e(route('datasets.download', [$dataset, $primaryFile])); ?>" 
               class="btn btn-outline-success" 
               title="Download">
                <i class="bi bi-download"></i>
            </a>
        <?php else: ?>
            <button class="btn btn-outline-secondary" 
                    title="No files (External Link)" 
                    disabled>
                <i class="bi bi-download"></i>
            </button>
        <?php endif; ?>
    </div>
</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($datasets->hasPages()): ?>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">Rows per page:</span>
                                <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                                    <option value="<?php echo e($datasets->url(1)); ?>" <?php echo e($datasets->perPage() == 5 ? 'selected' : ''); ?>>5</option>
                                    <option value="<?php echo e($datasets->url(1)); ?>" <?php echo e($datasets->perPage() == 10 ? 'selected' : ''); ?>>10</option>
                                    <option value="<?php echo e($datasets->url(1)); ?>" <?php echo e($datasets->perPage() == 20 ? 'selected' : ''); ?>>20</option>
                                </select>
                                <span class="small text-muted"><?php echo e($datasets->firstItem() ?? 0); ?> to <?php echo e($datasets->lastItem() ?? 0); ?> of <?php echo e($datasets->total()); ?></span>
                            </div>
                            <div>
                                <?php echo e($datasets->links()); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .profile-container {
        display: flex;
        min-height: calc(100vh - 70px);
    }
    
    .profile-sidebar {
        width: 220px;
        background-color: #fff;
        border-right: 1px solid #e0e0e0;
        padding: 2rem 0;
    }
    
    .profile-sidebar .nav-link {
        color: var(--uci-text);
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        text-decoration: none;
    }
    
    .profile-sidebar .nav-link:hover {
        background-color: var(--uci-light-blue);
        color: var(--uci-blue);
    }
    
    .profile-sidebar .nav-link.active {
        background-color: var(--uci-yellow);
        color: #000;
        font-weight: 700;
    }
    
    .profile-content {
        flex: 1;
        padding: 2rem;
        background-color: #fafafa;
    }
    
    .badge {
        padding: 0.5em 0.8em;
        font-size: 0.85em;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/profile/datasets.blade.php ENDPATH**/ ?>