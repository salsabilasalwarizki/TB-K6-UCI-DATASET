<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_desc', 'UCI Machine Learning Repository'); ?>">
    <title><?php echo $__env->yieldContent('title', 'UCI Machine Learning Repository'); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
        }
        
        /* ===== NAVBAR ===== */
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

/* Show dropdown on hover (optional) */
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
        
        /* ===== PROFILE PAGE ===== */
        .profile-container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        .profile-sidebar {
            width: 220px;
            background-color: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 2rem 0;
            flex-shrink: 0;
        }
        
        .profile-sidebar .nav-link {
            color: var(--uci-text);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
        }
        
        .profile-sidebar .nav-link:hover {
            color: var(--uci-blue);
            background-color: var(--uci-light-blue);
        }
        
        .profile-sidebar .nav-link.active {
            color: var(--uci-blue);
            background-color: var(--uci-yellow);
            border-left-color: var(--uci-blue);
            font-weight: 700;
        }
        
        .profile-sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .profile-content {
            flex: 1;
            padding: 2rem 3rem;
            background-color: #fafafa;
        }
        
        .welcome-header {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        .welcome-header .user-name {
            color: var(--uci-blue);
        }
        
        .profile-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .profile-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .profile-card-header h4 {
            margin: 0;
            color: var(--uci-blue);
            font-weight: 700;
        }
        
        .profile-card-header .edit-btn {
            color: var(--uci-gray);
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .profile-card-header .edit-btn:hover {
            color: var(--uci-blue);
        }
        
        .profile-info-row {
            display: flex;
            padding: 1rem 2rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .profile-info-row:last-child {
            border-bottom: none;
        }
        
        .profile-info-label {
            width: 200px;
            font-weight: 600;
            color: var(--uci-text);
            font-size: 0.9rem;
        }
        
        .profile-info-value {
            flex: 1;
            color: var(--uci-gray);
            font-size: 0.9rem;
        }
        
        /* ===== DONATED DATASETS PAGE ===== */
        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .section-header i {
            font-size: 2rem;
            color: var(--uci-blue);
        }
        
        .section-header h2 {
            margin: 0;
            color: var(--uci-blue);
            font-weight: 700;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
        }
        
        .empty-state a {
            color: var(--uci-blue);
            text-decoration: none;
        }
        
        .empty-state a:hover {
            text-decoration: underline;
        }
        
        /* ===== DONATION POLICY ===== */
        .policy-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        
        .policy-container h1 {
            color: var(--uci-blue);
            font-weight: 700;
            margin-bottom: 2rem;
        }
        
        .policy-container h3 {
            color: var(--uci-blue);
            font-size: 1.2rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .policy-container ol {
            padding-left: 1.5rem;
        }
        
        .policy-container ol li {
            margin-bottom: 1rem;
            line-height: 1.8;
            color: #555;
        }
        
        /* ===== CONTRIBUTE FORM ===== */
        .contribute-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .form-section {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .form-section h5 {
            color: var(--uci-blue);
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--uci-light-blue);
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #444;
        }
        
        .btn-submit {
            background-color: var(--uci-yellow);
            color: #000;
            font-weight: 700;
            padding: 0.75rem 2.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .btn-submit:hover {
            background-color: #ffc300;
            color: #000;
        }
        
        .btn-cancel {
            background-color: #e0e0e0;
            color: #555;
            font-weight: 600;
            padding: 0.75rem 2.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .btn-cancel:hover {
            background-color: #d0d0d0;
            color: #333;
        }
        
        /* ===== DATASET CARD (Profile) ===== */
        .dataset-mini-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dataset-mini-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .dataset-mini-title {
            font-weight: 600;
            color: var(--uci-blue);
        }
        
        .dataset-mini-meta {
            font-size: 0.8rem;
            color: var(--uci-gray);
        }
        
        /* ===== BANNER NOTICE ===== */
        .banner-notice {
            background-color: var(--uci-light-blue);
            border: 1px solid var(--uci-blue);
            border-radius: 6px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .banner-notice .btn {
            background-color: var(--uci-blue);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.5rem 1.25rem;
        }
        
        /* ===== WELCOME SECTION ===== */
        .welcome-section {
            text-align: center;
            padding: 2rem 0;
        }
        
        .welcome-section h1 {
            color: var(--uci-blue);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }
        
        .welcome-section p {
            color: var(--uci-gray);
            font-size: 1rem;
            max-width: 800px;
            margin: 0 auto 1.5rem;
        }
        
        .btn-view {
            background-color: var(--uci-blue);
            color: white;
            font-weight: 700;
            padding: 0.65rem 2rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-view:hover {
            background-color: var(--uci-dark-blue);
            color: white;
        }
        
        .btn-contribute-nav {
            background-color: var(--uci-yellow);
            color: #000;
            font-weight: 700;
            padding: 0.65rem 2rem;
            border: 2px solid var(--uci-yellow);
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-contribute-nav:hover {
            background-color: #ffc300;
            border-color: #ffc300;
            color: #000;
        }
        
        /* ===== DATASETS GRID ===== */
        .datasets-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .datasets-section {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .section-title {
            color: var(--uci-blue);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        
        .dataset-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            transition: box-shadow 0.2s;
        }
        
        .dataset-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: inherit;
        }
        
        .dataset-icon {
            width: 45px;
            height: 45px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            background-color: var(--uci-blue);
            color: white;
        }
        
        .dataset-card-title {
            font-weight: 600;
            color: var(--uci-blue);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }
        
        .dataset-card-desc {
            color: var(--uci-gray);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .dataset-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            font-size: 0.8rem;
            color: #555;
        }

        
        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.6rem;
            background-color: var(--uci-light-blue);
            color: var(--uci-blue);
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        /* ===== FOOTER ===== */
footer {
    background-color: var(--uci-blue);
    color: white;
    margin-top: 4rem;
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

/* Responsive Footer */
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
}
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .datasets-container {
                grid-template-columns: 1fr;
            }
            .profile-container {
                flex-direction: column;
            }
            .profile-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
                padding: 1rem 0;
            }
            .profile-sidebar .nav-link {
                border-left: none;
                border-bottom: 3px solid transparent;
            }
            .profile-sidebar .nav-link.active {
                border-left: none;
                border-bottom-color: var(--uci-blue);
                background-color: var(--uci-yellow);
            }
            .profile-content {
                padding: 1.5rem;
            }
            .welcome-header {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .search-form .form-control {
                min-width: 120px;
            }
            .nav-link {
                padding: 0.5rem !important;
                font-size: 0.85rem !important;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar -->
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
                <form class="d-flex search-form me-3" action="<?php echo e(route('datasets.index')); ?>" method="GET">
                    <input 
        class="form-control" 
        type="search" 
        name="q" 
        placeholder="Search datasets..." 
        value="<?php echo e(request('q')); ?>"
        aria-label="Search datasets">
    <button class="btn" type="submit" aria-label="Search">
        <i class="bi bi-search"></i>
    </button>
                </form>
                
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
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/layouts/app.blade.php ENDPATH**/ ?>