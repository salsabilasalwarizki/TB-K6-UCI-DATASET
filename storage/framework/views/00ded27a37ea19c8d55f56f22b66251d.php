<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - UCI ML Repository</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --admin-sidebar-width: 250px;
            --admin-header-height: 60px;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0077b6 0%, #005f73 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .admin-sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        .admin-sidebar .nav-link i {
            width: 20px;
        }
        
        /* Main Content */
        .admin-main {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .admin-header {
            height: var(--admin-header-height);
            background: white;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* Content */
        .admin-content {
            padding: 2rem;
        }
        
        /* Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Status Badges */
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-approved { background-color: #198754; color: white; }
        .badge-rejected { background-color: #dc3545; color: white; }
        
        /* Table */
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .admin-table thead {
            background-color: #f8f9fa;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="brand">
            <h5 class="mb-0">
                <i class="bi bi-shield-lock me-2"></i>
                Admin Panel
            </h5>
            <small class="opacity-75">UCI ML Repository</small>
        </div>
        
        <nav class="mt-4">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="<?php echo e(route('admin.datasets.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.datasets.*') ? 'active' : ''); ?>">
                <i class="bi bi-database"></i>
                <span>Dataset Review</span>
                <?php $pendingCount = \App\Models\Dataset::where('status', 'pending')->count(); ?>
                <?php if($pendingCount > 0): ?>
                <span class="badge bg-warning text-dark ms-auto"><?php echo e($pendingCount); ?></span>
                <?php endif; ?>
            </a>
            
            <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
            
            <a href="<?php echo e(route('admin.statistics')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.statistics') ? 'active' : ''); ?>">
                <i class="bi bi-graph-up"></i>
                <span>Statistics</span>
            </a>
            
            <hr class="my-3 mx-3 opacity-25">
            
            <a href="<?php echo e(route('home')); ?>" class="nav-link">
                <i class="bi bi-house"></i>
                <span>Back to Website</span>
            </a>
            
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div>
                <button class="btn btn-link d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="d-none d-md-inline mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <?php $pendingCount = \App\Models\Dataset::where('status', 'pending')->count(); ?>
                        <?php if($pendingCount > 0): ?>
                        <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="margin-left: -10px;">
                            <?php echo e($pendingCount); ?>

                        </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('admin.datasets.index', ['status' => 'pending'])); ?>">
                            <?php echo e($pendingCount); ?> dataset(s) pending review
                        </a></li>
                    </ul>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                        </div>
                        <span class="d-none d-md-inline"><?php echo e(auth()->user()->name); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted"><?php echo e(auth()->user()->email); ?></span></li>
                        <li><span class="dropdown-item-text small"><span class="badge bg-primary"><?php echo e(ucfirst(auth()->user()->role)); ?></span></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="admin-content">
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show m-3" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/layouts/admin.blade.php ENDPATH**/ ?>