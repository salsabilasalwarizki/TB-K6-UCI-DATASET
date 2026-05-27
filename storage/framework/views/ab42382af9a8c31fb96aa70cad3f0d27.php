<?php $__env->startSection('title', 'Dataset Donation - Final Submission - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 100%"></div>
            </div>
            <span class="progress-text">Page 7 / 7</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.submit')); ?>" method="POST" enctype="multipart/form-data" id="donationForm">
            <?php echo csrf_field(); ?>

        <?php if($errors->any()): ?>
    <div class="alert alert-danger mb-4">
        <strong>⚠️ Form tidak bisa disubmit:</strong>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

            <!-- Descriptive Questions Section -->
            <div class="form-card">
                <h5 class="card-section-title">Descriptive Questions</h5>
                
                <!-- Purpose -->
                <div class="form-group mb-4">
                    <label for="purpose" class="form-label">
                        For what purpose was the dataset created? <span class="required">*</span>
                    </label>
                    <textarea 
                        class="form-control" 
                        id="purpose" 
                        name="purpose" 
                        rows="3"
                        required
                        placeholder="e.g., This dataset was created for research on machine learning algorithms for classification tasks..."><?php echo e(old('purpose', $data['descriptive']['purpose'] ?? '')); ?></textarea>
                </div>

                <!-- Funding -->
                <div class="form-group mb-4">
                    <label for="funding" class="form-label">
                        Who funded the creation of the dataset?
                    </label>
                    <textarea 
                        class="form-control" 
                        id="funding" 
                        name="funding" 
                        rows="2"
                        placeholder="e.g., National Science Foundation (NSF), Google Research, etc."><?php echo e(old('funding', $data['descriptive']['funding'] ?? '')); ?></textarea>
                </div>

                <!-- Instances Representation -->
                <div class="form-group mb-4">
                    <label for="instances_represent" class="form-label">
                        What do the instances in this dataset represent? <span class="required">*</span>
                    </label>
                    <textarea 
                        class="form-control" 
                        id="instances_represent" 
                        name="instances_represent" 
                        rows="2"
                        required
                        placeholder="e.g., documents, photos, people, countries, patients, transactions..."><?php echo e(old('instances_represent', $data['descriptive']['instances_represent'] ?? '')); ?></textarea>
                    <div class="form-hint">
                        e.g. documents, photos, people, countries
                    </div>
                </div>

                <!-- Data Splits -->
                <div class="form-group mb-4">
                    <label for="data_splits" class="form-label">
                        Are there recommended data splits?
                    </label>
                    <textarea 
                        class="form-control" 
                        id="data_splits" 
                        name="data_splits" 
                        rows="2"
                        placeholder="e.g., 70% training, 15% validation, 15% testing"><?php echo e(old('data_splits', $data['descriptive']['data_splits'] ?? '')); ?></textarea>
                    <div class="form-hint">
                        e.g. training, development/validation, testing
                    </div>
                </div>

                <!-- Sensitive Data -->
                <div class="form-group mb-4">
                    <label for="sensitive_data" class="form-label">
                        Does the dataset contain data that might be considered sensitive in any way?
                    </label>
                    <textarea 
                        class="form-control" 
                        id="sensitive_data" 
                        name="sensitive_data" 
                        rows="3"
                        placeholder="Describe any sensitive data present, or state 'None' if not applicable"><?php echo e(old('sensitive_data', $data['descriptive']['sensitive_data'] ?? 'None')); ?></textarea>
                    <div class="form-hint">
                        e.g. racial or ethnic origins, sexual orientations, religious beliefs, political opinions, or union memberships
                    </div>
                </div>

                <!-- Preprocessing -->
                <div class="form-group mb-4">
                    <label for="preprocessing" class="form-label">
                        Was there any data preprocessing performed?
                    </label>
                    <textarea 
                        class="form-control" 
                        id="preprocessing" 
                        name="preprocessing" 
                        rows="3"
                        placeholder="Describe any preprocessing steps..."><?php echo e(old('preprocessing', $data['descriptive']['preprocessing'] ?? '')); ?></textarea>
                    <div class="form-hint">
                        e.g. discretization or bucketing, tokenization, part-of-speech tagging, SIFT feature extraction, removal of instances, processing of missing values
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-group mb-4">
                    <label for="additional_info" class="form-label">
                        Additional Information
                    </label>
                    <textarea 
                        class="form-control" 
                        id="additional_info" 
                        name="additional_info" 
                        rows="3"
                        placeholder="Please provide any additional information about your dataset."><?php echo e(old('additional_info', $data['descriptive']['additional_info'] ?? '')); ?></textarea>
                </div>

                <!-- Citation Requests -->
                <div class="form-group mb-4">
                    <label for="citation_requests" class="form-label">
                        Citation Requests/Acknowledgements
                    </label>
                    <textarea 
                        class="form-control" 
                        id="citation_requests" 
                        name="citation_requests" 
                        rows="3"
                        placeholder="e.g., Please cite this paper when using this dataset: Author et al., 'Title', Journal, Year"><?php echo e(old('citation_requests', $data['descriptive']['citation_requests'] ?? '')); ?></textarea>
                    <div class="form-hint mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Remember that datasets in the repository are publicly available for use under a 
                        <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" class="text-primary">CC BY 4.0 license</a>, 
                        so if there is a particular way in which you would like your dataset to be cited, please include it here.
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="form-card summary-card">
                <h5 class="card-section-title">Submission Summary</h5>
                
                <div class="summary-item">
                    <strong>Dataset Name:</strong>
                    <span><?php echo e($data['name'] ?? 'Not provided'); ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Number of Instances:</strong>
                    <span><?php echo e($data['num_instances'] ?? 'Not provided'); ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Number of Features:</strong>
                    <span><?php echo e($data['num_features'] ?? 'Not provided'); ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Subject Area:</strong>
                    <span><?php echo e($data['subject_area'] ?? 'Not provided'); ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Associated Tasks:</strong>
                    <span><?php echo e(!empty($data['associated_tasks']) ? implode(', ', $data['associated_tasks']) : 'Not provided'); ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Files to Upload:</strong>
                    <span><?php echo e(count($data['files'] ?? [])); ?> file(s)</span>
                </div>
                
                <div class="summary-item">
                    <strong>Keywords:</strong>
                    <span><?php echo e(!empty($data['keywords']) ? count($data['keywords']) . ' keyword(s)' : 'None'); ?></span>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Before submitting:</strong> Please review all information carefully. Once submitted, your dataset will be reviewed by our team before being published.
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.variable-info')); ?>" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>SUBMIT DATASET
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit this dataset?</p>
                <p class="text-muted small">
                    By submitting, you confirm that:
                </p>
                <ul class="small">
                    <li>You have the right to share this dataset publicly</li>
                    <li>The dataset does not contain sensitive personal information</li>
                    <li>All information provided is accurate</li>
                    <li>You agree to the CC BY 4.0 license terms</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                    <i class="bi bi-check-circle me-2"></i>Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal (shown after successful submission) -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-body text-center p-4">
                <!-- Progress Bar (Yellow) -->
                <div class="mb-3">
                    <div class="progress" style="height: 6px; border-radius: 3px; background-color: #e9ecef;">
                        <div class="progress-bar" style="width: 100%; background-color: #ffd60a !important; transition: width 0.3s;"></div>
                    </div>
                </div>
                
                <!-- Success Icon -->
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                </div>
                
                <h5 class="mb-3 fw-bold">Successfully uploaded dataset!</h5>
                <p class="text-muted small mb-4">Your dataset has been submitted and is pending review.</p>
                
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <a href="<?php echo e(route('profile.datasets')); ?>" class="btn" style="background-color: #0077b6; color: white; font-weight: 600; padding: 0.65rem 1.5rem; border-radius: 6px; border: none;">
                        VIEW SUBMITTED DATASET
                    </a>
                    <button type="button" class="btn" style="background-color: #f09393; color: white; font-weight: 600; padding: 0.65rem 1.5rem; border-radius: 6px; border: none;" onclick="window.location.href='<?php echo e(route('home')); ?>'">
                        CLOSE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay (shown during submission) -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); z-index: 9999; align-items: center; justify-content: center; flex-direction: column;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-3 fw-bold" style="color: #0077b6;">Submitting your dataset...</p>
    <p class="text-muted small">Please wait while we process your submission.</p>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
        padding: 2rem;
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
        margin-bottom: 0.75rem;
    }
    
    .form-control {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    .form-hint {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.4rem;
    }
    
    .form-hint a {
        color: #0077b6;
        text-decoration: underline;
    }
    
    /* Summary Card */
    .summary-card {
        background-color: #f8f9fa;
        border: 2px solid #0077b6;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .summary-item strong {
        color: #0077b6;
    }
    
    .summary-item span {
        color: #333;
        text-align: right;
    }
    
    .alert-info {
        background-color: #e9f5f9;
        border: 1px solid #0077b6;
        color: #005f73;
        padding: 1rem;
        border-radius: 6px;
    }
    
    /* Buttons */
    .btn-back {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }
    
    .btn-back:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-submit {
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
    
    .btn-submit:hover:not(:disabled) {
        background-color: #005f73;
    }
    
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .form-navigation {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e0e0e0;
        padding: 1.25rem 1.5rem;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-bar.bg-warning {
        background-color: #ffd60a !important;
    }
    
    /* Loading Overlay */
    #loadingOverlay {
        display: none;
    }
    
    #loadingOverlay.show {
        display: flex !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem 1rem;
        }
        
        .form-card {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .summary-item {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .summary-item span {
            text-align: left;
        }
        
        .form-navigation {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-back, .btn-submit {
            width: 100%;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('donationForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const loadingOverlay = document.getElementById('loadingOverlay');
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    
    // Hide loading overlay on page load
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
    
    // Show confirmation modal on submit button click
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Basic validation before showing confirmation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill in all required fields marked with *');
            return;
        }
        
        confirmModal.show();
    });
    
    // Handle confirmed submission
    confirmSubmitBtn.addEventListener('click', function() {
        confirmModal.hide();
        
        // Show loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('show');
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        
        // Submit the form
        form.submit();
    });
    
    // Show success modal if submission was successful (via session)
    <?php if(session('success')): ?>
        // Hide loading overlay first if visible
        if (loadingOverlay) {
            loadingOverlay.classList.remove('show');
        }
        
        // Show success modal after a short delay for animation
        setTimeout(function() {
            successModal.show();
        }, 500);
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        if (loadingOverlay) {
            loadingOverlay.classList.remove('show');
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>SUBMIT DATASET';
        alert('Error: <?php echo e(session('error')); ?>');
    <?php endif; ?>
    
    // Auto-save to localStorage for draft recovery
    const inputs = form.querySelectorAll('textarea');
    inputs.forEach(input => {
        // Load saved data on page load
        const saved = localStorage.getItem('descriptive_' + input.id);
        if (saved && !input.value) {
            input.value = saved;
        }
        
        // Save on input
        input.addEventListener('input', function() {
            localStorage.setItem('descriptive_' + this.id, this.value);
        });
    });
    
    // Clear localStorage after successful submit
    form.addEventListener('submit', function() {
        if (sessionStorage.getItem('formSubmitted') !== 'true') {
            sessionStorage.setItem('formSubmitted', 'true');
            inputs.forEach(input => {
                localStorage.removeItem('descriptive_' + input.id);
            });
        }
    });
    
    // Character counter for textareas
    inputs.forEach(input => {
        const counter = document.createElement('div');
        counter.className = 'text-muted small mt-1';
        counter.style.textAlign = 'right';
        input.parentNode.appendChild(counter);
        
        function updateCount() {
            const count = input.value.length;
            const max = input.maxLength || 5000;
            counter.textContent = `${count} / ${max} characters`;
            
            if (count > max * 0.9) {
                counter.classList.add('text-warning');
                counter.classList.remove('text-muted');
            } else {
                counter.classList.add('text-muted');
                counter.classList.remove('text-warning');
            }
        }
        
        input.addEventListener('input', updateCount);
        updateCount();
    });
    
    // Remove invalid state on input
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/descriptive.blade.php ENDPATH**/ ?>