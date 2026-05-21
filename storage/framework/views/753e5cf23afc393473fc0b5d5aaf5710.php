
<?php $__env->startSection('title', 'UCI Machine Learning Repository'); ?>
<?php $__env->startSection('meta_desc', 'A collection of databases for empirical analysis of machine learning algorithms'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Hero Banner -->
    <section class="hero-banner py-5" style="background: linear-gradient(135deg, #0077b6 0%, #005f73 100%); color: white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">
                        Welcome to the UC Irvine Machine Learning Repository
                    </h1>
                    <p class="lead mb-4 opacity-90">
                        We currently maintain <strong><?php echo e(number_format($stats['total'] ?? 0)); ?></strong> datasets 
                        as a service to the machine learning community. Here, you can donate and find datasets 
                        used by millions of people all around the world!
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-light btn-lg fw-semibold">
                            <i class="bi bi-grid-3x3-gap me-2"></i>VIEW DATASETS
                        </a>
                        <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-outline-light btn-lg fw-semibold">
                            <i class="bi bi-upload me-2"></i>CONTRIBUTE
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="bi bi-database display-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar py-3 bg-light border-bottom">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-primary fs-4">
                            <?php echo e(number_format($stats['total'] ?? 0)); ?>

                        </div>
                        <div class="stat-label text-muted small">Total Datasets</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-success fs-4">
                            <?php echo e(number_format($stats['by_data_type']->sum(fn($v) => $v) ?? 0)); ?>

                        </div>
                        <div class="stat-label text-muted small">Data Types</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-info fs-4">
                            <?php echo e(number_format($stats['by_task_type']->sum(fn($v) => $v) ?? 0)); ?>

                        </div>
                        <div class="stat-label text-muted small">Task Types</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-warning fs-4">
                            <?php echo e(number_format(($stats['recent_downloads'] ?? collect())->sum('download_count'))); ?>

                        </div>
                        <div class="stat-label text-muted small">Downloads</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        <?php if($popularDatasets->isNotEmpty() || $newDatasets->isNotEmpty()): ?>
            <div class="row g-4">
                
                <!-- Popular Datasets -->
                <?php if($popularDatasets->isNotEmpty()): ?>
                <div class="col-12">
                    <div class="section-header d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">
                            <i class="bi bi-fire text-danger me-2"></i>Popular Datasets
                        </h2>
                        <a href="<?php echo e(route('datasets.index', ['sort' => 'view_count', 'order' => 'desc'])); ?>" 
                           class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="row g-3">
                        <?php $__currentLoopData = $popularDatasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-3">
                            <?php echo $__env->make('components.dataset-card-mini', ['dataset' => $dataset, 'showStats' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- New Datasets -->
                <?php if($newDatasets->isNotEmpty()): ?>
                <div class="col-12">
                    <div class="section-header d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>New Datasets
                        </h2>
                        <a href="<?php echo e(route('datasets.index', ['sort' => 'created_at', 'order' => 'desc'])); ?>" 
                           class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="row g-3">
                        <?php $__currentLoopData = $newDatasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-3">
                            <?php echo $__env->make('components.dataset-card-mini', ['dataset' => $dataset, 'showStats' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5 my-5">
                <div class="mb-4">
                    <i class="bi bi-database display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted mb-3">No datasets available yet</h4>
                <p class="text-muted mb-4">Be the first to contribute a dataset to the repository!</p>
                <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Donate a Dataset
                </a>
            </div>
        <?php endif; ?>

        <!-- Browse by Category -->
        <section class="browse-categories mt-5 pt-4 border-top">
            <h3 class="mb-4">Browse by Category</h3>
            <div class="row g-3">
                <!-- Data Types -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-diagram-3 me-2"></i>Data Types
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = ($stats['by_data_type'] ?? collect())->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($type): ?>
                                <a href="<?php echo e(route('datasets.index', ['data_type' => $type])); ?>" 
                                   class="badge bg-light text-dark border text-decoration-none d-flex align-items-center gap-1">
                                    <?php echo e($type); ?>

                                    <span class="badge bg-secondary rounded-pill ms-1"><?php echo e($count); ?></span>
                                </a>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Types -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-search me-2"></i>Task Types
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = ($stats['by_task_type'] ?? collect())->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($task): ?>
                                <a href="<?php echo e(route('datasets.index', ['task_type' => $task])); ?>" 
                                   class="badge bg-light text-dark border text-decoration-none d-flex align-items-center gap-1">
                                    <?php echo e($task); ?>

                                    <span class="badge bg-secondary rounded-pill ms-1"><?php echo e($count); ?></span>
                                </a>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .hero-banner {
        position: relative;
        overflow: hidden;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    
    .stats-bar .stat-value {
        line-height: 1;
    }
    
    .stats-bar .stat-label {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    .section-title {
        font-weight: 700;
        color: var(--bs-body-color);
    }
    
    .section-header {
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bs-border-color);
    }
    
    .browse-categories .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .browse-categories .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/home.blade.php ENDPATH**/ ?>