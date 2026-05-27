
<?php $__env->startSection('title', 'Edit Dataset'); ?>
<?php $__env->startSection('page-title', 'Edit Dataset'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Dataset
                    </h2>
                    <p class="text-muted mb-0">Update dataset information</p>
                </div>
                <a href="<?php echo e(route('admin.datasets.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Alert Info -->
            <div class="alert alert-info d-flex align-items-start mb-4">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>
                    <strong>Important Notice:</strong>
                    <p class="mb-0">Changes to this dataset will be saved immediately. Make sure all information is accurate before saving.</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.datasets.update', $dataset)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Basic Information -->
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h5>

                        <!-- Name -->
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
                        </div>

                        <!-- Display Name -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-semibold">
                                Display Name
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="display_name" 
                                   name="display_name" 
                                   value="<?php echo e(old('display_name', $dataset->display_name)); ?>"
                                   placeholder="Display name (optional)">
                            <?php $__errorArgs = ['display_name'];
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

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
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
                                      rows="4" 
                                      required
                                      placeholder="Provide a comprehensive description"><?php echo e(old('description', $dataset->description)); ?></textarea>
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

                        <!-- Abstract -->
                        <div class="mb-4">
                            <label for="abstract" class="form-label fw-semibold">
                                Abstract
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
                                      rows="3"
                                      placeholder="Brief abstract"><?php echo e(old('abstract', $dataset->abstract)); ?></textarea>
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
                        </div>

                        <!-- Subject Area & Data Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_area" class="form-label fw-semibold">
                                    Subject Area
                                </label>
                                <input type="text" 
                                       class="form-select <?php $__errorArgs = ['subject_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="subject_area" 
                                       name="subject_area" 
                                       value="<?php echo e(old('subject_area', $dataset->subject_area)); ?>"
                                       placeholder="e.g., Computer Science">
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

                        <!-- Task Type & Domain -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="task_type" class="form-label fw-semibold">
                                    Task Type
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
                            <div class="col-md-6">
                                <label for="domain" class="form-label fw-semibold">
                                    Domain
                                </label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="domain" 
                                       name="domain" 
                                       value="<?php echo e(old('domain', $dataset->domain)); ?>"
                                       placeholder="e.g., Machine Learning">
                                <?php $__errorArgs = ['domain'];
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

                        <!-- Dataset Statistics -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-bar-chart me-2"></i>Dataset Statistics
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <label for="num_classes" class="form-label fw-semibold">
                                    Number of Classes
                                </label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['num_classes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="num_classes" 
                                       name="num_classes" 
                                       value="<?php echo e(old('num_classes', $dataset->num_classes)); ?>"
                                       min="0"
                                       placeholder="e.g., 3">
                                <?php $__errorArgs = ['num_classes'];
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

                        <!-- Status & Visibility -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-shield-check me-2"></i>Status & Visibility
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="pending" <?php echo e(old('status', $dataset->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="approved" <?php echo e(old('status', $dataset->status) == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="rejected" <?php echo e(old('status', $dataset->status) == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    <option value="available" <?php echo e(old('status', $dataset->status) == 'available' ? 'selected' : ''); ?>>Available</option>
                                    <option value="deprecated" <?php echo e(old('status', $dataset->status) == 'deprecated' ? 'selected' : ''); ?>>Deprecated</option>
                                </select>
                                <?php $__errorArgs = ['status'];
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
                                <label for="has_missing_values" class="form-label fw-semibold">
                                    Has Missing Values
                                </label>
                                <select class="form-select <?php $__errorArgs = ['has_missing_values'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="has_missing_values" 
                                        name="has_missing_values">
                                    <option value="0" <?php echo e(old('has_missing_values', $dataset->has_missing_values) == '0' ? 'selected' : ''); ?>>No</option>
                                    <option value="1" <?php echo e(old('has_missing_values', $dataset->has_missing_values) == '1' ? 'selected' : ''); ?>>Yes</option>
                                </select>
                                <?php $__errorArgs = ['has_missing_values'];
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

                        <!-- Admin Notes -->
                        <div class="mb-4">
                            <label for="admin_notes" class="form-label fw-semibold">
                                Admin Notes
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['admin_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="admin_notes" 
                                      name="admin_notes" 
                                      rows="3"
                                      placeholder="Internal notes for administrators"><?php echo e(old('admin_notes', $dataset->admin_notes)); ?></textarea>
                            <?php $__errorArgs = ['admin_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">These notes are only visible to administrators</div>
                        </div>

                        <!-- URLs -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-link-45deg me-2"></i>External Links
                        </h5>

                        <div class="mb-4">
                            <label for="dataset_url" class="form-label fw-semibold">
                                Dataset URL
                            </label>
                            <input type="url" 
                                   class="form-control <?php $__errorArgs = ['dataset_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="dataset_url" 
                                   name="dataset_url" 
                                   value="<?php echo e(old('dataset_url', $dataset->dataset_url)); ?>"
                                   placeholder="https://...">
                            <?php $__errorArgs = ['dataset_url'];
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

                        <div class="mb-4">
                            <label for="detail_url" class="form-label fw-semibold">
                                Detail URL
                            </label>
                            <input type="url" 
                                   class="form-control <?php $__errorArgs = ['detail_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="detail_url" 
                                   name="detail_url" 
                                   value="<?php echo e(old('detail_url', $dataset->detail_url)); ?>"
                                   placeholder="https://...">
                            <?php $__errorArgs = ['detail_url'];
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

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="<?php echo e(route('admin.datasets.index')); ?>" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Update Dataset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Dataset Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Created</small>
                            <span class="fw-semibold"><?php echo e($dataset->created_at?->format('M d, Y H:i') ?? 'N/A'); ?></span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Last Updated</small>
                            <span class="fw-semibold"><?php echo e($dataset->updated_at?->format('M d, Y H:i') ?? 'N/A'); ?></span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Dataset ID</small>
                            <span class="fw-semibold"><?php echo e($dataset->dataset_id); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-label {
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        padding: 0.6rem 0.8rem;
        font-size: 0.95rem;
    }
    
    .card {
        border-radius: 12px;
    }
    
    .card-body {
        border-radius: 12px;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/admin/datasets/edit.blade.php ENDPATH**/ ?>