
<?php $__env->startSection('title', 'Link External Dataset - Variable Info - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 83.33%"></div>
            </div>
            <span class="progress-text">Page 5 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.variable-info.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-card">
                <h5 class="card-section-title">Variable Information</h5>
                
                <!-- Class Labels -->
                <div class="form-group mb-4">
                    <label for="class_labels" class="form-label">
                        Provide class labels for categorical data, if applicable.
                    </label>
                    <textarea 
                        class="form-control" 
                        id="class_labels" 
                        name="class_labels" 
                        rows="4"
                        placeholder="e.g., Class1, Class2, Class3 (one per line or comma-separated)"
                    ><?php echo e(old('class_labels', $data['class_labels'] ?? '')); ?></textarea>
                    <small class="form-text text-muted">
                        List the possible values for the target variable if this is a classification dataset.
                    </small>
                </div>

                <!-- Additional Variable Info -->
                <div class="form-group mb-4">
                    <label for="variable_info" class="form-label">
                        Provide additional information about the dataset's variables.
                    </label>
                    <textarea 
                        class="form-control" 
                        id="variable_info" 
                        name="variable_info" 
                        rows="6"
                        placeholder="Describe the variables in the dataset, their meanings, units, ranges, etc."
                    ><?php echo e(old('variable_info', $data['variable_info'] ?? '')); ?></textarea>
                    <small class="form-text text-muted">
                        Include information about what each variable represents, measurement units, value ranges, or any other relevant details.
                    </small>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.linking.keywords')); ?>" class="btn-back">
                    BACK
                </a>
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
    .linking-page { max-width: 900px; margin: 0 auto; }
    .page-title { padding-top: 50px; color: #0077b6; font-weight: 700; font-size: 2rem; margin-bottom: 1.5rem; }
    .page-description { color: #555; line-height: 1.7; font-size: 0.95rem; margin-bottom: 0.5rem; }
    .progress-wrapper { display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem; }
    .progress { flex: 1; height: 8px; background-color: #e9ecef; border-radius: 4px; overflow: hidden; }
    .progress-bar.bg-warning { background-color: #ffd60a !important; }
    .progress-text { font-size: 0.85rem; color: #6c757d; white-space: nowrap; }
    
    .form-card { background: white; border: 1px solid #e0e0e0; border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; }
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.3rem; margin-bottom: 1.5rem; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-weight: 600; font-size: 0.95rem; color: #333; margin-bottom: 0.5rem; }
    .form-control { 
        border-radius: 8px; 
        border: 1px solid #dee2e6; 
        padding: 0.7rem 1rem; 
        font-size: 0.95rem; 
        resize: vertical;
        font-family: inherit;
    }
    .form-control:focus { 
        border-color: #0077b6; 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(0,119,182,0.1); 
    }
    textarea.form-control { min-height: 100px; }
    
    .form-text { font-size: 0.85rem; color: #6c757d; margin-top: 0.4rem; display: block; }
    
    .form-navigation { display: flex; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem; }
    .btn-back { 
        background-color: #fff; 
        color: #dc3545; 
        border: 1px solid #dc3545; 
        font-weight: 700; 
        padding: 0.75rem 2.5rem; 
        border-radius: 6px; 
        text-decoration: none; 
        transition: all 0.2s; 
    }
    .btn-back:hover { background-color: #dc3545; color: white; }
    .btn-next { 
        background-color: #0077b6; 
        color: white; 
        font-weight: 700; 
        padding: 0.75rem 2.5rem; 
        border: none; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: background-color 0.2s; 
    }
    .btn-next:hover { background-color: #005f73; }
    
    @media (max-width: 768px) { 
        .container { padding: 1.5rem 1rem; } 
        .form-card { padding: 1.25rem; } 
        .form-navigation { flex-direction: column; }
        .btn-back, .btn-next { width: 100%; text-align: center; } 
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/variable-info.blade.php ENDPATH**/ ?>