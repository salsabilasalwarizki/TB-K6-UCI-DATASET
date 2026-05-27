<?php $__env->startSection('title', 'Dataset Donation - Files - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 57%"></div>
            </div>
            <span class="progress-text">Page 4 / 7</span>
        </div>

        
    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Form has errors:</h6>
        <ul class="mb-0 small">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.files.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="file" name="files[]" multiple required class="form-control">

            <!-- File Format Selection -->
            <div class="form-card">
                <h5 class="card-section-title">What format is the data file? <span class="required">*</span></h5>
                
                <div class="radio-group">
                    <div class="radio-item">
                        <span class="radio-label">Tabular</span>
                        <input type="radio" 
                               name="file_format" 
                               value="tabular" 
                               id="format_tabular"
                               <?php echo e(old('file_format', 'tabular') == 'tabular' ? 'checked' : ''); ?>

                               onchange="toggleFormat()">
                    </div>
                    <div class="radio-item">
                        <span class="radio-label">Other</span>
                        <input type="radio" 
                               name="file_format" 
                               value="other" 
                               id="format_other"
                               <?php echo e(old('file_format') == 'other' ? 'checked' : ''); ?>

                               onchange="toggleFormat()">
                    </div>
                </div>
            </div>

            <!-- Tabular Data Section (Shown when Tabular is selected) -->
            <div id="tabular_section" class="tabular-section">
                <div class="form-card">
                    <h5 class="card-section-title">Tabular Data <span class="required">*</span></h5>
                    
                    <div class="checkbox-group mb-4">
                        <div class="checkbox-item">
                            <span class="checkbox-label">Does the data have a header?</span>
                           <input type="hidden" name="has_header" value="0">
<input type="checkbox" name="has_header" id="has_header" value="1" <?php echo e(old('has_header') == '1' ? 'checked' : ''); ?>>

                        </div>
                        <div class="checkbox-item">
                            <span class="checkbox-label">Does the data have missing values?</span>
                            <input type="hidden" name="has_missing" value="0">
<input type="checkbox" name="has_missing" id="has_missing" value="1" <?php echo e(old('has_missing') == '1' ? 'checked' : ''); ?>>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="upload-area mb-4" onclick="document.getElementById('tabular_file').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">CSV, ARFF, TXT (max 50MB)</small>
                        <input type="file" id="tabular_file" name="tabular_file" class="d-none" 
                               accept=".csv,.arff,.txt">
                    </div>

                    <!-- Variables Table -->
                    <div class="variables-table-container">
                        <table class="table variables-table" id="variables_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Variable Type</th>
                                    <th>Demographic</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Missing Values</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm" name="variables[0][name]" placeholder="Column name"></td>
<td>
    <select class="form-control form-control-sm" name="variables[0][role]">
                                            <option value="Feature">Feature</option>
                                            <option value="Target">Target</option>
                                            <option value="ID">ID</option>
                                        </select>
                                    </td>
                                    <td>
                                           <select class="form-control form-control-sm" name="variables[0][type]">
                                            <option value="Continuous">Continuous</option>
                                            <option value="Categorical">Categorical</option>
                                            <option value="Integer">Integer</option>
                                            <option value="Real">Real</option>
                                        </select>
                                    </td>
                                   <td><input type="text" class="form-control form-control-sm" name="variables[0][demo]"></td>
<td><input type="text" class="form-control form-control-sm" name="variables[0][desc]"></td>
<td><input type="text" class="form-control form-control-sm" name="variables[0][units]"></td>
<td class="text-center"><input type="checkbox" name="variables[0][missing]"></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="table-buttons mt-3">
                            <button type="button" class="btn btn-primary" onclick="addVariableRow()">
                                <i class="bi bi-plus-circle me-2"></i>ADD ROW
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteVariableRow()">
                                <i class="bi bi-trash me-2"></i>DELETE ROW
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Other Data Section -->
                <div class="form-card">
                    <h5 class="card-section-title">Other Data <i class="bi bi-info-circle text-info" data-bs-toggle="tooltip" title="Additional supporting files"></i></h5>
                    
                    <div class="upload-area" onclick="document.getElementById('other_file').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">Any format (max 50MB)</small>
                        <input type="file" id="other_file" name="other_file" class="d-none">
                    </div>
                </div>

                <!-- Test Data Section -->
                <div class="form-card">
                    <h5 class="card-section-title">Test Data <i class="bi bi-info-circle text-info" data-bs-toggle="tooltip" title="Separate test dataset (optional)"></i></h5>
                    
                    <div class="upload-area" onclick="document.getElementById('test_file').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">Any format (max 50MB)</small>
                        <input type="file" id="test_file" name="test_file" class="d-none">
                    </div>
                </div>
            </div>

            <!-- Other Format Section (Shown when Other is selected) -->
            <div id="other_section" class="other-section" style="display: none;">
                <div class="form-card">
                    <h5 class="card-section-title">Dataset <span class="required">*</span></h5>
                    
                    <div class="upload-area" onclick="document.getElementById('dataset_file').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">Any format (max 50MB)</small>
                        <input type="file" id="dataset_file" name="dataset_file" class="d-none">
                    </div>
                </div>

                <div class="form-card">
                    <h5 class="card-section-title">Test Data <i class="bi bi-info-circle text-info" data-bs-toggle="tooltip" title="Separate test dataset (optional)"></i></h5>
                    
                    <div class="upload-area" onclick="document.getElementById('test_file_other').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">Any format (max 50MB)</small>
                        <input type="file" id="test_file_other" name="test_file_other" class="d-none">
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.creators')); ?>" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
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
    
    .radio-group, .checkbox-group {
        margin: 1rem 0;
    }
    
    .radio-item, .checkbox-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .radio-item:last-child, .checkbox-item:last-child {
        border-bottom: none;
    }
    
    .radio-label, .checkbox-label {
        font-size: 0.95rem;
        color: #333;
    }
    
    .form-control {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    .upload-area {
        border: 2px dashed #0077b6;
        border-radius: 8px;
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f8f9fa;
    }
    
    .upload-area:hover {
        background-color: #e9f5f9;
        border-color: #005f73;
    }
    
    .upload-icon {
        font-size: 3rem;
        color: #0077b6;
        display: block;
        margin-bottom: 1rem;
    }
    
    .upload-text {
        margin: 0;
        color: #333;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .variables-table {
        width: 100%;
        margin-top: 1rem;
    }
    
    .variables-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.75rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .variables-table td {
        padding: 0.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .table-buttons {
        display: flex;
        gap: 1rem;
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
    
    .btn-primary {
        background-color: #0077b6;
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        border-radius: 6px;
    }
    
    .btn-primary:hover {
        background-color: #005f73;
    }
    
    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        border-radius: 6px;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
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
        
        .variables-table {
            font-size: 0.8rem;
        }
        
        .variables-table th,
        .variables-table td {
            padding: 0.4rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Toggle between Tabular and Other format
function toggleFormat() {
    const isTabular = document.getElementById('format_tabular').checked;
    const tabularSection = document.getElementById('tabular_section');
    const otherSection = document.getElementById('other_section');
    
    if (isTabular) {
        tabularSection.style.display = 'block';
        otherSection.style.display = 'none';
    } else {
        tabularSection.style.display = 'none';
        otherSection.style.display = 'block';
    }
}

// Add variable row
function addVariableRow() {
    const table = document.getElementById('variables_table');
    const tbody = table.querySelector('tbody');
    const rowIndex = tbody.querySelectorAll('tr').length; // index baru
    
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" name="variables[${rowIndex}][name]" placeholder="Column name"></td>
        <td>
            <select class="form-control form-control-sm" name="variables[${rowIndex}][role]">
                <option value="Feature">Feature</option>
                <option value="Target">Target</option>
                <option value="ID">ID</option>
            </select>
        </td>
        <td>
            <select class="form-control form-control-sm" name="variables[${rowIndex}][type]">
                <option value="Continuous">Continuous</option>
                <option value="Categorical">Categorical</option>
                <option value="Integer">Integer</option>
                <option value="Real">Real</option>
            </select>
        </td>
        <td><input type="text" class="form-control form-control-sm" name="variables[${rowIndex}][demo]"></td>
        <td><input type="text" class="form-control form-control-sm" name="variables[${rowIndex}][desc]"></td>
        <td><input type="text" class="form-control form-control-sm" name="variables[${rowIndex}][units]"></td>
        <td class="text-center"><input type="checkbox" name="variables[${rowIndex}][missing]"></td>
    `;
    
    tbody.appendChild(newRow);
}

// Delete variable row
function deleteVariableRow() {
    const table = document.getElementById('variables_table');
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    
    if (rows.length > 1) {
        tbody.removeChild(rows[rows.length - 1]);
    } else {
        alert('At least one variable row is required.');
    }
}

// File upload preview
document.getElementById('tabular_file')?.addEventListener('change', function(e) {
    showFilePreview(this, 'tabular_file');
});

document.getElementById('other_file')?.addEventListener('change', function(e) {
    showFilePreview(this, 'other_file');
});

document.getElementById('test_file')?.addEventListener('change', function(e) {
    showFilePreview(this, 'test_file');
});

document.getElementById('dataset_file')?.addEventListener('change', function(e) {
    showFilePreview(this, 'dataset_file');
});

document.getElementById('test_file_other')?.addEventListener('change', function(e) {
    showFilePreview(this, 'test_file_other');
});

function showFilePreview(input, elementId) {
    const file = input.files[0];
    if (file) {
        const uploadArea = input.closest('.upload-area');
        const preview = document.createElement('div');
        preview.className = 'alert alert-success mt-3';
        preview.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>
            <strong>${file.name}</strong> • ${(file.size / 1024 / 1024).toFixed(2)} MB
        `;
        
        // Remove existing preview
        const existingPreview = uploadArea.nextElementSibling;
        if (existingPreview && existingPreview.classList.contains('alert')) {
            existingPreview.remove();
        }
        
        uploadArea.after(preview);
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const isTabular = document.getElementById('format_tabular').checked;
    
    if (isTabular) {
        const tabularFile = document.getElementById('tabular_file').files[0];
        if (!tabularFile) {
            e.preventDefault();
            alert('Please upload a tabular data file.');
            return false;
        }
    } else {
        const datasetFile = document.getElementById('dataset_file').files[0];
        if (!datasetFile) {
            e.preventDefault();
            alert('Please upload a dataset file.');
            return false;
        }
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFormat();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/files.blade.php ENDPATH**/ ?>