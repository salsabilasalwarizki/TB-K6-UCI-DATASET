
<?php $__env->startSection('title', 'Link External Dataset - Creators - UCI Machine Learning Repository'); ?>

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
                <div class="progress-bar bg-warning" style="width: 50%"></div>
            </div>
            <span class="progress-text">Page 3 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.creators.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-card">
                <h5 class="card-section-title">Optional: Add Creators</h5>
                
                <div class="note-box mb-4">
                    <p class="mb-0"><strong>Note:</strong> Creator information will be publicly visible on the dataset page.</p>
                </div>

                <!-- Container for Creator Cards -->
                <div id="creatorsContainer">
                    <!-- Initial Creator (Creator 1) -->
                    <div class="creator-card" data-index="0">
                        <div class="creator-header">
                            <h6 class="creator-title">Creator 1</h6>
                            <button type="button" class="btn-remove-creator" onclick="removeCreator(this)" disabled title="Cannot remove the only creator">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" name="creators[0][first_name]" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" name="creators[0][last_name]" placeholder="Last Name" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="creators[0][email]" placeholder="Email">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Institution</label>
                                <input type="text" class="form-control" name="creators[0][institution]" placeholder="Institution">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Institution Address</label>
                                <input type="text" class="form-control" name="creators[0][institution_address]" placeholder="Institution Address">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Template for JavaScript -->
                <script type="text/template" id="creator-template">
                    <div class="creator-card" data-index="{index}">
                        <div class="creator-header">
                            <h6 class="creator-title">Creator {num}</h6>
                            <button type="button" class="btn-remove-creator" onclick="removeCreator(this)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" name="creators[{index}][first_name]" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" name="creators[{index}][last_name]" placeholder="Last Name" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="creators[{index}][email]" placeholder="Email">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Institution</label>
                                <input type="text" class="form-control" name="creators[{index}][institution]" placeholder="Institution">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Institution Address</label>
                                <input type="text" class="form-control" name="creators[{index}][institution_address]" placeholder="Institution Address">
                            </div>
                        </div>
                    </div>
                </script>

                <!-- Add More Button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn-add-more" id="addCreatorBtn">
                        ADD MORE <i class="bi bi-plus-lg ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.linking.paper')); ?>" class="btn-back">
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
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.3rem; margin-bottom: 1rem; }
    
    .note-box { background-color: #f8f9fa; border-left: 4px solid #0077b6; padding: 1rem 1.25rem; border-radius: 6px; }
    .note-box p { color: #555; margin: 0; }
    
    .creator-card { background: #fff; border: 1px solid #e9ecef; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; }
    .creator-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .creator-title { color: #0077b6; font-weight: 700; font-size: 1.1rem; margin: 0; }
    
    .btn-remove-creator { 
        background: transparent; border: 1px solid #dc3545; color: #dc3545; 
        width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; 
        cursor: pointer; transition: all 0.2s; 
    }
    .btn-remove-creator:hover:not(:disabled) { background: #dc3545; color: white; }
    .btn-remove-creator:disabled { opacity: 0.3; cursor: not-allowed; }
    
    .form-label { font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.4rem; }
    .form-control { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.7rem 1rem; font-size: 0.95rem; }
    .form-control:focus { border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,0.1); }
    
    .btn-add-more {
        background-color: #0077b6; color: white; border: none;
        padding: 0.75rem 2rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem;
        cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center;
    }
    .btn-add-more:hover { background-color: #005f73; }
    
    .form-navigation { display: flex; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem; }
    .btn-back { background-color: #fff; color: #dc3545; border: 1px solid #dc3545; font-weight: 700; padding: 0.75rem 2.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s; }
    .btn-back:hover { background-color: #dc3545; color: white; }
    .btn-next { background-color: #0077b6; color: white; font-weight: 700; padding: 0.75rem 2.5rem; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.2s; }
    .btn-next:hover { background-color: #005f73; }
    
    @media (max-width: 768px) { .container { padding: 1.5rem 1rem; } .form-card { padding: 1.25rem; } .form-navigation { flex-direction: column; } .btn-back, .btn-next { width: 100%; text-align: center; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let creatorCounter = 1;

document.getElementById('addCreatorBtn').addEventListener('click', function() {
    creatorCounter++;
    const container = document.getElementById('creatorsContainer');
    const template = document.getElementById('creator-template').innerHTML;
    const newRow = document.createElement('div');
    newRow.className = 'creator-card';
    // Replace placeholders with current index and number
    newRow.innerHTML = template.replace(/{index}/g, creatorCounter - 1).replace(/{num}/g, creatorCounter);
    container.appendChild(newRow);
    updateDeleteButtons();
});

function removeCreator(btn) {
    btn.closest('.creator-card').remove();
    updateDeleteButtons();
}

function updateDeleteButtons() {
    const cards = document.querySelectorAll('.creator-card');
    cards.forEach(card => {
        const btn = card.querySelector('.btn-remove-creator');
        if (cards.length === 1) {
            btn.disabled = true;
            btn.style.opacity = '0.3';
            btn.style.cursor = 'not-allowed';
        } else {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/creators.blade.php ENDPATH**/ ?>