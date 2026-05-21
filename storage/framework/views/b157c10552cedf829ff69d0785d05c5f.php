
<?php $__env->startSection('title', 'Link External Dataset - Paper - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 33.33%"></div>
            </div>
            <span class="progress-text">Page 2 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.paper.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-card">
                <h5 class="card-section-title">Introductory Paper</h5>
                
                <p class="text-muted mb-4">
                    Optional: Provide a paper ID and its type to auto-fill the fields
                </p>

                <!-- Paper ID Type - Dropdown dengan semua opsi -->
                <div class="form-group">
                    <label for="paper_id_type" class="form-label">Paper ID Type</label>
                    <select class="form-control" id="paper_id_type" name="paper_id_type">
                        <option value="None" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'None' ? 'selected' : ''); ?>>None</option>
                        <option value="URL" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'URL' ? 'selected' : ''); ?>>URL (from semanticscholar.org, arxiv.org, aclweb.org, acm.org, or biorxiv.org)</option>
                        <option value="DOI" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'DOI' ? 'selected' : ''); ?>>Digital Object Identifier (DOI)</option>
                        <option value="Semantic Scholar ID" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'Semantic Scholar ID' ? 'selected' : ''); ?>>Semantic Scholar ID</option>
                        <option value="Corpus ID" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'Corpus ID' ? 'selected' : ''); ?>>Corpus ID</option>
                        <option value="arXiv" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'arXiv' ? 'selected' : ''); ?>>arXiv</option>
                        <option value="Microsoft Academic Graph (MAG)" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'Microsoft Academic Graph (MAG)' ? 'selected' : ''); ?>>Microsoft Academic Graph (MAG)</option>
                        <option value="Association for Computational Linguistics (ACL)" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'Association for Computational Linguistics (ACL)' ? 'selected' : ''); ?>>Association for Computational Linguistics (ACL)</option>
                        <option value="PubMed/Medline (PMID)" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'PubMed/Medline (PMID)' ? 'selected' : ''); ?>>PubMed/Medline (PMID)</option>
                        <option value="PubMed Central (PMCID)" <?php echo e(old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'PubMed Central (PMCID)' ? 'selected' : ''); ?>>PubMed Central (PMCID)</option>
                    </select>
                </div>

                <!-- Paper ID with FIND Button -->
                <div class="form-group">
                    <label for="paper_id" class="form-label">Paper ID</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               id="paper_id" 
                               name="paper_id" 
                               value="<?php echo e(old('paper_id', $oldPaper['paper_id'] ?? '')); ?>" 
                               placeholder="e.g., 10.1145/123456.789012">
                        <button type="button" class="btn-find" id="findPaperBtn">
                            <span>FIND</span>
                            <i class="bi bi-search ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title" class="form-label">Title <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="title" 
                           name="title" 
                           value="<?php echo e(old('title', $oldPaper['title'] ?? '')); ?>" 
                           required 
                           placeholder="Title of the paper">
                    <?php $__errorArgs = ['title'];
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

                <!-- Authors -->
                <div class="form-group">
                    <label for="authors" class="form-label">Authors <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="authors" 
                           name="authors" 
                           value="<?php echo e(old('authors', $oldPaper['authors'] ?? '')); ?>" 
                           required 
                           placeholder="e.g., John Doe, Jane Smith">
                    <?php $__errorArgs = ['authors'];
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

                <!-- Venue -->
                <div class="form-group">
                    <label for="venue" class="form-label">Venue <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="venue" 
                           name="venue" 
                           value="<?php echo e(old('venue', $oldPaper['venue'] ?? '')); ?>" 
                           required 
                           placeholder="e.g., NeurIPS 2021, Journal of Machine Learning Research">
                    <?php $__errorArgs = ['venue'];
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

                <!-- Year -->
                <div class="form-group">
                    <label for="year" class="form-label">Year <span class="required">*</span></label>
                    <input type="number" 
                           class="form-control" 
                           id="year" 
                           name="year" 
                           value="<?php echo e(old('year', $oldPaper['year'] ?? '')); ?>" 
                           required 
                           min="1900" 
                           max="<?php echo e(date('Y')); ?>" 
                           placeholder="e.g., 2023">
                    <?php $__errorArgs = ['year'];
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

                <!-- URL -->
                <div class="form-group">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" 
                           class="form-control" 
                           id="url" 
                           name="url" 
                           value="<?php echo e(old('url', $oldPaper['url'] ?? '')); ?>" 
                           placeholder="https://...">
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.linking.metadata')); ?>" class="btn-back">
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
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.2rem; margin-bottom: 1rem; }
    .required { color: #dc3545; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-weight: 600; font-size: 0.95rem; color: #333; margin-bottom: 0.5rem; }
    .form-control { width: 100%; border: 1px solid #dee2e6; border-radius: 6px; padding: 0.65rem 1rem; font-size: 0.95rem; transition: border-color 0.2s; }
    .form-control:focus { border-color: #0077b6; outline: none; box-shadow: 0 0 0 3px rgba(0,119,182,0.12); }
    .error-message { color: #dc3545; font-size: 0.8rem; margin-top: 0.4rem; }
    
    /* Input Group untuk Paper ID + FIND button */
    .input-group { display: flex; gap: 0.5rem; }
    .input-group .form-control { flex: 1; }
    .btn-find { 
        background-color: #f8f9fa; 
        border: 1px solid #dee2e6; 
        color: #6c757d;
        font-weight: 600;
        padding: 0.65rem 1.5rem; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: all 0.2s;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    .btn-find:hover { background-color: #e9ecef; color: #0077b6; border-color: #0077b6; }
    .btn-find:disabled { opacity: 0.5; cursor: not-allowed; }
    
    /* Navigation Buttons */
    .form-navigation { display: flex; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem; }
    .btn-back { 
        background-color: #fff; 
        color: #dc3545; 
        border: 1px solid #dc3545; 
        font-weight: 700; 
        padding: 0.75rem 2.5rem; 
        border-radius: 6px; 
        font-size: 0.95rem; 
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
        font-size: 0.95rem; 
        cursor: pointer; 
        transition: background-color 0.2s; 
    }
    .btn-next:hover { background-color: #005f73; }
    
    @media (max-width: 768px) { 
        .container { padding: 1.5rem 1rem; } 
        .form-card { padding: 1.25rem; } 
        .page-title { font-size: 1.5rem; } 
        .input-group { flex-direction: column; }
        .btn-find { width: 100%; justify-content: center; }
        .form-navigation { flex-direction: column; }
        .btn-back, .btn-next { width: 100%; text-align: center; } 
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-fetch paper data when FIND button is clicked
document.getElementById('findPaperBtn').addEventListener('click', function() {
    const paperId = document.getElementById('paper_id').value;
    const paperIdType = document.getElementById('paper_id_type').value;
    
    if (!paperId || paperIdType === 'None') {
        alert('Please enter a Paper ID and select a valid ID type');
        return;
    }
    
    // TODO: Implement API call to fetch paper data based on ID type
    // Example: fetch paper data from CrossRef (DOI), arXiv API, or PubMed API
    alert('Paper lookup functionality will be implemented here.\nID: ' + paperId + '\nType: ' + paperIdType);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/paper.blade.php ENDPATH**/ ?>