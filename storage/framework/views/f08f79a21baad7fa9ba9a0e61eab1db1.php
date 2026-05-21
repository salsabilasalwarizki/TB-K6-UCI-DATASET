
<?php $__env->startSection('title', 'Edit Datasets - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <a href="<?php echo e(route('profile')); ?>" class="nav-link">
            <i class="bi bi-person-fill"></i> Profile
        </a>
        <a href="<?php echo e(route('profile.datasets')); ?>" class="nav-link">
            <i class="bi bi-grid-3x3-gap-fill"></i> Datasets
        </a>
        <a href="<?php echo e(route('profile.edits')); ?>" class="nav-link active">
            <i class="bi bi-pencil-fill"></i> Edits
        </a>
    </aside>
    
    <!-- Content -->
    <div class="profile-content">
        <div class="section-header">
            <i class="bi bi-pencil-fill"></i>
            <h2>Your Editable Datasets</h2>
        </div>
        
        <p class="text-muted mb-4">
            Below are your approved datasets that you can still edit. 
            Changes will be reviewed by admins before going live.
        </p>
        
        <?php if($datasets->isNotEmpty()): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Dataset</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Views</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if($dataset->thumbnail_url): ?>
                                        <img src="<?php echo e($dataset->thumbnail_url); ?>" 
                                             alt="<?php echo e($dataset->name); ?>" 
                                             class="rounded" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-database text-primary"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?php echo e(route('datasets.show', $dataset)); ?>" 
                                               class="fw-semibold text-decoration-none text-dark">
                                                <?php echo e(Str::limit($dataset->name, 35)); ?>

                                            </a>
                                            <?php if($dataset->subject_area): ?>
                                            <div class="small text-muted"><?php echo e($dataset->subject_area); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($dataset->status === 'approved' ? 'success' : 'info'); ?>">
                                        <?php echo e(ucfirst($dataset->status)); ?>

                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <?php echo e($dataset->updated_at?->diffForHumans() ?? 'N/A'); ?>

                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo e(number_format($dataset->view_count ?? 0)); ?>

                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('datasets.show', $dataset)); ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="View Public Page">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('contribute.edit.metadata', $dataset)); ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Edit Metadata">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Request Deletion"
                                                onclick="confirmDelete(<?php echo e($dataset->dataset_id); ?>, '<?php echo e($dataset->name); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($datasets->hasPages()): ?>
                <div class="card-footer bg-light">
                    <?php echo e($datasets->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-3"></i>
            <p class="text-muted mb-3">You don't have any approved datasets yet.</p>
            <p class="small text-muted mb-4">
                Once your donated datasets are approved by admins, they will appear here for editing.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-search me-2"></i>Browse Datasets
                </a>
                <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Donate Dataset
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to request deletion for <strong id="datasetName"></strong>?</p>
                <p class="small text-muted">This will notify admins to review your deletion request.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Request Deletion</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(datasetId, datasetName) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('datasetName').textContent = datasetName;
    document.getElementById('deleteForm').action = `/profile/dataset/${datasetId}`;
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/profile/edits.blade.php ENDPATH**/ ?>