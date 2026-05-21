
<?php $__env->startSection('title', 'Link External Dataset - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>

<div class="linking-page">
    <div class="container">
        <!-- Header -->
        <div class="linking-header">
            <h1 class="page-title">Dataset Linking Form</h1>
            <p class="page-description">
                We offer users the option to list a dataset in our dataset index without actually hosting the data itself in our repository.
            </p>
            <p class="page-description">
                Instead, users can provide a link to an external webpage from which the dataset can be downloaded. Linking a dataset in 
                our repository can help increase the dataset's visibility and also allows users to use our dataset filtering and search 
                capabilities to identify if the dataset is useful for them.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 16.67%"></div>
            </div>
            <span class="progress-text">Page 1 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.metadata.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Basic Info Section -->
            <div class="form-card">
                <h5 class="card-section-title">Basic Info</h5>
                
                <div class="form-group">
                    <label for="external_url" class="form-label">Dataset URL <span class="required">*</span></label>
                    <input type="url" 
                           class="form-control" 
                           id="external_url" 
                           name="external_url" 
                           value="<?php echo e(old('external_url')); ?>" 
                           required
                           placeholder="https://example.com/dataset">
                    <div class="form-hint">The direct link to the dataset or its download page.</div>
                    <?php $__errorArgs = ['external_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Dataset Name <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="<?php echo e(old('name')); ?>" 
                           required
                           placeholder="e.g., Iris Dataset">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="abstract" class="form-label">Abstract <span class="required">*</span></label>
                    <textarea class="form-control" 
                              id="abstract" 
                              name="abstract" 
                              rows="4" 
                              required
                              maxlength="1000"
                              placeholder="Provide a detailed description of the dataset..."><?php echo e(old('abstract')); ?></textarea>
                    <div class="form-hint">Maximum 1000 characters.</div>
                    <?php $__errorArgs = ['abstract'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="num_instances" class="form-label">Number of Instances (Rows) in Dataset <span class="required">*</span></label>
                    <input type="number" 
                           class="form-control" 
                           id="num_instances" 
                           name="num_instances" 
                           value="<?php echo e(old('num_instances')); ?>" 
                           required 
                           min="0"
                           placeholder="e.g., 150">
                    <?php $__errorArgs = ['num_instances'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="num_features" class="form-label">Number of Features in Dataset</label>
                    <input type="number" 
                           class="form-control" 
                           id="num_features" 
                           name="num_features" 
                           value="<?php echo e(old('num_features')); ?>" 
                           min="0"
                           placeholder="e.g., 4">
                    <?php $__errorArgs = ['num_features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="doi" class="form-label">Dataset DOI</label>
                    <input type="text" 
                           class="form-control" 
                           id="doi" 
                           name="doi" 
                           value="<?php echo e(old('doi')); ?>"
                           placeholder="e.g., 10.24433/CO.1234567.v1">
                    <div class="form-hint">Digital Object Identifier for the dataset (if available).</div>
                </div>

                <!-- Graphics Upload Section -->
                <div class="form-group">
                    <label class="form-label">
                        Graphics 
                        <i class="bi bi-info-circle tooltip-icon" 
                           data-bs-toggle="tooltip" 
                           title="Upload a representative image for your dataset"></i>
                    </label>
                    <div class="upload-box" onclick="document.getElementById('graphics').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">PNG, JPG, GIF (max 5MB)</small>
                    </div>
                    <input type="file" id="graphics" name="graphics" class="d-none" accept="image/*">
                    <div id="graphics-preview" class="mt-2"></div>
                    <?php $__errorArgs = ['graphics'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Dataset Characteristics Section -->
            <div class="form-card">
                <h5 class="card-section-title">Dataset Characteristics <span class="required">*</span></h5>
                
                <?php
                    $characteristics = ['Tabular', 'Sequential', 'Multivariate', 'Time-Series', 'Text', 'Image', 'Spatiotemporal', 'Other'];
                ?>
                
                <?php $__currentLoopData = $characteristics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $char): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="checkbox-item">
                    <span class="checkbox-label"><?php echo e($char); ?></span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="characteristics[]" 
                           value="<?php echo e($char); ?>" 
                           id="char_<?php echo e($char); ?>"
                           <?php echo e(in_array($char, old('characteristics', [])) ? 'checked' : ''); ?>>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php $__errorArgs = ['characteristics'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Subject Area Section -->
            <div class="form-card">
                <h5 class="card-section-title">Subject Area <span class="required">*</span></h5>
                
                <?php
                    $subjectAreas = [
                        'Biology',
                        'Business',
                        'Climate and Environment',
                        'Computer Science',
                        'Engineering',
                        'Games',
                        'Health and Medicine',
                        'Law',
                        'Physics and Chemistry',
                        'Social Science',
                        'Other'
                    ];
                ?>
                
                <?php $__currentLoopData = $subjectAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="radio-item">
                    <span class="radio-label"><?php echo e($area); ?></span>
                    <input type="radio" 
                           class="custom-radio" 
                           name="subject_area" 
                           value="<?php echo e($area); ?>" 
                           id="area_<?php echo e($loop->index); ?>"
                           <?php echo e(old('subject_area') == $area ? 'checked' : ''); ?>>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php $__errorArgs = ['subject_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Associated Tasks Section -->
            <div class="form-card">
                <h5 class="card-section-title">Associated Tasks <span class="required">*</span></h5>
                
                <?php
                    $tasks = ['Classification', 'Regression', 'Clustering', 'Other'];
                ?>
                
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="checkbox-item">
                    <span class="checkbox-label"><?php echo e($task); ?></span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="associated_tasks[]" 
                           value="<?php echo e($task); ?>" 
                           id="task_<?php echo e($task); ?>"
                           <?php echo e(in_array($task, old('associated_tasks', [])) ? 'checked' : ''); ?>>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php $__errorArgs = ['associated_tasks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Feature Types Section -->
            <div class="form-card">
                <h5 class="card-section-title">Feature Types</h5>
                
                <?php
                    $featureTypes = ['Real', 'Categorical', 'Integer'];
                ?>
                
                <?php $__currentLoopData = $featureTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ftype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="checkbox-item">
                    <span class="checkbox-label"><?php echo e($ftype); ?></span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="feature_types[]" 
                           value="<?php echo e($ftype); ?>" 
                           id="ftype_<?php echo e($ftype); ?>"
                           <?php echo e(in_array($ftype, old('feature_types', [])) ? 'checked' : ''); ?>>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <button type="submit" class="btn-next">
                    NEXT <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .linking-page {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .page-title {
        padding-top: 50px;
        color: #0077b6;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .page-description {
        color: #555;
        line-height: 1.7;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .progress-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    
    .progress {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-text {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
    }
    
    .form-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }
    
    .card-section-title {
        color: #0077b6;
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
    }
    
    .required {
        color: #dc3545;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-hint {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.4rem;
    }
    
    .upload-box {
        border: 2px dashed #0077b6;
        border-radius: 8px;
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f8f9fa;
    }
    
    .upload-box:hover {
        background-color: #e9f5f9;
        border-color: #005f73;
    }
    
    .upload-icon {
        font-size: 2.5rem;
        color: #0077b6;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .upload-text {
        margin: 0;
        color: #333;
        font-size: 0.95rem;
    }
    
    #graphics-preview img {
        max-width: 200px;
        border-radius: 8px;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .checkbox-item, .radio-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .checkbox-item:last-child, .radio-item:last-child {
        border-bottom: none;
    }
    
    .checkbox-label, .radio-label {
        font-size: 0.95rem;
        color: #333;
    }
    
    .custom-checkbox, .custom-radio {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #0077b6;
    }
    
    .tooltip-icon {
        color: #0077b6;
        cursor: help;
        font-size: 0.9rem;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }
    
    .form-navigation {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
    .btn-next {
        background-color: #0077b6;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-next:hover {
        background-color: #005f73;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem 1rem;
        }
        
        .form-card {
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Graphics upload preview
document.getElementById('graphics').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            this.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Only JPG, PNG, and GIF files are allowed');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('graphics-preview').innerHTML = 
                '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; border-radius: 8px; margin-top: 1rem;">' +
                '<p class="text-success mt-2 mb-0"><i class="bi bi-check-circle me-1"></i>' + file.name + '</p>';
        };
        reader.readAsDataURL(file);
    }
});

// Tooltip initialization
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/metadata.blade.php ENDPATH**/ ?>