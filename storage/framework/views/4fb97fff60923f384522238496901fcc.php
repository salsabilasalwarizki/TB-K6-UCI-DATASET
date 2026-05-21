
<?php $__env->startSection('title', 'Edit Dataset Metadata - UCI ML Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Dataset Metadata
                    </h2>
                    <p class="text-muted mb-0">Update information for your approved dataset</p>
                </div>
                <a href="<?php echo e(route('profile.edits')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Edits
                </a>
            </div>

            <!-- Alert Info -->
            <div class="alert alert-info d-flex align-items-start mb-4">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>
                    <strong>Important Notice:</strong>
                    <p class="mb-0">Changes to approved datasets will be reviewed by admins before going live. 
                    Your dataset status will change to "pending" after submission.</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('contribute.edit.metadata.update', $dataset)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Dataset Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Dataset Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo e(old('name', $dataset->name)); ?>" 
                                   required
                                   placeholder="Enter dataset name">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Use a clear, descriptive name for your dataset</div>
                        </div>

                        <!-- Abstract/Description -->
                        <div class="mb-4">
                            <label for="abstract" class="form-label fw-semibold">
                                Abstract <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['abstract'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="abstract" 
                                      name="abstract" 
                                      rows="5" 
                                      required
                                      placeholder="Provide a concise summary of the dataset"><?php echo e(old('abstract', $dataset->abstract)); ?></textarea>
                            <?php $__errorArgs = ['abstract'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Brief description (max 2000 characters)</div>
                        </div>

                        <!-- Detailed Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Detailed Description
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="6"
                                      placeholder="Provide comprehensive details about the dataset"><?php echo e(old('description', $dataset->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Subject Area & Data Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_area" class="form-label fw-semibold">
                                    Subject Area
                                </label>
                                <select class="form-select <?php $__errorArgs = ['subject_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="subject_area" 
                                        name="subject_area">
                                    <option value="">Select subject area</option>
                                    <option value="Biology" <?php echo e(old('subject_area', $dataset->subject_area) == 'Biology' ? 'selected' : ''); ?>>Biology</option>
                                    <option value="Computer Science" <?php echo e(old('subject_area', $dataset->subject_area) == 'Computer Science' ? 'selected' : ''); ?>>Computer Science</option>
                                    <option value="Medicine" <?php echo e(old('subject_area', $dataset->subject_area) == 'Medicine' ? 'selected' : ''); ?>>Medicine</option>
                                    <option value="Engineering" <?php echo e(old('subject_area', $dataset->subject_area) == 'Engineering' ? 'selected' : ''); ?>>Engineering</option>
                                    <option value="Social Sciences" <?php echo e(old('subject_area', $dataset->subject_area) == 'Social Sciences' ? 'selected' : ''); ?>>Social Sciences</option>
                                    <option value="Business" <?php echo e(old('subject_area', $dataset->subject_area) == 'Business' ? 'selected' : ''); ?>>Business</option>
                                    <option value="Other" <?php echo e(old('subject_area', $dataset->subject_area) == 'Other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <?php $__errorArgs = ['subject_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="data_type" class="form-label fw-semibold">
                                    Data Type
                                </label>
                                <select class="form-select <?php $__errorArgs = ['data_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="data_type" 
                                        name="data_type">
                                    <option value="">Select data type</option>
                                    <option value="Multivariate" <?php echo e(old('data_type', $dataset->data_type) == 'Multivariate' ? 'selected' : ''); ?>>Multivariate</option>
                                    <option value="Univariate" <?php echo e(old('data_type', $dataset->data_type) == 'Univariate' ? 'selected' : ''); ?>>Univariate</option>
                                    <option value="Sequential" <?php echo e(old('data_type', $dataset->data_type) == 'Sequential' ? 'selected' : ''); ?>>Sequential</option>
                                    <option value="Time-Series" <?php echo e(old('data_type', $dataset->data_type) == 'Time-Series' ? 'selected' : ''); ?>>Time-Series</option>
                                    <option value="Text" <?php echo e(old('data_type', $dataset->data_type) == 'Text' ? 'selected' : ''); ?>>Text</option>
                                    <option value="Image" <?php echo e(old('data_type', $dataset->data_type) == 'Image' ? 'selected' : ''); ?>>Image</option>
                                    <option value="Tabular" <?php echo e(old('data_type', $dataset->data_type) == 'Tabular' ? 'selected' : ''); ?>>Tabular</option>
                                </select>
                                <?php $__errorArgs = ['data_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Task Type -->
                        <div class="mb-4">
                            <label for="task_type" class="form-label fw-semibold">
                                Associated Task
                            </label>
                            <select class="form-select <?php $__errorArgs = ['task_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="task_type" 
                                    name="task_type">
                                <option value="">Select task type</option>
                                <option value="Classification" <?php echo e(old('task_type', $dataset->task_type) == 'Classification' ? 'selected' : ''); ?>>Classification</option>
                                <option value="Regression" <?php echo e(old('task_type', $dataset->task_type) == 'Regression' ? 'selected' : ''); ?>>Regression</option>
                                <option value="Clustering" <?php echo e(old('task_type', $dataset->task_type) == 'Clustering' ? 'selected' : ''); ?>>Clustering</option>
                                <option value="Causal Discovery" <?php echo e(old('task_type', $dataset->task_type) == 'Causal Discovery' ? 'selected' : ''); ?>>Causal Discovery</option>
                                <option value="Other" <?php echo e(old('task_type', $dataset->task_type) == 'Other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <?php $__errorArgs = ['task_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Number of Instances & Features -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="num_instances" class="form-label fw-semibold">
                                    Number of Instances
                                </label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['num_instances'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="num_instances" 
                                       name="num_instances" 
                                       value="<?php echo e(old('num_instances', $dataset->num_instances)); ?>"
                                       min="0"
                                       placeholder="e.g., 150">
                                <?php $__errorArgs = ['num_instances'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="num_features" class="form-label fw-semibold">
                                    Number of Features
                                </label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['num_features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="num_features" 
                                       name="num_features" 
                                       value="<?php echo e(old('num_features', $dataset->num_features)); ?>"
                                       min="0"
                                       placeholder="e.g., 4">
                                <?php $__errorArgs = ['num_features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Current Status Badge -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Status</label>
                            <div>
                                <span class="badge bg-<?php echo e($dataset->status === 'approved' ? 'success' : 'info'); ?> fs-6">
                                    <?php echo e(ucfirst($dataset->status)); ?>

                                </span>
                                <small class="text-muted ms-2">
                                    Last updated: <?php echo e($dataset->updated_at?->diffForHumans() ?? 'N/A'); ?>

                                </small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="<?php echo e(route('profile.edits')); ?>" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Submit for Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0077b6;
        box-shadow: 0 0 0 0.2rem rgba(0, 119, 182, 0.25);
    }
    
    .card {
        border-radius: 12px;
    }
    
    .form-label {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/edit/metadata.blade.php ENDPATH**/ ?>