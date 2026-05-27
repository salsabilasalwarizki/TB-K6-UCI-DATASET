
<?php $__env->startSection('title', 'Browse Datasets'); ?>
<?php $__env->startSection('meta_desc', 'Explore our collection of machine learning datasets'); ?>

<?php $__env->startSection('content'); ?>
<div class="datasets-page">
    <!-- Page Header -->
    <header class="page-header bg-gradient-primary text-white py-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-2">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Browse Datasets
                    </h1>
                    <p class="lead mb-0 opacity-90">
                        Discover <?php echo e(number_format($datasets->total() ?? 0)); ?> datasets for your machine learning projects
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="<?php echo e(route('contribute.policy')); ?>" class="btn btn-light btn-lg fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Contribute Dataset
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row g-4">
            
            <!-- Filters Sidebar -->
            <aside class="col-lg-3 col-md-4">
                <div class="filters-card card border-0 shadow-sm sticky-top" style="top: 2rem; z-index: 100;">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold text-primary">
                                <i class="bi bi-funnel me-2"></i>Filters
                            </h5>
                            <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#mobileFilters">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-3" id="mobileFilters">
                        <form method="GET" action="<?php echo e(route('datasets.index')); ?>" id="filterForm">
                            
                            <!-- Search -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <label class="form-label small fw-semibold text-muted mb-2">Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" 
                                           name="search" placeholder="Search datasets..."
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                            </div>
                            
                            <!-- Keywords -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <div class="filter-toggle d-flex justify-content-between align-items-center mb-2" 
                                     data-bs-toggle="collapse" data-bs-target="#kwCollapse">
                                    <label class="form-label small fw-semibold text-muted mb-0">Keywords</label>
                                    <i class="bi bi-chevron-down text-muted transition-icon"></i>
                                </div>
                                <div id="kwCollapse" class="collapse show">
                                    <input type="text" class="form-control form-control-sm mb-2" 
                                           placeholder="Filter keywords..." id="keywordSearch">
                                    <div class="keywords-scroll" style="max-height: 120px; overflow-y: auto;">
                                        <?php $__empty_1 = true; $__currentLoopData = $keywords->take(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="form-check form-check-sm keyword-item">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="keywords[]" value="<?php echo e($keyword->keyword_id); ?>"
                                                   id="kw_<?php echo e($keyword->keyword_id); ?>"
                                                   <?php echo e(in_array($keyword->keyword_id, request('keywords', [])) ? 'checked' : ''); ?>>
                                            <label class="form-check-label small" for="kw_<?php echo e($keyword->keyword_id); ?>">
                                                <?php echo e($keyword->keyword_name); ?>

                                                <span class="badge bg-light text-dark ms-1"><?php echo e($keyword->datasets_count ?? 0); ?></span>
                                            </label>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <small class="text-muted">No keywords</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Data Type -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <div class="filter-toggle d-flex justify-content-between align-items-center mb-2"
                                     data-bs-toggle="collapse" data-bs-target="#typeCollapse">
                                    <label class="form-label small fw-semibold text-muted mb-0">Data Type</label>
                                    <i class="bi bi-chevron-down text-muted transition-icon"></i>
                                </div>
                                <div id="typeCollapse" class="collapse show">
                                    <?php $dataTypes = ['Multivariate','Text','Image','Time-Series','Sequential','Tabular','Relational','Domain-Theory','Data-Generator','Univariate']; ?>
                                    <?php $__currentLoopData = $dataTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-sm">
                                        <input class="form-check-input" type="radio" name="data_type" 
                                               value="<?php echo e($type); ?>" id="dt_<?php echo e(str_replace('-', '_', strtolower($type))); ?>"
                                               <?php echo e(request('data_type') == $type ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="dt_<?php echo e(str_replace('-', '_', strtolower($type))); ?>">
                                            <?php echo e($type); ?>

                                            <span class="badge bg-light text-dark ms-1"><?php echo e($stats['data_type_counts'][$type] ?? 0); ?></span>
                                        </label>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            
                            <!-- Subject Area -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <div class="filter-toggle d-flex justify-content-between align-items-center mb-2"
                                     data-bs-toggle="collapse" data-bs-target="#areaCollapse">
                                    <label class="form-label small fw-semibold text-muted mb-0">Subject Area</label>
                                    <i class="bi bi-chevron-down text-muted transition-icon"></i>
                                </div>
                                <div id="areaCollapse" class="collapse show">
                                    <input type="text" class="form-control form-control-sm mb-2" 
                                           placeholder="Filter areas..." id="subjectSearch">
                                    <div class="areas-scroll" style="max-height: 120px; overflow-y: auto;">
                                        <?php $__empty_1 = true; $__currentLoopData = $subjectAreas->take(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="form-check form-check-sm subject-item">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="subject_area[]" value="<?php echo e($area->area_name); ?>"
                                                   id="sa_<?php echo e($area->area_id); ?>"
                                                   <?php echo e(in_array($area->area_name, request('subject_area', [])) ? 'checked' : ''); ?>>
                                            <label class="form-check-label small" for="sa_<?php echo e($area->area_id); ?>">
                                                <?php echo e($area->area_name); ?>

                                                <span class="badge bg-light text-dark ms-1"><?php echo e($area->datasets_count ?? 0); ?></span>
                                            </label>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <small class="text-muted">No areas</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Task Type -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <div class="filter-toggle d-flex justify-content-between align-items-center mb-2"
                                     data-bs-toggle="collapse" data-bs-target="#taskCollapse">
                                    <label class="form-label small fw-semibold text-muted mb-0">Task Type</label>
                                    <i class="bi bi-chevron-down text-muted transition-icon"></i>
                                </div>
                                <div id="taskCollapse" class="collapse show">
                                    <?php $taskTypes = ['Classification','Regression','Clustering','Causal-Discovery','Relational-Learning','Other']; ?>
                                    <?php $__currentLoopData = $taskTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check form-check-sm">
                                        <input class="form-check-input" type="radio" name="task_type" 
                                               value="<?php echo e($task); ?>" id="task_<?php echo e(str_replace('-', '_', strtolower($task))); ?>"
                                               <?php echo e(request('task_type') == $task ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="task_<?php echo e(str_replace('-', '_', strtolower($task))); ?>">
                                            <?php echo e($task); ?>

                                            <span class="badge bg-light text-dark ms-1"><?php echo e($stats['task_type_counts'][$task] ?? 0); ?></span>
                                        </label>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            
                            <!-- Instances Range -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <label class="form-label small fw-semibold text-muted mb-2"># Instances</label>
                                <div class="d-flex gap-2 mb-2">
                                    <input type="number" class="form-control form-control-sm" name="instances_min" 
                                           placeholder="Min" value="<?php echo e(request('instances_min')); ?>" min="0">
                                    <input type="number" class="form-control form-control-sm" name="instances_max" 
                                           placeholder="Max" value="<?php echo e(request('instances_max')); ?>" min="0">
                                </div>
                                <input type="range" class="form-range" name="instances_range" min="0" 
                                       max="<?php echo e($stats['max_instances'] ?? 100000); ?>"
                                       value="<?php echo e(request('instances_range', $stats['max_instances'] ?? 100000)); ?>"
                                       id="instancesRange" oninput="updateRangeValue('instances', this.value)">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>0</span>
                                    <span id="instancesValue"><?php echo e(number_format(request('instances_range', $stats['max_instances'] ?? 100000))); ?></span>
                                    <span><?php echo e(number_format($stats['max_instances'] ?? 100000)); ?></span>
                                </div>
                            </div>
                            
                            <!-- Features Range -->
                            <div class="filter-group mb-3 pb-3 border-bottom">
                                <label class="form-label small fw-semibold text-muted mb-2"># Features</label>
                                <div class="d-flex gap-2 mb-2">
                                    <input type="number" class="form-control form-control-sm" name="features_min" 
                                           placeholder="Min" value="<?php echo e(request('features_min')); ?>" min="0">
                                    <input type="number" class="form-control form-control-sm" name="features_max" 
                                           placeholder="Max" value="<?php echo e(request('features_max')); ?>" min="0">
                                </div>
                                <input type="range" class="form-range" name="features_range" min="0" 
                                       max="<?php echo e($stats['max_features'] ?? 1000); ?>"
                                       value="<?php echo e(request('features_range', $stats['max_features'] ?? 1000)); ?>"
                                       id="featuresRange" oninput="updateRangeValue('features', this.value)">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>0</span>
                                    <span id="featuresValue"><?php echo e(number_format(request('features_range', $stats['max_features'] ?? 1000))); ?></span>
                                    <span><?php echo e(number_format($stats['max_features'] ?? 1000)); ?></span>
                                </div>
                            </div>
                            
                            <!-- Quick Filters -->
                            <div class="filter-group mb-3">
                                <label class="form-label small fw-semibold text-muted mb-2">Quick Filters</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <label class="form-check form-check-sm">
                                        <input class="form-check-input" type="checkbox" name="has_missing" value="1"
                                               id="hasMissing" <?php echo e(request('has_missing') ? 'checked' : ''); ?>>
                                        <span class="form-check-label small">Has Missing Values</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-funnel me-1"></i>Apply Filters
                                </button>
                                <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>Clear All
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
            <!-- Tambahkan di atas datasets container -->
<?php if(request('q')): ?>
<div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
    <div>
        <i class="bi bi-search me-2"></i>
        <strong>Search results for:</strong> "<?php echo e(request('q')); ?>"
        <span class="text-muted ms-2">(<?php echo e($datasets->total()); ?> found)</span>
    </div>
    <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-x-circle me-1"></i>Clear Search
    </a>
</div>
<?php endif; ?>
            <!-- Main Content -->
            <main class="col-lg-9 col-md-8">
                
                <!-- Toolbar -->
                <div class="toolbar d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <!-- Sort Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="bi bi-sort-down me-1"></i>
                            Sort: <?php echo e($sortLabels[request('sort', 'view_count')] ?? '# Views'); ?>

                        </button>
                        <ul class="dropdown-menu dropdown-menu-sm">
                            <li><h6 class="dropdown-header">Sort By</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item <?php echo e(request('sort') == 'view_count' ? 'active' : ''); ?>" 
                                   href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'view_count', 'order' => 'desc'])); ?>">
                                <i class="bi bi-eye me-2"></i>Most Viewed
                            </a></li>
                            <li><a class="dropdown-item <?php echo e(request('sort') == 'download_count' ? 'active' : ''); ?>" 
                                   href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'download_count', 'order' => 'desc'])); ?>">
                                <i class="bi bi-cloud-download me-2"></i>Most Downloaded
                            </a></li>
                            <li><a class="dropdown-item <?php echo e(request('sort') == 'citation_count' ? 'active' : ''); ?>" 
                                   href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'citation_count', 'order' => 'desc'])); ?>">
                                <i class="bi bi-chat-quote me-2"></i>Most Cited
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item <?php echo e(request('sort') == 'name' ? 'active' : ''); ?>" 
                                   href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc'])); ?>">
                                <i class="bi bi-sort-alpha-down me-2"></i>Name A-Z
                            </a></li>
                            <li><a class="dropdown-item <?php echo e(request('sort') == 'created_at' ? 'active' : ''); ?>" 
                                   href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc'])); ?>">
                                <i class="bi bi-clock me-2"></i>Newest First
                            </a></li>
                        </ul>
                    </div>
                    
                    <!-- View Toggle & Expand -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted d-none d-sm-inline">View:</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary <?php echo e(request('view') != 'grid' ? 'active' : ''); ?>" 
                                    onclick="setViewMode('list')" title="List View">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary <?php echo e(request('view') == 'grid' ? 'active' : ''); ?>" 
                                    onclick="setViewMode('grid')" title="Grid View">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleAllCards()">
                            <i class="bi bi-arrows-expand me-1"></i>
                            <span class="d-none d-sm-inline">Expand All</span>
                        </button>
                    </div>
                </div>
                
                <!-- Active Filters -->
                <?php if(request()->except('page')): ?>
                <div class="active-filters mb-4">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="small text-muted fw-semibold">Active:</span>
                        <?php $__currentLoopData = request()->except('page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(is_array($value)): ?>
                                <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-inline-flex align-items-center gap-1 py-1 px-2">
                                    <?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($v); ?>

                                    <a href="<?php echo e(request()->fullUrlWithQuery([$key => array_filter(request($key, []), fn($x) => $x != $v)])); ?>" 
                                       class="text-reset ms-1 text-decoration-none">&times;</a>
                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php elseif($value): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-inline-flex align-items-center gap-1 py-1 px-2">
                                <?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($value); ?>

                                <a href="<?php echo e(request()->fullUrlWithQuery([$key => null])); ?>" 
                                   class="text-reset ms-1 text-decoration-none">&times;</a>
                            </span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Datasets Grid/List -->
                <div class="datasets-container <?php echo e(request('view') == 'grid' ? 'row g-3' : ''); ?>" id="datasetsContainer">
                    <?php $__empty_1 = true; $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="dataset-card card h-100 border-0 shadow-sm hover-lift <?php echo e(request('view') == 'grid' ? 'col-md-6 col-lg-4' : ''); ?>" 
                             data-dataset-id="<?php echo e($dataset->dataset_id); ?>">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3">
                                <!-- Thumbnail -->
                                <div class="flex-shrink-0">
                                    <div class="dataset-thumbnail position-relative rounded overflow-hidden" 
                                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <?php if($dataset->thumbnail_url): ?>
                                            <img src="<?php echo e($dataset->thumbnail_url); ?>" 
                                                 alt="<?php echo e($dataset->display_name ?? $dataset->name); ?>" 
                                                 class="w-100 h-100 object-fit-cover">
                                        <?php elseif($dataset->large_image_url): ?>
                                            <img src="<?php echo e($dataset->large_image_url); ?>" 
                                                 alt="<?php echo e($dataset->display_name ?? $dataset->name); ?>" 
                                                 class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                                <i class="bi bi-database fs-4"></i>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($dataset->status !== 'available'): ?>
                                        <span class="position-absolute top-0 end-0 badge <?php echo e($dataset->status === 'pending' ? 'bg-warning' : 'bg-secondary'); ?> rounded-pill" 
                                              style="font-size: 0.65rem;">
                                            <?php echo e(ucfirst($dataset->status)); ?>

                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-grow-1 min-w-0">
                                    <!-- Title & Badges -->
                                    <h6 class="card-title mb-1 text-truncate">
                                        <a href="<?php echo e(route('datasets.show', $dataset)); ?>" 
                                           class="text-decoration-none text-dark fw-semibold stretched-link">
                                            <?php echo e($dataset->display_name ?? $dataset->name); ?>

                                        </a>
                                    </h6>
                                    
                                    <div class="badges mb-2">
                                        <?php if($dataset->data_type): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle me-1"><?php echo e($dataset->data_type); ?></span>
                                        <?php endif; ?>
                                        <?php if($dataset->task_type): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo e($dataset->task_type); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Description -->
                                    <p class="card-text small text-muted mb-2 line-clamp-2">
                                        <?php echo e(Str::limit($dataset->abstract ?? $dataset->description, request('view') == 'grid' ? 60 : 100)); ?>

                                    </p>
                                    
                                    <!-- Stats -->
                                    <div class="stats d-flex flex-wrap gap-3 small text-muted mb-2">
                                        <?php if($dataset->num_instances !== null): ?>
                                        <span title="Instances"><i class="bi bi-table me-1"></i><?php echo e($dataset->num_instances >= 1000000 ? number_format($dataset->num_instances / 1000000, 1) . 'M' : number_format($dataset->num_instances)); ?></span>
                                        <?php endif; ?>
                                        <?php if($dataset->num_features !== null): ?>
                                        <span title="Features"><i class="bi bi-grid-3x3-gap me-1"></i><?php echo e(number_format($dataset->num_features)); ?></span>
                                        <?php endif; ?>
                                        <?php if($dataset->num_classes !== null): ?>
                                        <span title="Classes"><i class="bi bi-diagram-3 me-1"></i><?php echo e($dataset->num_classes); ?></span>
                                        <?php endif; ?>
                                        <?php if($dataset->has_missing_values): ?>
                                        <span class="text-warning" title="Has missing values"><i class="bi bi-exclamation-triangle"></i></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Meta -->
                                    <div class="meta small text-muted mb-2">
                                        <?php if($dataset->subject_area): ?>
                                        <span><i class="bi bi-folder me-1"></i><?php echo e($dataset->subject_area); ?></span>
                                        <?php endif; ?>
                                        <?php if($dataset->donated_date): ?>
                                        <span class="ms-2"><i class="bi bi-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($dataset->donated_date)->format('M Y')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="actions d-flex gap-2 mt-auto pt-2">
                                        <a href="<?php echo e(route('datasets.show', $dataset)); ?>" 
                                           class="btn btn-sm btn-outline-primary flex-grow-1 stretched-link">
                                            View
                                        </a>
                                        <?php $defaultFile = $dataset->files->where('pivot.is_default', 1)->first() ?? $dataset->files->first(); ?>
                                        <?php if($defaultFile): ?>
                                        <a href="<?php echo e(asset('storage/' . $defaultFile->file_path)); ?>" 
                                           class="btn btn-sm btn-outline-success" download
                                           title="Download <?php echo e($defaultFile->original_filename ?? $defaultFile->filename); ?>">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                onclick="toggleCard(<?php echo e($dataset->dataset_id); ?>)"
                                                title="Toggle Details">
                                            <i class="bi bi-chevron-down toggle-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Expanded Details -->
                            <div class="dataset-details collapse mt-3 pt-3 border-top" id="details-<?php echo e($dataset->dataset_id); ?>">
                                <div class="row g-3">
                                    <!-- Variables Preview -->
                                    <div class="col-md-6">
                                        <?php if($dataset->variables->isNotEmpty()): ?>
                                        <h6 class="small fw-semibold text-primary mb-2">
                                            <i class="bi bi-list-columns me-1"></i>Variables
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0" style="font-size: 0.8rem;">
                                                <tbody>
                                                    <?php $__currentLoopData = $dataset->variables->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="py-1 pe-2"><?php echo e($var->display_name ?? $var->variable_name); ?></td>
                                                        <td class="py-1"><span class="badge bg-light border"><?php echo e($var->variable_type); ?></span></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if($dataset->variables->count() > 4): ?>
                                        <small class="text-muted">+<?php echo e($dataset->variables->count() - 4); ?> more</small>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    <div class="col-md-6">
                                        <?php if($dataset->descriptionDetails): ?>
                                        <h6 class="small fw-semibold text-primary mb-2">
                                            <i class="bi bi-info-circle me-1"></i>Info
                                        </h6>
                                        <?php if($dataset->descriptionDetails->purpose): ?>
                                        <p class="small mb-1"><strong>Purpose:</strong> <?php echo e(Str::limit($dataset->descriptionDetails->purpose, 80)); ?></p>
                                        <?php endif; ?>
                                        <?php if($dataset->descriptionDetails->instances_represent): ?>
                                        <p class="small mb-0"><strong>Instances:</strong> <?php echo e(Str::limit($dataset->descriptionDetails->instances_represent, 80)); ?></p>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if($dataset->keywords->isNotEmpty()): ?>
                                        <h6 class="small fw-semibold text-primary mb-2 mt-2">
                                            <i class="bi bi-tags me-1"></i>Keywords
                                        </h6>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php $__currentLoopData = $dataset->keywords->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('datasets.index', ['keywords[]' => $keyword->keyword_id])); ?>" 
                                               class="badge bg-light border text-decoration-none text-dark small">
                                                <?php echo e($keyword->keyword_name); ?>

                                            </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="d-flex gap-2 mt-3 pt-2 border-top">
                                    <button class="btn btn-sm btn-outline-primary" onclick="importInPython(<?php echo e($dataset->dataset_id); ?>)">
                                        <i class="bi bi-code-slash me-1"></i>Python
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="showCitation(<?php echo e($dataset->dataset_id); ?>, '<?php echo e(addslashes($dataset->name)); ?>')">
                                        <i class="bi bi-quote me-1"></i>Cite
                                    </button>
                                    <?php if(auth()->check()): ?>
                                    <button class="btn btn-sm btn-outline-info" onclick="addToCollection(<?php echo e($dataset->dataset_id); ?>)">
                                        <i class="bi bi-bookmark me-1"></i>Save
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <i class="bi bi-search display-1 text-muted opacity-25"></i>
                            </div>
                            <h4 class="text-muted mb-2">No datasets found</h4>
                            <p class="text-muted mb-4">Try adjusting your filters or search terms</p>
                            <a href="<?php echo e(route('datasets.index')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-x-circle me-2"></i>Clear All Filters
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if($datasets->hasPages()): ?>
                <nav class="mt-4" aria-label="Dataset pagination">
                    <ul class="pagination pagination-sm justify-content-center">
                        
                        <?php if($datasets->onFirstPage()): ?>
                            <li class="page-item disabled"><span class="page-link">‹</span></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($datasets->previousPageUrl()); ?>" rel="prev">‹</a></li>
                        <?php endif; ?>
                        <!-- Pagination Section -->
