
<?php $__env->startSection('title', $dataset->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('profile.datasets')); ?>">My Datasets</a></li>
            <li class="breadcrumb-item active"><?php echo e(Str::limit($dataset->name, 50)); ?></li>
        </ol>
    </nav>

    <!-- Dataset Header -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%); color: white;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-database display-6"></i>
                        <h1 class="h3 mb-0"><?php echo e($dataset->name); ?></h1>
                    </div>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar me-1"></i>
                        Donated on <?php echo e($dataset->donated_date?->format('n/j/Y') ?? 'N/A'); ?>

                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-light btn-sm me-2" type="button" data-bs-toggle="tooltip" title="Edit dataset">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input" type="checkbox" id="visibilityToggle" <?php echo e($dataset->is_public ?? false ? 'checked' : ''); ?>>
                        <label class="form-check-label text-white" for="visibilityToggle">
                            <?php echo e($dataset->is_public ?? false ? 'Public' : 'Private'); ?>

                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-text"><?php echo e($dataset->description); ?></p>
                    <?php if(strlen($dataset->description) > 200): ?>
                    <button class="btn btn-link btn-sm p-0" type="button" data-bs-toggle="collapse" data-bs-target="#fullDescription">
                        Show more
                    </button>
                    <div class="collapse" id="fullDescription">
                        <p class="mt-2"><?php echo e($dataset->description); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dataset Characteristics Grid -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Dataset Characteristics</h6>
                            <p class="mb-0"><?php echo e($dataset->characteristics ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Subject Area</h6>
                            <p class="mb-0"><?php echo e($dataset->subjectArea->area_name ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Associated Tasks</h6>
                            <p class="mb-0"><?php echo e($dataset->task->task_name ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Feature Type</h6>
                            <p class="mb-0"><?php echo e($dataset->feature_type ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Instances</h6>
                            <p class="mb-0"><?php echo e(number_format($dataset->num_instances ?? 0)); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Features</h6>
                            <p class="mb-0"><?php echo e(number_format($dataset->num_features ?? 0)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dataset Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#datasetInfo">
                            Dataset Information <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="datasetInfo" class="collapse show">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Has Missing Values?</strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($dataset->has_missing_values ? 'Yes' : 'No'); ?>

                            </div>
                        </div>
                        <?php if(!empty($descriptiveInfo)): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Purpose</strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($descriptiveInfo['purpose'] ?? 'N/A'); ?>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Instances Represent</strong>
                            </div>
                            <div class="col-md-6">
                                <?php echo e($descriptiveInfo['instances_represent'] ?? 'N/A'); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Introductory Paper -->
            <?php if($dataset->papers->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#paperSection">
                            Introductory Paper <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="paperSection" class="collapse show">
                    <div class="card-body">
                        <?php $__currentLoopData = $dataset->papers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <h6>
                                <a href="<?php echo e($paper->paper_url ?? '#'); ?>" target="_blank" class="text-decoration-none">
                                    <?php echo e($paper->title); ?>

                                </a>
                            </h6>
                            <p class="mb-1 text-muted">By <?php echo e($paper->authors); ?></p>
                            <p class="mb-0 small">Published in <?php echo e($paper->venue ?? 'N/A'); ?>, <?php echo e($paper->publication_year); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dataset Files -->
            <?php if($dataset->files->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#filesSection">
                            Dataset Files <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="filesSection" class="collapse show">
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Size</th>
                                    <th>Format</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataset->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($file->original_filename); ?></td>
                                    <td><?php echo e($file->file_size); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo e(strtoupper($file->file_format)); ?></span></td>
                                    <td>
                                        <a href="<?php echo e(route('datasets.download', [$dataset, $file])); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Variables -->
            <?php if($dataset->variables->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#variablesSection">
                            Variables (<?php echo e($dataset->variables->count()); ?>) <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="variablesSection" class="collapse show">
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataset->variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($var->variable_name); ?></strong></td>
                                    <td><span class="badge bg-info"><?php echo e($var->role); ?></span></td>
                                    <td><?php echo e($var->type); ?></td>
                                    <td><?php echo e($var->description ?? '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-download me-2"></i>DOWNLOAD (<?php echo e($dataset->files->sum('file_size_bytes') ? number_format($dataset->files->sum('file_size_bytes') / 1024, 1) . ' KB' : 'N/A'); ?>)
                    </button>
                    <button class="btn btn-warning w-100 mb-3">
                        <i class="bi bi-quote me-2"></i>CITE
                    </button>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-chat-quote me-2"></i><?php echo e($totalCitations); ?> citations</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-eye me-2"></i><?php echo e($totalViews); ?> views</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DOI -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">DOI</h6>
                </div>
                <div class="card-body">
                    <?php if($dataset->doi): ?>
                    <a href="<?php echo e($dataset->doi->resolution_url); ?>" target="_blank" class="text-decoration-none">
                        <?php echo e($dataset->doi->doi_string); ?>

                    </a>
                    <?php else: ?>
                    <span class="text-muted">0</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- License -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">License</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 small">
                        This dataset is licensed under a 
                        <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">
                            Creative Commons Attribution 4.0 International
                        </a> (CC BY 4.0) license.
                    </p>
                    <p class="mb-0 small text-muted">
                        This allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given.
                    </p>
                </div>
            </div>

            <!-- Creators -->
            <?php if($dataset->creators && $dataset->creators->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Creators (<?php echo e($dataset->creators->count()); ?>)</h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $dataset->creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $creator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-2">
                        <strong><?php echo e($creator->name); ?></strong>
                        <?php if($creator->pivot->contribution_role): ?>
                        <span class="badge bg-secondary ms-1"><?php echo e($creator->pivot->contribution_role); ?></span>
                        <?php endif; ?>
                        <?php if($creator->affiliation): ?>
                        <div class="small text-muted"><?php echo e($creator->affiliation); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Submission Status</h6>
                </div>
                <div class="card-body">
                    <?php
                        $status = $dataset->status ?? 'pending';
                        $statusClass = [
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger'
                        ][$status] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo e($statusClass); ?> fs-6">
                        <?php echo e(strtoupper($status)); ?>

                    </span>
                    <?php if($dataset->admin_notes): ?>
                    <div class="mt-2 small text-muted">
                        <strong>Admin Notes:</strong><br>
                        <?php echo e($dataset->admin_notes); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Visibility toggle
document.getElementById('visibilityToggle')?.addEventListener('change', function() {
    const isPublic = this.checked;
    // Make AJAX call to update visibility
    fetch('<?php echo e(route("profile.dataset.update-visibility", $dataset)); ?>', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ is_public: isPublic })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/profile/dataset-detail.blade.php ENDPATH**/ ?>