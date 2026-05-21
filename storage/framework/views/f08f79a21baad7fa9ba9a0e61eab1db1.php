
<?php $__env->startSection('title', 'Edits - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <a href="<?php echo e(route('profile')); ?>" class="nav-link">
            <i class="bi bi-person-fill"></i> Profile
        </a>
        <a href="<?php echo e(route('profile.datasets')); ?>" class="nav-link">
            <i class="bi bi-grid-3x3-gap-fill"></i> Datasets
        </a>
        <a href="<?php echo e(route('profile.edits')); ?>" class="nav-link active">
            <i class="bi bi-pencil-fill"></i> Edits
        </a>
    </aside>
    
    <!-- Content -->
    <div class="profile-content">
        <div class="section-header">
            <i class="bi bi-pencil-fill"></i>
            <h2>Your Edits</h2>
        </div>
        
        <div class="empty-state">
            <p class="text-muted">You haven't made any edits to datasets yet.</p>
            <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-search me-2"></i>Browse Datasets
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/profile/edits.blade.php ENDPATH**/ ?>