<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['dataset', 'showStats' => true]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['dataset', 'showStats' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card h-100 dataset-card-mini hover-lift">
    <div class="card-body">
        <!-- Thumbnail / Icon -->
        <div class="text-center mb-3">
            <?php if($dataset->thumbnail_url): ?>
                <img src="<?php echo e($dataset->thumbnail_url); ?>" 
                     alt="<?php echo e($dataset->display_name ?? $dataset->name); ?>" 
                     class="img-fluid rounded" 
                     style="max-height: 80px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-database fs-3"></i>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Title -->
        <h6 class="card-title fw-semibold mb-2 text-center">
            <a href="<?php echo e(route('datasets.show', [$dataset, $dataset->slug])); ?>" 
               class="text-decoration-none text-dark stretched-link">
                <?php echo e($dataset->display_name ?? $dataset->name); ?>

            </a>
        </h6>
        <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['dataset', 'showStats' => true, 'showBadge' => false, 'badgeText' => '', 'badgeVariant' => 'primary']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['dataset', 'showStats' => true, 'showBadge' => false, 'badgeText' => '', 'badgeVariant' => 'primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Di dalam card, tambahkan badge conditional -->
<?php if($showBadge && $badgeText): ?>
<span class="badge bg-<?php echo e($badgeVariant); ?> position-absolute top-0 end-0 m-2">
    <?php echo e($badgeText); ?>

</span>
<?php endif; ?>
        <!-- Badges: Data Type & Task Type (langsung dari field ENUM) -->
        <div class="d-flex justify-content-center gap-1 mb-2">
            <?php if($dataset->data_type): ?>
                <span class="badge bg-info text-dark" title="Data Type"><?php echo e($dataset->data_type); ?></span>
            <?php endif; ?>
            <?php if($dataset->task_type): ?>
                <span class="badge bg-success" title="Task Type"><?php echo e($dataset->task_type); ?></span>
            <?php endif; ?>
        </div>
        
        <!-- Description -->
        <p class="card-text small text-muted text-center mb-3">
            <?php echo e(Str::limit($dataset->description ?? $dataset->abstract, 80)); ?>

        </p>
        
        <!-- Stats (optional) -->
        <?php if($showStats): ?>
        <div class="d-flex justify-content-center gap-3 small text-muted mb-3">
            <?php if($dataset->num_instances !== null): ?>
                <span title="Instances">
                    <i class="bi bi-table me-1"></i>
                    <?php echo e($dataset->num_instances >= 1000000 ? number_format($dataset->num_instances / 1000000, 1) . 'M' : number_format($dataset->num_instances)); ?>

                </span>
            <?php endif; ?>
            <?php if($dataset->num_features !== null): ?>
                <span title="Features">
                    <i class="bi bi-grid-3x3-gap me-1"></i><?php echo e(number_format($dataset->num_features)); ?>

                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Subject Area (field VARCHAR langsung) -->
        <?php if($dataset->subject_area): ?>
            <div class="text-center mb-3">
                <small class="text-muted">
                    <i class="bi bi-folder me-1"></i><?php echo e($dataset->subject_area); ?>

                </small>
            </div>
        <?php endif; ?>
        
        <!-- Quick Action -->
        <div class="text-center">
            <a href="<?php echo e(route('datasets.show', [$dataset, $dataset->slug])); ?>" 
               class="btn btn-sm btn-outline-primary stretched-link">
                View Details
            </a>
        </div>
    </div>
    
    <!-- Hover overlay for link -->
    <div class="card-overlay"></div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .dataset-card-mini {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--bs-border-color);
    }
    
    .hover-lift {
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-color: var(--uci-blue, #0077b6);
    }
    
    .card-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: linear-gradient(to top, rgba(0,119,182,0.03), transparent);
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .hover-lift:hover .card-overlay {
        opacity: 1;
    }
    
    .stretched-link::after {
        z-index: 1;
    }
</style>
<?php $__env->stopPush(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/components/dataset-card-mini.blade.php ENDPATH**/ ?>