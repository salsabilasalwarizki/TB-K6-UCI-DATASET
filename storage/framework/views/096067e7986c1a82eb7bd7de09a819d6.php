
<?php $__env->startSection('title', 'Donate Dataset - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>

<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">
                We offer users the option to upload their dataset data to our repository.
            </p>
            <p class="page-description">
                Users can provide tabular or non-tabular dataset data which will be made publicly available on our repository. 
                Donators are free to edit their donated datasets, but edits must be approved before finalizing.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 14.28%"></div>
            </div>
            <span class="progress-text">Page 1 / 7</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.metadata.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Basic Info Section -->
            <div class="form-card">
                <h5 class="card-section-title">Basic Info</h5>
                
                <div class="form-group">
                    <label for="name" class="form-label">Dataset Name <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="<?php echo e(old('name')); ?>" 
                           required>
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
                              maxlength="1000"><?php echo e(old('abstract')); ?></textarea>
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
                           min="0">
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
                           min="0">
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
                           value="<?php echo e(old('doi')); ?>">
                    <div class="form-hint">If a DOI is not provided, one will be generated for the dataset.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Graphics <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Upload a representative image for your dataset"></i></label>
                    <div class="upload-box" onclick="document.getElementById('graphics').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                    </div>
                    <input type="file" id="graphics" name="graphics" class="d-none" accept="image/*">
                    <div id="graphics-preview" class="mt-2"></div>
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
                    NEXT
                </button>
            </div>
        </form>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    
    .page-title {
        padding-top : 50px;
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
    
    /* Footer UCI */
    .uci-footer {
        background-color: #0077b6;
        color: white;
        padding: 3rem 0 2rem;
        margin-top: 4rem;
    }
    
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 2rem;
    }
    
    .footer-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .footer-logo {
        width: 60px;
        height: 60px;
    }
    
    .footer-brand-text {
        font-size: 0.85rem;
        line-height: 1.4;
        font-weight: 600;
    }
    
    .footer-col h6 {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    
    .footer-col a {
        display: block;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        transition: color 0.2s;
    }
    
    .footer-col a:hover {
        color: white;
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
        
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .footer-brand {
            flex-direction: column;
            text-align: center;
        }
        
        .footer-col {
            text-align: center;
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
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('graphics-preview').innerHTML = 
                '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; border-radius: 8px; margin-top: 1rem;">';
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/metadata.blade.php ENDPATH**/ ?>