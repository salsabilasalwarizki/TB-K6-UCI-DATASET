
<?php $__env->startSection('title', 'Dataset Donation - Creators - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 42.5%"></div>
            </div>
            <span class="progress-text">Page 3 / 7</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.creators.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Creators Section -->
            <div class="form-card">
                <h5 class="card-section-title">Optional: Add Creators</h5>
                
                <div class="alert alert-info mb-4">
                    <strong>Note:</strong> Creator information will be publicly visible on the dataset page.
                </div>

                <!-- Creators List -->
                <div id="creators-container">
                    <?php
                        $creators = old('creators', $creatorsData ?? []);
                    ?>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $creator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="creator-item mb-4" data-index="<?php echo e($index); ?>">
                            <div class="creator-header d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Creator <?php echo e($index + 1); ?></h6>
                                <?php if($index > 0): ?>
                                    <button type="button" class="btn-remove-creator text-danger" onclick="removeCreator(<?php echo e($index); ?>)">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name <span class="required">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="creators[<?php echo e($index); ?>][name]" 
                                           value="<?php echo e($creator['name'] ?? ''); ?>" 
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Affiliation</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="creators[<?php echo e($index); ?>][affiliation]" 
                                           value="<?php echo e($creator['affiliation'] ?? ''); ?>"
                                           placeholder="e.g., University of California">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           class="form-control" 
                                           name="creators[<?php echo e($index); ?>][email]" 
                                           value="<?php echo e($creator['email'] ?? ''); ?>"
                                           placeholder="creator@example.com">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ORCID</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="creators[<?php echo e($index); ?>][orcid]" 
                                           value="<?php echo e($creator['orcid'] ?? ''); ?>"
                                           placeholder="0000-0000-0000-0000">
                                    <div class="form-hint">Format: 0000-0000-0000-0000</div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Contribution Role</label>
                                    <select class="form-control" name="creators[<?php echo e($index); ?>][contribution_role]">
                                        <option value="Creator" <?php echo e(($creator['contribution_role'] ?? '') == 'Creator' ? 'selected' : ''); ?>>Creator</option>
                                        <option value="Donor" <?php echo e(($creator['contribution_role'] ?? '') == 'Donor' ? 'selected' : ''); ?>>Donor</option>
                                        <option value="Analyst" <?php echo e(($creator['contribution_role'] ?? '') == 'Analyst' ? 'selected' : ''); ?>>Analyst</option>
                                        <option value="Data Collector" <?php echo e(($creator['contribution_role'] ?? '') == 'Data Collector' ? 'selected' : ''); ?>>Data Collector</option>
                                        <option value="Other" <?php echo e(($creator['contribution_role'] ?? '') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div id="no-creators-msg" class="text-center py-5">
                            <i class="bi bi-people display-4 text-muted mb-3"></i>
                            <p class="text-muted">No creators added yet. Click the button below to add creators.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Add Creator Button -->
                <div class="text-center mt-4 mb-4">
                    <button type="button" class="btn-add-creator" onclick="addCreator()">
                        <i class="bi bi-plus-circle me-2"></i>BEGIN ADDING CREATORS
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.paper')); ?>" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
                <button type="submit" class="btn-next">
                    NEXT <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden template for new creator -->
<template id="creator-template">
    <div class="creator-item mb-4" data-index="__INDEX__">
        <div class="creator-header d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Creator __INDEX__</h6>
            <button type="button" class="btn-remove-creator text-danger" onclick="removeCreator(__INDEX__)">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name <span class="required">*</span></label>
                <input type="text" 
                       class="form-control" 
                       name="creators[__INDEX__][name]" 
                       required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Affiliation</label>
                <input type="text" 
                       class="form-control" 
                       name="creators[__INDEX__][affiliation]"
                       placeholder="e.g., University of California">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" 
                       class="form-control" 
                       name="creators[__INDEX__][email]"
                       placeholder="creator@example.com">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">ORCID</label>
                <input type="text" 
                       class="form-control" 
                       name="creators[__INDEX__][orcid]"
                       placeholder="0000-0000-0000-0000">
                <div class="form-hint">Format: 0000-0000-0000-0000</div>
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Contribution Role</label>
                <select class="form-control" name="creators[__INDEX__][contribution_role]">
                    <option value="Creator">Creator</option>
                    <option value="Donor">Donor</option>
                    <option value="Analyst">Analyst</option>
                    <option value="Data Collector">Data Collector</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        
        <hr class="my-4">
    </div>
</template>
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
    
    .form-hint {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.4rem;
    }
    
    .btn-add-creator {
        background-color: #0077b6;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-add-creator:hover {
        background-color: #005f73;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,119,182,0.3);
    }
    
    .btn-remove-creator {
        background: none;
        border: none;
        font-weight: 600;
        cursor: pointer;
        padding: 0.5rem;
    }
    
    .btn-remove-creator:hover {
        text-decoration: underline;
    }
    
    .creator-item {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
    
    .alert-info {
        background-color: #e9f5f9;
        border: 1px solid #0077b6;
        color: #005f73;
        padding: 1rem;
        border-radius: 6px;
    }
    
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
    }
    
    .btn-back:hover {
        background-color: #dc3545;
        color: white;
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
    
    .form-navigation {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
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
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let creatorIndex = <?php echo e(count($creatorsData ?? old('creators', []))); ?>;

function addCreator() {
    // Hide no creators message
    const noCreatorsMsg = document.getElementById('no-creators-msg');
    if (noCreatorsMsg) {
        noCreatorsMsg.style.display = 'none';
    }
    
    // Get template
    const template = document.getElementById('creator-template');
    const clone = template.content.cloneNode(true);
    
    // Replace __INDEX__ with actual index
    const html = clone.querySelector('.creator-item').outerHTML.replace(/__INDEX__/g, creatorIndex);
    
    // Add to container
    const container = document.getElementById('creators-container');
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    container.appendChild(tempDiv.firstElementChild);
    
    creatorIndex++;
}

function removeCreator(index) {
    const creatorItem = document.querySelector(`.creator-item[data-index="${index}"]`);
    if (creatorItem) {
        creatorItem.remove();
        
        // Show no creators message if all removed
        const remainingCreators = document.querySelectorAll('.creator-item');
        if (remainingCreators.length === 0) {
            const noCreatorsMsg = document.getElementById('no-creators-msg');
            if (noCreatorsMsg) {
                noCreatorsMsg.style.display = 'block';
            }
        }
    }
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const creators = document.querySelectorAll('.creator-item');
    if (creators.length === 0) {
        // Allow submission even with no creators (it's optional)
        return true;
    }
    
    // Validate each creator has at least a name
    let valid = true;
    creators.forEach(creator => {
        const nameInput = creator.querySelector('input[name*="[name]"]');
        if (nameInput && !nameInput.value.trim()) {
            valid = false;
            nameInput.classList.add('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields (Name) for each creator.');
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/creators.blade.php ENDPATH**/ ?>