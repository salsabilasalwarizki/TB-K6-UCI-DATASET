
<?php $__env->startSection('title', 'Profile - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <a href="<?php echo e(route('profile')); ?>" class="nav-link active">
            <i class="bi bi-person-fill"></i> Profile
        </a>
        <a href="<?php echo e(route('profile.datasets')); ?>" class="nav-link">
            <i class="bi bi-grid-3x3-gap-fill"></i> Datasets
        </a>
        <a href="<?php echo e(route('profile.edits')); ?>" class="nav-link">
            <i class="bi bi-pencil-fill"></i> Edits
        </a>
    </aside>
    
    <!-- Content -->
    <div class="profile-content">
        <h1 class="welcome-header">Welcome, <span class="user-name"><?php echo e($user->name); ?></span></h1>
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="profile-card">
            <div class="profile-card-header">
                <div>
                    <i class="bi bi-person-circle me-2" style="font-size: 1.5rem; color: var(--uci-blue);"></i>
                    <h4 style="display: inline;">Profile</h4>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#editProfileForm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </button>
                </div>
            </div>
            
            <!-- Display Mode -->
            <div id="profileDisplay">
                <div class="profile-info-row">
                    <div class="profile-info-label">Name</div>
                    <div class="profile-info-value"><?php echo e($user->name); ?></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-label">Email</div>
                    <div class="profile-info-value"><?php echo e($user->email); ?></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-label">Permissions</div>
                    <div class="profile-info-value">basic</div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-label">Institution</div>
                    <div class="profile-info-value"><?php echo e($user->institution ?? 'Not specified'); ?></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-label">Institution Address</div>
                    <div class="profile-info-value"><?php echo e($user->institution_address ?? 'Not specified'); ?></div>
                </div>
            </div>
            
            <!-- Edit Form (Collapsed by default) -->
            <div class="collapse" id="editProfileForm">
                <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="p-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Institution</label>
                        <input type="text" name="institution" class="form-control" value="<?php echo e(old('institution', $user->institution)); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Institution Address</label>
                        <textarea name="institution_address" class="form-control" rows="2"><?php echo e(old('institution_address', $user->institution_address)); ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#editProfileForm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password Card -->
        <div class="profile-card mt-4">
            <div class="profile-card-header">
                <h4><i class="bi bi-lock me-2"></i>Change Password</h4>
            </div>
            <form action="<?php echo e(route('profile.password')); ?>" method="POST" class="p-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/profile/index.blade.php ENDPATH**/ ?>