
<?php $__env->startSection('title', 'User Management'); ?>
<?php $__env->startSection('page-title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Users</h2>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.users.export')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i>Add User
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-primary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total</h6>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['total'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Active</h6>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['active'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Inactive</h6>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['inactive'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Unverified</h6>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['unverified'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card border-info h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                    <i class="bi bi-shield-lock fs-4 text-info"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Admins</h6>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['admins'] ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts (Only if data exists) -->
<?php if(isset($registrationData) && $registrationData->isNotEmpty()): ?>
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>User Registration Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Role Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="roleChart"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" 
               placeholder="Search users..." value="<?php echo e(request('search')); ?>">
    </div>
    <div class="col-md-3">
        <select name="role" class="form-select form-select-sm">
            <option value="">All Roles</option>
            <option value="user" <?php echo e(request('role')=='user'?'selected':''); ?>>User</option>
            <option value="contributor" <?php echo e(request('role')=='contributor'?'selected':''); ?>>Contributor</option>
            <option value="admin" <?php echo e(request('role')=='admin'?'selected':''); ?>>Admin</option>
            <option value="superadmin" <?php echo e(request('role')=='superadmin'?'selected':''); ?>>Super Admin</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status')=='active'?'selected':''); ?>>Active</option>
            <option value="inactive" <?php echo e(request('status')=='inactive'?'selected':''); ?>>Inactive</option>
            <option value="unverified" <?php echo e(request('status')=='unverified'?'selected':''); ?>>Unverified</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
    </div>
</form>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="<?php echo e(route('admin.users.bulk-action')); ?>">
            <?php echo csrf_field(); ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll"></th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Datasets</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($user->id !== auth()->id()): ?>
                                <input type="checkbox" name="user_ids[]" value="<?php echo e($user->id); ?>">
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width:32px;height:32px;font-weight:600;">
                                        <?php echo e(strtoupper(substr($user->name,0,1))); ?>

                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="fw-semibold text-decoration-none">
                                            <?php echo e($user->name); ?>

                                        </a>
                                        <div class="small text-muted"><?php echo e($user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($user->role==='admin'?'primary':
                                    ($user->role==='superadmin'?'danger':
                                    ($user->role==='contributor'?'info':'secondary'))); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td><?php echo e($user->datasets_count ?? 0); ?></td>
                            <td>
                                <?php if($user->is_active): ?>
                                    <?php if($user->email_verified_at): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Unverified</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?php echo e($user->created_at?->format('M d, Y') ?? 'N/A'); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if($user->id !== auth()->id()): ?>
                                    <button type="button" 
                                            class="btn btn-outline-<?php echo e($user->is_active ? 'warning' : 'success'); ?>" 
                                            title="<?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>"
                                            onclick="toggleActive(<?php echo e($user->id); ?>)">
                                        <i class="bi bi-<?php echo e($user->is_active ? 'pause' : 'play'); ?>"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" onclick="confirmDelete('<?php echo e(route('admin.users.destroy', $user)); ?>')" 
                                            class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($users->count()): ?>
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <select name="action" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Bulk Actions...</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="demote">Demote to User</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Apply bulk action to selected users?')">
                        Apply
                    </button>
                </div>
                <?php echo e($users->withQueryString()->links()); ?>

            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if(isset($registrationData) && $registrationData->isNotEmpty()): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Registration Trend Chart
const regCtx = document.getElementById('registrationChart')?.getContext('2d');
if (regCtx) {
    new Chart(regCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($registrationData->pluck('month')); ?>,
            datasets: [{
                label: 'New Users',
                data: <?php echo json_encode($registrationData->pluck('count')); ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } }
        }
    });
}

// Role Distribution Chart
const roleCtx = document.getElementById('roleChart')?.getContext('2d');
if (roleCtx && <?php echo json_encode($roleData->isNotEmpty()); ?>) {
    new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($roleData->pluck('role')); ?>,
            datasets: [{
                data: <?php echo json_encode($roleData->pluck('count')); ?>,
                backgroundColor: ['#6c757d', '#17a2b8', '#007bff', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
}
</script>
<?php endif; ?>

<script>
// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="user_ids[]"]').forEach(cb => {
        if (!cb.disabled) cb.checked = this.checked;
    });
});

// Toggle active/inactive (menggunakan is_active, bukan banned_at)
function toggleActive(userId) {
    fetch(`/admin/users/${userId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to update user status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to connect to server');
    });
}

// Confirm delete
function confirmDelete(url) {
    if (confirm('Are you sure you want to delete this user?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/admin/users/index.blade.php ENDPATH**/ ?>