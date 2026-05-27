<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_desc', 'UCI Machine Learning Repository'); ?>">
    <title><?php echo $__env->yieldContent('title', 'UCI Machine Learning Repository'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --uci-blue: #0077b6;
            --uci-dark-blue: #005f73;
            --uci-light-blue: #e9f5f9;
            --uci-yellow: #ffd60a;
            --uci-text: #333;
            --uci-gray: #6c757d;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #fafafa;
            color: var(--uci-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* ===== NAVBAR (Sama dengan app.blade.php) ===== */
        .navbar {
            background-color: #fff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 0.5rem 0;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .navbar-brand .brand-icon {
            width: 45px;
            height: 45px;
        }
        
        .navbar-brand .brand-text {
            font-size: 0.8rem;
            line-height: 1.2;
            color: var(--uci-blue);
            font-weight: 700;
        }
        
        .navbar-brand .brand-text span {
            display: block;
            font-size: 0.7rem;
            color: var(--uci-gray);
            font-weight: 400;
        }
        
        .nav-link {
            color: var(--uci-text) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link:hover {
            color: var(--uci-blue) !important;
        }
        
        /* About Us Dropdown Styling */
        .navbar .dropdown-menu {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            min-width: 220px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }
        
        .navbar .dropdown-item {
            padding: 0.75rem 1.25rem;
            color: var(--uci-text);
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        
        .navbar .dropdown-item:hover {
            background-color: var(--uci-light-blue);
            color: var(--uci-blue);
        }
        
        .navbar .dropdown-item-content {
            display: flex;
            flex-direction: column;
        }
        
        .navbar .dropdown-title {
            font-weight: 600;
            color: inherit;
        }
        
        .navbar .dropdown-divider {
            margin: 0.25rem 0;
            border-color: #f0f0f0;
        }
        
        .navbar .dropdown:hover > .dropdown-menu {
            display: block;
        }
        
        .navbar .dropdown-toggle::after {
            margin-left: 0.4rem;
            vertical-align: middle;
            border: none;
            content: "▼";
            font-size: 0.6rem;
            color: var(--uci-gray);
        }
        
        .navbar .dropdown-toggle:hover::after {
            color: var(--uci-blue);
        }
        
        .search-form .form-control {
            border: 2px solid #dee2e6;
            border-right: none;
            border-radius: 4px 0 0 4px;
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
            min-width: 200px;
        }
        
        .search-form .form-control:focus {
            border-color: var(--uci-blue);
            box-shadow: none;
        }
        
        .search-form .btn {
            background-color: var(--uci-blue);
            border: 2px solid var(--uci-blue);
            border-radius: 0 4px 4px 0;
            padding: 0.4rem 1rem;
            color: white;
        }
        
        .search-form .btn:hover {
            background-color: var(--uci-dark-blue);
            border-color: var(--uci-dark-blue);
        }
        
        /* User Avatar Dropdown */
        .user-avatar-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--uci-blue);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background-color 0.2s;
        }
        
        .user-avatar-btn:hover {
            background-color: var(--uci-dark-blue);
        }
        
        .user-dropdown .dropdown-menu {
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 180px;
        }
        
        .user-dropdown .dropdown-item {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: var(--uci-light-blue);
        }
        
        .user-dropdown .dropdown-item i {
            color: var(--uci-blue);
            font-size: 1rem;
        }
        
        /* ===== AUTH PAGE SPECIFIC ===== */
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background-color: #fafafa;
        }
        
        .auth-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        
        .auth-card h2 {
            color: var(--uci-blue);
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .auth-card .auth-subtitle {
            color: var(--uci-gray);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .auth-card .auth-subtitle a {
            color: var(--uci-blue);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-card .auth-subtitle a:hover {
            text-decoration: underline;
        }
        
        .auth-form .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #444;
            margin-bottom: 0.5rem;
        }
        
        .auth-form .form-control {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .auth-form .form-control:focus {
            border-color: var(--uci-blue);
            box-shadow: 0 0 0 3px rgba(0,119,182,0.15);
        }
        
        .auth-form .forgot-link {
            color: var(--uci-blue);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .auth-form .forgot-link:hover {
            text-decoration: underline;
        }
        
        .auth-form .btn-submit {
            background-color: var(--uci-blue);
            color: white;
            font-weight: 700;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.2s;
        }
        
        .auth-form .btn-submit:hover {
            background-color: var(--uci-dark-blue);
        }
        
        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--uci-gray);
            font-size: 0.85rem;
        }
        
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }
        
        .auth-divider span {
            padding: 0 1rem;
        }
        
        /* Social Buttons */
        .social-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 140px;
            padding: 0.65rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: white;
            color: var(--uci-text);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-social:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            color: var(--uci-text);
        }
        
        .btn-social svg {
            width: 20px;
            height: 20px;
        }
        
        .btn-github {
            background-color: #24292e;
            color: white;
            border-color: #24292e;
        }
        
        .btn-github:hover {
            background-color: #1a1e22;
            color: white;
        }
        
        /* Error styling */
        .auth-form .is-invalid {
            border-color: #dc3545;
        }
        
        .auth-form .invalid-feedback {
            font-size: 0.8rem;
        }
        
        .auth-alert {
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        /* ===== FOOTER (Sama dengan app.blade.php) ===== */
        footer {
            background-color: var(--uci-blue);
            color: white;
            margin-top: auto;
            padding: 3rem 0 2rem;
        }
        
        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .footer-logo {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }
        
        .footer-brand-text {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.4;
            color: white;
        }
        
        .footer-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1.25rem;
            color: white;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .footer-copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 3rem;
            padding-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .footer-copyright a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }
        
        .footer-copyright a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .search-form .form-control {
                min-width: 120px;
            }
        }
        
        @media (max-width: 768px) {
            footer {
                padding: 2rem 0 1.5rem;
            }
            
            .footer-brand {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .footer-section-title {
                text-align: center;
                margin-top: 1.5rem;
            }
            
            .footer-links {
                text-align: center;
            }
            
            .footer-copyright {
                margin-top: 2rem;
                padding-top: 1rem;
            }
            
            .auth-card {
                padding: 1.5rem;
            }
            
            .social-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-social {
                width: 100%;
                max-width: 200px;
            }
        }
        
        @media (max-width: 576px) {
            .nav-link {
                padding: 0.5rem !important;
                font-size: 0.85rem !important;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar (Sama persis dengan app.blade.php) -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <svg class="brand-icon" viewBox="0 0 50 50" fill="none">
                    <circle cx="25" cy="25" r="24" stroke="#0077b6" stroke-width="2"/>
                    <circle cx="15" cy="20" r="3" fill="#0077b6"/>
                    <circle cx="35" cy="18" r="3" fill="#0077b6"/>
                    <circle cx="25" cy="32" r="3" fill="#0077b6"/>
                    <line x1="15" y1="20" x2="35" y2="18" stroke="#0077b6" stroke-width="1.5"/>
                    <line x1="15" y1="20" x2="25" y2="32" stroke="#0077b6" stroke-width="1.5"/>
                    <line x1="35" y1="18" x2="25" y2="32" stroke="#0077b6" stroke-width="1.5"/>
                    <circle cx="20" cy="15" r="2" fill="#0077b6" opacity="0.5"/>
                    <circle cx="30" cy="25" r="2" fill="#0077b6" opacity="0.5"/>
                    <line x1="20" y1="15" x2="15" y2="20" stroke="#0077b6" stroke-width="1" opacity="0.5"/>
                    <line x1="20" y1="15" x2="35" y2="18" stroke="#0077b6" stroke-width="1" opacity="0.5"/>
                    <line x1="30" y1="25" x2="35" y2="18" stroke="#0077b6" stroke-width="1" opacity="0.5"/>
                    <line x1="30" y1="25" x2="25" y2="32" stroke="#0077b6" stroke-width="1" opacity="0.5"/>
                </svg>
                <div class="brand-text">
                    UC Irvine
                    <span>Machine Learning Repository</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Navbar Navigation -->
<ul class="navbar-nav me-auto">
    <?php if(auth()->guard()->check()): ?>
    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin'): ?>
        <li class="nav-item">
            <a class="nav-link text-danger fw-bold" href="<?php echo e(route('admin.dashboard')); ?>">
                <i class="bi bi-shield-lock-fill me-1"></i> Admin Panel
            </a>
        </li>
    <?php endif; ?>
<?php endif; ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('datasets.index')); ?>">Datasets</a>
    </li>
    
    <!-- Contribute Dataset Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="contributeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Contribute Dataset
        </a>
        <ul class="dropdown-menu" aria-labelledby="contributeDropdown">
            <li>
                <a class="dropdown-item" href="<?php echo e(route('contribute.policy')); ?>">
                    <div class="dropdown-item-content">
                        <span class="dropdown-title">Donate New</span>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?php echo e(route('contribute.linking.metadata')); ?>">
                    <div class="dropdown-item-content">
                        <span class="dropdown-title">Link External</span>
                    </div>
                </a>
            </li>
        </ul>
    </li>
    
    <!-- About Us Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?php echo e(route('about.who-we-are')); ?>" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            About Us
        </a>
        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
            <li>
                <a class="dropdown-item" href="<?php echo e(route('about.who-we-are')); ?>">
                    <div class="dropdown-item-content">
                        <span class="dropdown-title">Who We Are</span>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?php echo e(route('about.citation')); ?>">
                    <div class="dropdown-item-content">
                        <span class="dropdown-title">Citation Metadata</span>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?php echo e(route('about.contact')); ?>">
                    <div class="dropdown-item-content">
                        <span class="dropdown-title">Contact Information</span>
                    </div>
                </a>
            </li>
        </ul>
    </li>
</ul>
                <!-- Search Form -->
                <form class="d-flex search-form me-3" action="<?php echo e(route('datasets.index')); ?>" method="GET">
                    <input class="form-control" type="search" name="q" placeholder="Search datasets..." value="<?php echo e(request('q')); ?>">
                    <button class="btn" type="submit"><i class="bi bi-search"></i></button>
                </form>
                
                <!-- Auth Buttons -->
                <?php if(auth()->guard()->check()): ?>
                    <div class="dropdown user-dropdown">
                        <button class="user-avatar-btn" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-fill"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('profile')); ?>">
                                    <i class="bi bi-gear"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-sm" style="background-color: var(--uci-blue); color: white; border-radius: 6px;">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content (Auth Card Centered) -->
    <main class="auth-container">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer (Sama persis dengan app.blade.php) -->
    <footer>
        <div class="container">
            <div class="row">
                <!-- Brand Column -->
                <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                    <div class="footer-brand">
                        <svg class="footer-logo" viewBox="0 0 50 50" fill="none">
                            <circle cx="25" cy="25" r="24" stroke="white" stroke-width="2"/>
                            <circle cx="15" cy="20" r="3" fill="white"/>
                            <circle cx="35" cy="18" r="3" fill="white"/>
                            <circle cx="25" cy="32" r="3" fill="white"/>
                            <line x1="15" y1="20" x2="35" y2="18" stroke="white" stroke-width="1.5"/>
                            <line x1="15" y1="20" x2="25" y2="32" stroke="white" stroke-width="1.5"/>
                            <line x1="35" y1="18" x2="25" y2="32" stroke="white" stroke-width="1.5"/>
                            <circle cx="20" cy="15" r="2" fill="white" opacity="0.5"/>
                            <circle cx="30" cy="25" r="2" fill="white" opacity="0.5"/>
                        </svg>
                        <div class="footer-brand-text">
                            UC Irvine<br>
                            Machine Learning<br>
                            Repository
                        </div>
                    </div>
                </div>

                <!-- THE PROJECT Column -->
                <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                    <h5 class="footer-section-title">THE PROJECT</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('about.who-we-are')); ?>">About Us</a></li>
                        <li><a href="#">CML</a></li>
                        <li><a href="#">National Science Foundation</a></li>
                    </ul>
                </div>

                <!-- NAVIGATION Column -->
                <div class="col-lg-3 col-md-4 col-6 mb-4 mb-lg-0">
                    <h5 class="footer-section-title">NAVIGATION</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li><a href="<?php echo e(route('datasets.index')); ?>">View Datasets</a></li>
                        <li><a href="<?php echo e(route('contribute.policy')); ?>">Donate a Dataset</a></li>
                    </ul>
                </div>

                <!-- LOGISTICS Column -->
                <div class="col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
                    <h5 class="footer-section-title">LOGISTICS</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('about.contact')); ?>">Contact</a></li>
                        <li><a href="#">Privacy Notice</a></li>
                        <li><a href="#">Feature Request or Bug Report</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Section -->
            <div class="footer-copyright">
                <p class="mb-1">© <?php echo e(date('Y')); ?> UC Irvine Machine Learning Repository</p>
                <p class="mb-0">Donald Bren School of Information and Computer Sciences • University of California, Irvine</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script untuk dropdown hover (optional) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.navbar .dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('mouseenter', function() {
                    const bsDropdown = bootstrap.Dropdown.getOrCreateInstance(this.querySelector('.dropdown-toggle'));
                    bsDropdown.show();
                });
                dropdown.addEventListener('mouseleave', function() {
                    const bsDropdown = bootstrap.Dropdown.getOrCreateInstance(this.querySelector('.dropdown-toggle'));
                    bsDropdown.hide();
                });
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/layouts/auth.blade.php ENDPATH**/ ?>