<?php if($datasets->hasPages()): ?>
<div class="mt-4 d-flex justify-content-center">
    <?php echo e($datasets->appends(request()->query())->links()); ?>

</div>
<?php endif; ?>
                        
                        
                        <?php $__currentLoopData = $datasets->getUrlRange(1, $datasets->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $datasets->currentPage()): ?>
                                <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
                            <?php else: ?>
                                <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        
                        <?php if($datasets->hasMorePages()): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($datasets->nextPageUrl()); ?>" rel="next">›</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><span class="page-link">›</span></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                
                <!-- Results Count -->
                <div class="text-center text-muted small mt-3">
                    Showing <?php echo e($datasets->firstItem() ?? 0); ?>–<?php echo e($datasets->lastItem() ?? 0); ?> of <?php echo e($datasets->total()); ?> datasets
                </div>
                
            </main>
        </div>
    </div>
</div>

<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Cite Dataset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <input type="hidden" id="citationDatasetId">
                
                <div class="mb-4">
                    <h6 class="small fw-semibold text-muted mb-2">BibTeX</h6>
                    <pre class="bg-light p-3 rounded small mb-0 overflow-auto" style="max-height: 200px;" id="bibtexCode"><code>@dataset{placeholder,
  title = {Dataset Name},
  author = {Dataset Contributors},
  year = {2026},
  url = {https://example.com/dataset},
  note = {Accessed: <?php echo e(date('Y-m-d')); ?>}
}</code></pre>
                    <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyCitation()">
                        <i class="bi bi-clipboard me-1"></i>Copy BibTeX
                    </button>
                </div>
                
                <div>
                    <h6 class="small fw-semibold text-muted mb-2">APA Style</h6>
                    <p class="bg-light p-3 rounded small mb-0" id="apaCitation">
                        Dataset Contributors. (2026). <em>Dataset Name</em>. https://example.com/dataset
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ===== Page Layout ===== */
    .datasets-page {
        min-height: 100vh;
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .page-header.bg-gradient-primary {
        background: linear-gradient(135deg, #0077b6 0%, #005f73 100%) !important;
    }
    
    /* ===== Filters Card ===== */
    .filters-card {
        border-radius: 12px;
        transition: box-shadow 0.2s ease;
    }
    
    .filters-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    }
    
    .filter-group {
        transition: opacity 0.2s ease;
    }
    
    .filter-toggle {
        cursor: pointer;
        user-select: none;
    }
    
    .filter-toggle:hover .transition-icon {
        transform: rotate(180deg);
        transition: transform 0.2s ease;
    }
    
    .form-check-sm .form-check-input {
        width: 1rem;
        height: 1rem;
        margin-top: 0.15rem;
    }
    
    .form-check-sm .form-check-label {
        padding-left: 0.3rem;
    }
    
    .keywords-scroll, .areas-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    
    .keywords-scroll::-webkit-scrollbar,
    .areas-scroll::-webkit-scrollbar {
        width: 4px;
    }
    
    .keywords-scroll::-webkit-scrollbar-track,
    .areas-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .keywords-scroll::-webkit-scrollbar-thumb,
    .areas-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
    
    /* ===== Dataset Cards ===== */
    .dataset-card {
        border-radius: 12px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: visible;
    }
    
    .hover-lift {
        will-change: transform, box-shadow;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
        z-index: 5;
    }
    
    .dataset-thumbnail {
        transition: transform 0.3s ease;
    }
    
    .hover-lift:hover .dataset-thumbnail {
        transform: scale(1.02);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .stats span, .meta span {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    
    .toggle-icon {
        transition: transform 0.3s ease;
        display: inline-block;
    }
    
    .toggle-icon.rotated {
        transform: rotate(180deg);
    }
    
    /* ===== Expanded Details ===== */
    .dataset-details {
        background: linear-gradient(180deg, rgba(248,249,250,0.8) 0%, transparent 100%);
        border-radius: 0 0 12px 12px;
        margin: 0 -1rem -1rem -1rem;
        padding: 1rem !important;
    }
    
    /* ===== Active Filters ===== */
    .active-filters .badge {
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .active-filters .badge:hover {
        background: #dcfce7 !important;
        border-color: #22c55e !important;
        transform: translateY(-1px);
    }
    
    .active-filters .badge a {
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }
    
    .active-filters .badge a:hover {
        opacity: 1;
        color: inherit !important;
    }
    
    /* ===== Pagination ===== */
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #0077b6 0%, #005f73 100%);
        border: none;
        box-shadow: 0 4px 12px rgba(0,119,182,0.3);
    }
    
    .pagination .page-link:hover:not(.disabled) {
        background: #e2e8f0;
        transform: translateY(-1px);
    }
    
    /* ===== Toolbar ===== */
    .toolbar {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .dropdown-menu {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        padding: 0.5rem 0;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: background 0.15s ease;
    }
    
    .dropdown-item:hover,
    .dropdown-item.active {
        background: linear-gradient(135deg, rgba(0,119,182,0.1) 0%, rgba(0,95,115,0.1) 100%);
        color: #005f73;
    }
    
    .btn-group-sm .btn {
        padding: 0.35rem 0.6rem;
    }
    
    /* ===== Responsive ===== */
    @media (max-width: 991px) {
        .filters-card {
            position: static !important;
            margin-bottom: 1rem;
        }
        
        #mobileFilters {
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .dataset-card .stretched-link::after {
            z-index: 1;
        }
    }
    
    @media (max-width: 767px) {
        .page-header .container {
            text-align: center;
        }
        
        .page-header .col-lg-4 {
            text-align: center !important;
        }
        
        .toolbar {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem;
        }
        
        .datasets-container .dataset-card {
            margin: 0 !important;
            width: 100% !important;
        }
    }
    
    /* ===== Animations ===== */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .dataset-card {
        animation: fadeIn 0.3s ease forwards;
        animation-delay: calc(var(--i, 0) * 0.05s);
    }
    
    /* ===== Loading State ===== */
    .loading-skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    /* ===== Utility Classes ===== */
    .object-fit-cover { object-fit: cover; }
    .min-w-0 { min-width: 0; }
    .line-clamp-2 { 
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ===== Range Sliders =====
function updateRangeValue(type, value) {
    const el = document.getElementById(type + 'Value');
    if (el) el.textContent = parseInt(value).toLocaleString();
}

document.addEventListener('DOMContentLoaded', function() {
    ['instances', 'features'].forEach(type => {
        const range = document.getElementById(type + 'Range');
        const display = document.getElementById(type + 'Value');
        if (range && display) {
            display.textContent = parseInt(range.value).toLocaleString();
        }
    });
});

// ===== Card Toggle =====
function toggleCard(datasetId) {
    const details = document.getElementById('details-' + datasetId);
    const icon = document.querySelector(`#details-${datasetId}`).closest('.dataset-card')?.querySelector('.toggle-icon');
    
    if (!details) return;
    
    const collapse = bootstrap.Collapse.getInstance(details) || new bootstrap.Collapse(details, {toggle: false});
    
    if (details.classList.contains('show')) {
        collapse.hide();
        icon?.classList.remove('rotated');
    } else {
        collapse.show();
        icon?.classList.add('rotated');
    }
}

// ===== Expand/Collapse All =====
let allExpanded = false;

function toggleAllCards() {
    const cards = document.querySelectorAll('.dataset-details');
    const icons = document.querySelectorAll('.toggle-icon');
    
    allExpanded = !allExpanded;
    
    cards.forEach((card, i) => {
        const collapse = bootstrap.Collapse.getInstance(card) || new bootstrap.Collapse(card, {toggle: false});
        allExpanded ? collapse.show() : collapse.hide();
        icons[i]?.classList.toggle('rotated', allExpanded);
    });
}

// ===== View Mode =====
function setViewMode(mode) {
    const container = document.getElementById('datasetsContainer');
    const cards = container.querySelectorAll('.dataset-card');
    const url = new URL(window.location);
    
    cards.forEach(card => {
        if (mode === 'grid') {
            card.classList.add('col-md-6', 'col-lg-4');
        } else {
            card.classList.remove('col-md-6', 'col-lg-4');
        }
    });
    
    // Update active button state
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.btn')?.classList.add('active');
    
    url.searchParams.set('view', mode);
    window.history.replaceState({}, '', url);
}

// ===== Live Search Filters =====
function setupLiveSearch(inputId, itemClass) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('.' + itemClass).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setupLiveSearch('keywordSearch', 'keyword-item');
    setupLiveSearch('subjectSearch', 'subject-item');
});

// ===== Citation Modal =====
function showCitation(datasetId, datasetName) {
    const modal = new bootstrap.Modal(document.getElementById('citationModal'));
    document.getElementById('citationDatasetId').value = datasetId;
    
    const year = new Date().getFullYear();
    const url = window.location.origin + '/datasets/' + datasetId;
    
    const bibtex = `@dataset{ds${datasetId},
  title = {${datasetName}},
  author = {Dataset Contributors},
  year = {${year}},
  url = {${url}},
  note = {Accessed: ${new Date().toLocaleDateString()}}
}`;
    
    const apa = `Dataset Contributors. (${year}). <em>${datasetName}</em>. ${url}`;
    
    document.getElementById('bibtexCode').innerHTML = `<code>${bibtex}</code>`;
    document.getElementById('apaCitation').textContent = apa;
    
    modal.show();
}

function copyCitation() {
    const text = document.getElementById('bibtexCode').textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.replace('btn-success', 'btn-outline-secondary');
        }, 2000);
    });
}

