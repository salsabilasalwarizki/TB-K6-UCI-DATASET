
<?php $__env->startSection('title', 'Dataset Donation - Paper - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 2 of 7: Introductory Paper</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 28.5%"></div>
            </div>
            <span class="progress-text small text-muted">Page 2 / 7</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.paper.store')); ?>" method="POST" id="paperForm">
            <?php echo csrf_field(); ?>
            
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

            <!-- Paper Section -->
            <div class="form-card">
                <h5 class="card-section-title">Introductory Paper (Optional)</h5>
                <p class="text-muted small mb-4">
                    Provide details about the paper that introduced this dataset. 
                    <br><strong>Tip:</strong> Enter DOI/arXiv ID and click "Find" to auto-fill.
                </p>

                <!-- Auto-fill Section -->
                <div class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-md-4">
                        <label for="paper_id_type" class="form-label small">ID Type</label>
                        <select class="form-select form-select-sm" id="paper_id_type" name="paper_id_type">
                            <option value="None" <?php echo e(old('paper_id_type', session('donation_wizard.paper.paper_id_type', 'None')) == 'None' ? 'selected' : ''); ?>>None</option>
                            <option value="DOI" <?php echo e(old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'DOI' ? 'selected' : ''); ?>>DOI</option>
                            <option value="arXiv" <?php echo e(old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'arXiv' ? 'selected' : ''); ?>>arXiv</option>
                            <option value="PubMed" <?php echo e(old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'PubMed' ? 'selected' : ''); ?>>PubMed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="paper_id" class="form-label small">Paper ID</label>
                        <input type="text" class="form-control form-control-sm" id="paper_id" name="paper_id" 
                               value="<?php echo e(old('paper_id', session('donation_wizard.paper.paper_id', ''))); ?>"
                               placeholder="e.g., 10.1000/xyz123 or arXiv:2101.12345">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" id="btnFindPaper" disabled>
                            <i class="bi bi-search me-1"></i>Find
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Manual Entry Fields -->
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Paper Title <span class="required">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="title" name="title" 
                           value="<?php echo e(old('title', session('donation_wizard.paper.title', ''))); ?>" 
                           required maxlength="500"
                           placeholder="Enter the full paper title">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group mb-3">
                    <label for="authors" class="form-label">Authors <span class="required">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['authors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="authors" name="authors" 
                           value="<?php echo e(old('authors', session('donation_wizard.paper.authors', ''))); ?>" 
                           required maxlength="500"
                           placeholder="e.g., J. Smith, A. Johnson, K. Lee">
                    <?php $__errorArgs = ['authors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-hint">Separate multiple authors with commas.</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="venue" class="form-label">Venue/Journal <span class="required">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['venue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="venue" name="venue" 
                               value="<?php echo e(old('venue', session('donation_wizard.paper.venue', ''))); ?>" 
                               required maxlength="255"
                               placeholder="e.g., NeurIPS 2024, JMLR">
                        <?php $__errorArgs = ['venue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year <span class="required">*</span></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="year" name="year" 
                               value="<?php echo e(old('year', session('donation_wizard.paper.year', date('Y')))); ?>" 
                               required min="1900" max="<?php echo e(date('Y')); ?>"
                               placeholder="e.g., 2024">
                        <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="url" class="form-label">URL (Optional)</label>
                        <input type="url" class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="url" name="url" 
                               value="<?php echo e(old('url', session('donation_wizard.paper.url', ''))); ?>" 
                               maxlength="500"
                               placeholder="https://...">
                        <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="<?php echo e(route('contribute.metadata')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Enable/disable Find button based on input
const paperType = document.getElementById('paper_id_type');
const paperId = document.getElementById('paper_id');
const btnFind = document.getElementById('btnFindPaper');

function updateFindButton() {
    if (paperType.value !== 'None' && paperId.value.trim()) {
        btnFind.disabled = false;
        btnFind.classList.remove('btn-outline-secondary');
        btnFind.classList.add('btn-outline-primary');
    } else {
        btnFind.disabled = true;
        btnFind.classList.remove('btn-outline-primary');
        btnFind.classList.add('btn-outline-secondary');
    }
}

paperType.addEventListener('change', updateFindButton);
paperId.addEventListener('input', updateFindButton);
updateFindButton();

// Find paper (mock implementation - replace with actual API call)
btnFind.addEventListener('click', function() {
    const type = paperType.value;
    const id = paperId.value.trim();
    
    if (!id) {
        alert('Please enter a Paper ID');
        return;
    }
    
    // Show loading
    const originalText = btnFind.innerHTML;
    btnFind.disabled = true;
    btnFind.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Searching...';
    
    // Mock API call (replace with actual fetch to Crossref/arXiv)
    setTimeout(() => {
        alert('Auto-fill feature requires API integration.\n\nFor now, please enter paper details manually.');
        btnFind.disabled = false;
        btnFind.innerHTML = originalText;
    }, 1500);
});

// Form validation
document.getElementById('paperForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const authors = document.getElementById('authors').value.trim();
    const venue = document.getElementById('venue').value.trim();
    const year = document.getElementById('year').value;
    
    if (!title || !authors || !venue || !year) {
        e.preventDefault();
        alert('Please fill in all required fields marked with *');
        return false;
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/paper.blade.php ENDPATH**/ ?>