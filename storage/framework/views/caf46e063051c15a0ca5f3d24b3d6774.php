
<?php $__env->startSection('title', 'Link External Dataset - Descriptive - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 100%"></div>
            </div>
            <span class="progress-text">Page 6 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.submit')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-card">
    <h5 class="card-section-title">Descriptive Questions</h5>
    
    <!-- 1. Purpose (Optional) -->
    <div class="form-group mb-4">
        <label for="purpose" class="form-label">
            For what purpose was the dataset created?
        </label>
        <textarea 
            class="form-control" 
            id="purpose" 
            name="purpose" 
            rows="3"
            placeholder="Describe the main goal of this dataset..."><?php echo e(old('purpose', $data['purpose'] ?? '')); ?></textarea>
    </div>

    <!-- 2. Funding -->
    <div class="form-group mb-4">
        <label for="funding" class="form-label">
            Who funded the creation of the dataset?
        </label>
        <textarea 
            class="form-control" 
            id="funding" 
            name="funding" 
            rows="2"
            placeholder="e.g., National Science Foundation, Internal Grant, None"><?php echo e(old('funding', $data['funding'] ?? '')); ?></textarea>
    </div>

    <!-- 3. Instances Represent (Optional) -->
    <div class="form-group mb-4">
        <label for="instances_represent" class="form-label">
            What do the instances in this dataset represent?
        </label>
        <textarea 
            class="form-control" 
            id="instances_represent" 
            name="instances_represent" 
            rows="2"
            placeholder="e.g., patients, photos, transactions, sensor readings..."><?php echo e(old('instances_represent', $data['instances_represent'] ?? '')); ?></textarea>
        <small class="form-text text-muted">e.g. documents, photos, people, countries</small>
    </div>

    <!-- 4. Data Splits -->
    <div class="form-group mb-4">
        <label for="data_splits" class="form-label">
            Are there recommended data splits?
        </label>
        <textarea 
            class="form-control" 
            id="data_splits" 
            name="data_splits" 
            rows="2"
            placeholder="e.g., 70% training, 30% testing"><?php echo e(old('data_splits', $data['data_splits'] ?? '')); ?></textarea>
        <small class="form-text text-muted">e.g. training, development/validation, testing</small>
    </div>

    <!-- 5. Sensitive Data -->
    <div class="form-group mb-4">
        <label for="sensitive_data" class="form-label">
            Does the dataset contain data that might be considered sensitive in any way?
        </label>
        <textarea 
            class="form-control" 
            id="sensitive_data" 
            name="sensitive_data" 
            rows="3"
            placeholder="Describe any sensitive data..."><?php echo e(old('sensitive_data', $data['sensitive_data'] ?? '')); ?></textarea>
        <small class="form-text text-muted">e.g. racial or ethnic origins, sexual orientations, religious beliefs, political opinions, or union memberships</small>
    </div>

    <!-- 6. Preprocessing -->
    <div class="form-group mb-4">
        <label for="preprocessing" class="form-label">
            Was there any data preprocessing performed?
        </label>
        <textarea 
            class="form-control" 
            id="preprocessing" 
            name="preprocessing" 
            rows="3"
            placeholder="Describe preprocessing steps..."><?php echo e(old('preprocessing', $data['preprocessing'] ?? '')); ?></textarea>
        <small class="form-text text-muted">e.g. discretization or bucketing, tokenization, part-of-speech tagging, SIFT feature extraction, removal of instances, processing of missing values</small>
    </div>

    <!-- 7. Additional Information -->
    <div class="form-group mb-4">
        <label for="additional_info" class="form-label">
            Additional Information
        </label>
        <textarea 
            class="form-control" 
            id="additional_info" 
            name="additional_info" 
            rows="3"
            placeholder="Any other details..."><?php echo e(old('additional_info', $data['additional_info'] ?? '')); ?></textarea>
        <small class="form-text text-muted">Please provide any additional information about your dataset.</small>
    </div>

    <!-- 8. Citation Requests -->
    <div class="form-group mb-4">
        <label for="citation_requests" class="form-label">
            Citation Requests/Acknowledgements
        </label>
        <textarea 
            class="form-control" 
            id="citation_requests" 
            name="citation_requests" 
            rows="3"
            placeholder="e.g., Please cite this dataset as..."><?php echo e(old('citation_requests', $data['citation_requests'] ?? '')); ?></textarea>
        <small class="form-text text-muted">
            Remember that datasets in the repository are publicly available for use under a CC BY 4.0 license, so if there is a particular way in which you would like your dataset to be cited, please include it here.
        </small>
    </div>

    <!-- Summary Box -->
    <div class="summary-box p-3 bg-light border rounded mt-4">
        <h6 class="fw-bold mb-2">Summary</h6>
        <p class="small text-muted mb-0">
            <strong>Dataset Name:</strong> <?php echo e($data['name'] ?? 'N/A'); ?><br>
            <strong>External URL:</strong> <a href="<?php echo e($data['external_url'] ?? '#'); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;"><?php echo e($data['external_url'] ?? 'N/A'); ?></a><br>
            <strong>Subject Area:</strong> <?php echo e($data['subject_area'] ?? 'N/A'); ?>

        </p>
    </div>
</div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.linking.variable-info')); ?>" class="btn-back">
                    BACK
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle me-2"></i>SUBMIT
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
    
    .form-label { font-weight: 600; font-size: 0.95rem; color: #333; margin-bottom: 0.5rem; }
    .form-control { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.7rem 1rem; font-size: 0.95rem; resize: vertical; }
    .form-control:focus { border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,0.1); }
    .form-text { font-size: 0.85rem; color: #6c757d; margin-top: 0.4rem; display: block; }
    .required { color: #dc3545; }
    
    .summary-box { border-left: 4px solid #0077b6 !important; background-color: #f8f9fa; }
    
    .form-navigation { display: flex; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem; }
    .btn-back { background-color: #fff; color: #dc3545; border: 1px solid #dc3545; font-weight: 700; padding: 0.75rem 2.5rem; border-radius: 6px; text-decoration: none; }
    .btn-back:hover { background-color: #dc3545; color: white; }
    .btn-submit { background-color: #0077b6; color: white; font-weight: 700; padding: 0.75rem 2.5rem; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; }
    .btn-submit:hover { background-color: #005f73; }
    
    @media (max-width: 768px) { 
        .container { padding: 1.5rem 1rem; } 
        .form-card { padding: 1.25rem; } 
        .form-navigation { flex-direction: column; }
        .btn-back, .btn-submit { width: 100%; text-align: center; } 
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/descriptive.blade.php ENDPATH**/ ?>