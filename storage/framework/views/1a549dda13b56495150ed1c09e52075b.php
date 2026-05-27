
<?php $__env->startSection('title', 'Statistics'); ?>
<?php $__env->startSection('page-title', 'Platform Statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4">Statistics Dashboard</h2>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary"><?php echo e($stats['total_datasets']); ?></h3>
                    <p class="mb-0">Total Datasets</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success"><?php echo e($stats['total_users']); ?></h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info"><?php echo e($stats['total_papers']); ?></h3>
                    <p class="mb-0">Total Papers</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/admin/statistics.blade.php ENDPATH**/ ?>