// ===== Python Import =====
function importInPython(datasetId) {
    const code = `# Import dataset ${datasetId}
import pandas as pd
import requests

url = '${window.location.origin}/datasets/${datasetId}/download'
response = requests.get(url)

with open('dataset.csv', 'wb') as f:
    f.write(response.content)

df = pd.read_csv('dataset.csv')
print(f"Loaded {len(df)} rows, {len(df.columns)} columns")
print(df.head())`;
    
    navigator.clipboard.writeText(code).then(() => {
        alert('✓ Python code copied to clipboard!');
    });
}

// ===== Save to Collection =====
function addToCollection(datasetId) {
    fetch(`/datasets/${datasetId}/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ action: 'add' })
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? '✓ ' + data.message : data.message);
    })
    .catch(() => {
        window.location.href = "<?php echo e(route('login')); ?>?redirect=" + encodeURIComponent(window.location.href);
    });
}

// ===== Smooth Scroll for Filter Toggles =====
document.querySelectorAll('.filter-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        if (window.innerWidth < 992) {
            e.preventDefault();
            const target = document.querySelector(this.dataset.bsTarget);
            if (target) {
                target.classList.toggle('show');
                this.querySelector('.transition-icon')?.classList.toggle('rotated');
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/datasets/index.blade.php ENDPATH**/ ?>