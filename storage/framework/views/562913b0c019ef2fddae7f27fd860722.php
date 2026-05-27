
<?php $__env->startSection('title', $dataset->display_name ?? $dataset->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Header -->
            <div class="card mb-4" style="background: linear-gradient(135deg, #0077b6 0%, #005f73 100%); color: white;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-10">
                            <h1 class="h2 mb-2"><?php echo e($dataset->display_name ?? $dataset->name); ?></h1>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-calendar me-1"></i>
                                Donated on <?php echo e($dataset->donated_date?->format('n/j/Y') ?? 'N/A'); ?>

                            </p>
                        </div>
                        <div class="col-md-2 text-md-end mt-3 mt-md-0">
                            <?php if($dataset->thumbnail_url): ?>
                            <img src="<?php echo e($dataset->thumbnail_url); ?>" 
                                 alt="<?php echo e($dataset->name); ?>" 
                                 class="img-fluid rounded"
                                 style="max-height: 100px; max-width: 150px;">
                            <?php elseif($dataset->large_image_url): ?>
                            <img src="<?php echo e($dataset->large_image_url); ?>" 
                                 alt="<?php echo e($dataset->name); ?>" 
                                 class="img-fluid rounded"
                                 style="max-height: 100px; max-width: 150px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abstract/Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-text"><?php echo e($dataset->abstract ?? $dataset->description); ?></p>
                    <?php if($dataset->summary): ?>
                    <p class="card-text mt-3"><strong>Summary:</strong> <?php echo e($dataset->summary); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dataset Characteristics Grid -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Dataset Characteristics</h6>
                            <p class="mb-0"><?php echo e($dataset->data_type ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Subject Area</h6>
                            <p class="mb-0"><?php echo e($dataset->subject_area ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Associated Tasks</h6>
                            <p class="mb-0"><?php echo e($dataset->task_type ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Domain</h6>
                            <p class="mb-0"><?php echo e($dataset->domain ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Instances</h6>
                            <p class="mb-0"><?php echo e(number_format($dataset->num_instances ?? 0)); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Features</h6>
                            <p class="mb-0"><?php echo e(number_format($dataset->num_features ?? 0)); ?></p>
                        </div>
                    </div>
                </div>
                <?php if($dataset->num_classes): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Classes</h6>
                            <p class="mb-0"><?php echo e(number_format($dataset->num_classes)); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Dataset Information Section -->
            <?php if($dataset->descriptionDetails): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#datasetInfo">
                            Dataset Information <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="datasetInfo" class="collapse show">
                    <div class="card-body">
                        
                        <?php if($dataset->descriptionDetails->instances_represent): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">What do the instances represent?</h6>
                            <p><?php echo e($dataset->descriptionDetails->instances_represent); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($dataset->descriptionDetails->purpose): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">Purpose</h6>
                            <p><?php echo e($dataset->descriptionDetails->purpose); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($dataset->descriptionDetails->funding): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">Funding</h6>
                            <p><?php echo e($dataset->descriptionDetails->funding); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold">Has Missing Values?</h6>
                            <p><?php echo e($dataset->has_missing_values ? 'Yes' : 'No'); ?></p>
                        </div>
                        
                        <?php if($dataset->descriptionDetails->data_splits): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">Recommended Data Splits</h6>
                            <p><?php echo e($dataset->descriptionDetails->data_splits); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($dataset->descriptionDetails->sensitive_data): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">Sensitive Data</h6>
                            <p><?php echo e($dataset->descriptionDetails->sensitive_data); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($dataset->descriptionDetails->additional_info): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold">Additional Information</h6>
                            <p><?php echo e($dataset->descriptionDetails->additional_info); ?></p>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Variables Table -->
            <?php if($dataset->variables->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#variablesSection">
                            Variables Table <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="variablesSection" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Variable Name</th>
                                        <th>Role</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th>Min Value</th>
                                        <th>Max Value</th>
                                        <th>Missing Values</th>
                                        <th>Unique Values</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $dataset->variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($var->display_name ?? $var->variable_name); ?></strong></td>
                                        <td><span class="badge bg-info"><?php echo e(ucfirst($var->role)); ?></span></td>
                                        <td><?php echo e($var->variable_type); ?></td>
                                        <td><?php echo e($var->description ?? '-'); ?></td>
                                        <td><?php echo e($var->unit ?? '-'); ?></td>
                                        <td><?php echo e($var->min_value ?? '-'); ?></td>
                                        <td><?php echo e($var->max_value ?? '-'); ?></td>
                                        <td><?php echo e($var->missing_count > 0 ? $var->missing_count : 'No'); ?></td>
                                        <td><?php echo e($var->unique_count ?? '-'); ?></td>
                                    </tr>
                                    <?php if($var->variable_type === 'Categorical' && $var->categories->isNotEmpty()): ?>
                                    <tr class="table-light">
                                        <td colspan="9">
                                            <small class="text-muted"><strong>Categories:</strong> 
                                            <?php $__currentLoopData = $var->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($cat->category_label ?? $cat->category_value); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Introductory Paper -->
            <?php
                $introductoryPapers = $dataset->papers->where('pivot.citation_type', 'introductory')->take(1);
            ?>
            <?php if($introductoryPapers->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#paperSection">
                            Introductory Paper <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="paperSection" class="collapse show">
                    <div class="card-body">
                        <?php $__currentLoopData = $introductoryPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <?php if($paper->url): ?>
                            <h6>
                                <a href="<?php echo e($paper->url); ?>" target="_blank" class="text-decoration-none">
                                    <?php echo e($paper->title); ?>

                                </a>
                            </h6>
                            <?php else: ?>
                            <h6><?php echo e($paper->title); ?></h6>
                            <?php endif; ?>
                            <p class="mb-1 text-muted">By <?php echo e($paper->authors); ?></p>
                            <p class="mb-0 small">Published in <?php echo e($paper->venue ?? 'N/A'); ?>, <?php echo e($paper->publication_year); ?></p>
                            <?php if($paper->abstract): ?>
                            <p class="mt-2 small"><?php echo e(Str::limit($paper->abstract, 200)); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dataset Files -->
            <?php if($dataset->files->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#filesSection">
                            Dataset Files <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </h5>
                </div>
                <div id="filesSection" class="collapse show">
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Format</th>
                                    <th>Size</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataset->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($file->original_filename ?? $file->filename); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo e(strtoupper($file->file_format)); ?></span></td>
                                    <td><?php echo e($file->file_size_bytes ? number_format($file->file_size_bytes / 1024, 2) . ' KB' : 'N/A'); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo e(ucfirst($file->pivot->file_role ?? 'data')); ?></span></td>
                                    <td>
                                        <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Papers Citing this Dataset (FIXED) -->
            <?php
                    $citingPapers = $dataset->papers->where(function($paper) {
        return $paper->pivot->citation_type === 'citing' || $paper->pivot->citation_type === null;
    })->sortByDesc('publication_year');
                $papersPerPage = request('per_page', 5);
                $currentPage = request('page', 1);
                $totalPapers = $citingPapers->count();
                $startIndex = ($currentPage - 1) * $papersPerPage;
                $endIndex = min($startIndex + $papersPerPage, $totalPapers);
                $paginatedPapers = $citingPapers->slice($startIndex, $papersPerPage);
                $totalPages = ceil($totalPapers / $papersPerPage);
            ?>
            
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Papers Citing this Dataset (<?php echo e($totalPapers); ?>)</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" id="sortByYear" onchange="sortPapers()">
                            <option value="year_desc" <?php echo e(request('sort') === 'year_desc' || !request('sort') ? 'selected' : ''); ?>>Year (Newest)</option>
                            <option value="year_asc" <?php echo e(request('sort') === 'year_asc' ? 'selected' : ''); ?>>Year (Oldest)</option>
                            <option value="title_asc" <?php echo e(request('sort') === 'title_asc' ? 'selected' : ''); ?>>Title (A-Z)</option>
                            <option value="title_desc" <?php echo e(request('sort') === 'title_desc' ? 'selected' : ''); ?>>Title (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($paginatedPapers->isNotEmpty()): ?>
                    <div class="list-group" id="papersList">
                        <?php $__currentLoopData = $paginatedPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item list-group-item-action">
                            <?php if($paper->url): ?>
                            <h6>
                                <a href="<?php echo e($paper->url); ?>" target="_blank" class="text-decoration-none">
                                    <?php echo e($paper->title); ?>

                                </a>
                            </h6>
                            <?php else: ?>
                            <h6><?php echo e($paper->title); ?></h6>
                            <?php endif; ?>
                            <p class="mb-1 text-muted small">By <?php echo e($paper->authors); ?></p>
                            <p class="mb-0 small">Published in <?php echo e($paper->venue ?? 'ArXiv'); ?>, <?php echo e($paper->publication_year); ?></p>
                            <?php if($paper->doi): ?>
                            <p class="mb-0 small text-primary">DOI: <?php echo e($paper->doi); ?></p>
                            <?php endif; ?>
                            <?php if($paper->abstract): ?>
                            <p class="mt-2 small text-muted"><?php echo e(Str::limit($paper->abstract, 150)); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Rows per page:</span>
                            <select class="form-select form-select-sm" style="width: auto;" onchange="changePageSize(this.value)">
                                <option value="5" <?php echo e($papersPerPage == 5 ? 'selected' : ''); ?>>5</option>
                                <option value="10" <?php echo e($papersPerPage == 10 ? 'selected' : ''); ?>>10</option>
                                <option value="20" <?php echo e($papersPerPage == 20 ? 'selected' : ''); ?>>20</option>
                                <option value="50" <?php echo e($papersPerPage == 50 ? 'selected' : ''); ?>>50</option>
                            </select>
                            <span class="small text-muted">
                                <?php echo e($totalPapers > 0 ? $startIndex + 1 : 0); ?> to <?php echo e($endIndex); ?> of <?php echo e($totalPapers); ?>

                            </span>
                        </div>
                        
                        <?php if($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?php echo e($currentPage <= 1 ? 'disabled' : ''); ?>">
                                    <a class="page-link" href="?page=<?php echo e($currentPage - 1); ?>&per_page=<?php echo e($papersPerPage); ?>&sort=<?php echo e(request('sort', 'year_desc')); ?>">‹</a>
                                </li>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)): ?>
                                        <li class="page-item <?php echo e($i == $currentPage ? 'active' : ''); ?>">
                                            <a class="page-link" href="?page=<?php echo e($i); ?>&per_page=<?php echo e($papersPerPage); ?>&sort=<?php echo e(request('sort', 'year_desc')); ?>"><?php echo e($i); ?></a>
                                        </li>
                                    <?php elseif($i == $currentPage - 3 || $i == $currentPage + 3): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <li class="page-item <?php echo e($currentPage >= $totalPages ? 'disabled' : ''); ?>">
                                    <a class="page-link" href="?page=<?php echo e($currentPage + 1); ?>&per_page=<?php echo e($papersPerPage); ?>&sort=<?php echo e(request('sort', 'year_desc')); ?>">›</a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted mb-0">No papers citing this dataset yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Related Papers -->
            <?php
                $relatedPapers = $dataset->papers->where('pivot.citation_type', 'related');
            ?>
            <?php if($relatedPapers->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Related Papers</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php $__currentLoopData = $relatedPapers->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item list-group-item-action">
                            <h6><?php echo e($paper->title); ?></h6>
                            <p class="mb-1 text-muted small">By <?php echo e($paper->authors); ?></p>
                            <p class="mb-0 small"><?php echo e($paper->venue ?? 'N/A'); ?>, <?php echo e($paper->publication_year); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Reviews Section -->
            <?php if($dataset->reviews->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">User Reviews (<?php echo e($dataset->reviews->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $dataset->reviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><?php echo e($review->title ?? 'Untitled Review'); ?></h6>
                            <div class="text-warning">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?php echo e($i <= $review->rating ? '-fill' : ''); ?>"></i>
                                <?php endfor; ?>
                                <span class="text-muted ms-1">(<?php echo e(number_format($review->rating, 1)); ?>)</span>
                            </div>
                        </div>
                        <p class="mb-1"><?php echo e($review->content); ?></p>
                        <?php if($review->pros): ?>
                        <p class="mb-1 small text-success"><strong>Pros:</strong> <?php echo e($review->pros); ?></p>
                        <?php endif; ?>
                        <?php if($review->cons): ?>
                        <p class="mb-1 small text-danger"><strong>Cons:</strong> <?php echo e($review->cons); ?></p>
                        <?php endif; ?>
                        <small class="text-muted">
                            By <?php echo e($review->user->name ?? 'Anonymous'); ?> 
                            on <?php echo e($review->created_at->format('M d, Y')); ?>

                            <?php if($review->is_verified): ?>
                            <span class="badge bg-success ms-1">Verified</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <?php
                        $defaultFile = $dataset->files->where('pivot.is_default', 1)->first() ?? $dataset->files->first();
                    ?>
                    <?php if($defaultFile): ?>
                    <a href="<?php echo e(asset('storage/' . $defaultFile->file_path)); ?>" 
                       class="btn btn-primary w-100 mb-2"
                       download>
                        <i class="bi bi-download me-2"></i>DOWNLOAD (<?php echo e($defaultFile->file_size_bytes ? number_format($defaultFile->file_size_bytes / 1024, 2) . ' KB' : 'N/A'); ?>)
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="importInPython()">
                        <i class="bi bi-code-slash me-2"></i>IMPORT IN PYTHON
                    </button>
                    <button class="btn btn-warning w-100 mb-3" onclick="showCitation()">
                        <i class="bi bi-quote me-2"></i>CITE
                    </button>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-chat-quote me-2"></i><?php echo e(number_format($dataset->citation_count ?? 0)); ?> citations</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-eye me-2"></i><?php echo e(number_format($dataset->view_count ?? 0)); ?> views</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-cloud-download me-2"></i><?php echo e(number_format($dataset->download_count ?? 0)); ?> downloads</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keywords -->
            <?php if($dataset->keywords->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Keywords</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $dataset->keywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('datasets.index', ['keyword' => $keyword->slug])); ?>" 
                           class="badge bg-light text-dark border text-decoration-none">
                            <?php echo e($keyword->keyword_name); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Creators -->
            <?php
                $creators = $dataset->contributors->where('pivot.contribution_role', 'creator');
            ?>
            <?php if($creators->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Creators</h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $creator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-2">
                        <i class="bi bi-person me-1"></i>
                        <strong><?php echo e($creator->name); ?></strong>
                        <?php if($creator->pivot->contribution_role): ?>
                        <span class="badge bg-secondary ms-1"><?php echo e(ucfirst($creator->pivot->contribution_role)); ?></span>
                        <?php endif; ?>
                        <?php if($creator->affiliation): ?>
                        <div class="small text-muted"><?php echo e($creator->affiliation); ?></div>
                        <?php endif; ?>
                        <?php if($creator->orcid): ?>
                        <div class="small">
                            <a href="https://orcid.org/<?php echo e($creator->orcid); ?>" target="_blank" class="text-decoration-none">
                                <i class="bi bi-orcid"></i> <?php echo e($creator->orcid); ?>

                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contributors -->
            <?php
                $otherContributors = $dataset->contributors->whereNotIn('pivot.contribution_role', ['creator']);
            ?>
            <?php if($otherContributors->isNotEmpty()): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Contributors</h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $otherContributors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contributor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-2">
                        <i class="bi bi-person me-1"></i>
                        <strong><?php echo e($contributor->name); ?></strong>
                        <?php if($contributor->pivot->contribution_role): ?>
                        <span class="badge bg-secondary ms-1"><?php echo e(ucfirst($contributor->pivot->contribution_role)); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- DOI -->
            <?php if($dataset->doi): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">DOI</h6>
                </div>
                <div class="card-body">
                    <a href="<?php echo e($dataset->doi->resolution_url ?? 'https://doi.org/' . $dataset->doi->doi_string); ?>" 
                       target="_blank" 
                       class="text-decoration-none">
                        <?php echo e($dataset->doi->doi_string); ?>

                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- License -->
            <?php if($dataset->license): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">License</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 small">
                        <?php if($dataset->license->license_url): ?>
                        <a href="<?php echo e($dataset->license->license_url); ?>" target="_blank">
                            <?php echo e($dataset->license->license_name); ?>

                        </a>
                        <?php else: ?>
                        <?php echo e($dataset->license->license_name); ?>

                        <?php endif; ?>
                    </p>
                    <?php if($dataset->license->description): ?>
                    <p class="mb-0 small text-muted"><?php echo e(Str::limit($dataset->license->description, 150)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dataset Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body">
                    <?php
                        $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'available' => 'primary',
                            'deprecated' => 'secondary'
                        ];
                    ?>
                    <span class="badge bg-<?php echo e($statusColors[$dataset->status] ?? 'secondary'); ?>">
                        <?php echo e(ucfirst($dataset->status)); ?>

                    </span>
                    <?php if($dataset->approved_at): ?>
                    <div class="mt-2 small text-muted">
                        Approved on <?php echo e($dataset->approved_at->format('M d, Y')); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Dataset Details</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php if($dataset->uci_id): ?>
                        <li class="mb-2">
                            <small class="text-muted">UCI ID:</small><br>
                            <strong><?php echo e($dataset->uci_id); ?></strong>
                        </li>
                        <?php endif; ?>
                        <?php if($dataset->dataset_url): ?>
                        <li class="mb-2">
                            <small class="text-muted">Source URL:</small><br>
                            <a href="<?php echo e($dataset->dataset_url); ?>" target="_blank" class="text-decoration-none">
                                View Original Source
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="mb-2">
                            <small class="text-muted">Added:</small><br>
                            <strong><?php echo e($dataset->created_at->format('M d, Y')); ?></strong>
                        </li>
                        <li>
                            <small class="text-muted">Last Updated:</small><br>
                            <strong><?php echo e($dataset->updated_at->format('M d, Y')); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cite this Dataset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>BibTeX</h6>
                <pre class="bg-light p-3 rounded"><code>@dataset<?php echo e($dataset->dataset_id); ?>,
  title = { <?php echo e($dataset->name); ?> },
  <?php if($dataset->user): ?>author = { <?php echo e($dataset->user->name); ?> },
  <?php endif; ?>
  year = { <?php echo e($dataset->created_at->year); ?> },
  <?php if($dataset->doi): ?>doi = { <?php echo e($dataset->doi->doi_string); ?> },
  <?php endif; ?>
  url = { <?php echo e(route('datasets.show', $dataset)); ?> }
}</code></pre>
                
                <h6 class="mt-3">APA Style</h6>
                <p class="bg-light p-3 rounded">
                    <?php if($dataset->user): ?><?php echo e($dataset->user->name); ?><?php endif; ?>. 
                    (<?php echo e($dataset->created_at->year); ?>). 
                    <em><?php echo e($dataset->name); ?></em>. 
                    <?php if($dataset->doi): ?>https://doi.org/<?php echo e($dataset->doi->doi_string); ?><?php endif; ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyCitation()">Copy BibTeX</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .btn-link {
        color: var(--uci-blue, #0077b6);
        font-weight: 600;
    }
    
    .btn-link:hover {
        color: var(--uci-dark-blue, #005f73);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .table th {
        font-weight: 600;
        color: var(--uci-blue, #0077b6);
    }
    
    .pagination .page-link {
        color: var(--uci-blue, #0077b6);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--uci-blue, #0077b6);
        border-color: var(--uci-blue, #0077b6);
    }
    
    pre code {
        white-space: pre-wrap;
        word-break: break-all;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showCitation() {
    const modal = new bootstrap.Modal(document.getElementById('citationModal'));
    modal.show();
}

function copyCitation() {
    const bibtex = `@dataset<?php echo e($dataset->dataset_id); ?>,
  title = { <?php echo e($dataset->name); ?> },
  <?php if($dataset->user): ?>author = { <?php echo e($dataset->user->name); ?> },
  <?php endif; ?>
  year = { <?php echo e($dataset->created_at->year); ?> },
  <?php if($dataset->doi): ?>doi = { <?php echo e($dataset->doi->doi_string); ?> },
  <?php endif; ?>
  url = { <?php echo e(route('datasets.show', $dataset)); ?> }
}`;
    
    navigator.clipboard.writeText(bibtex).then(() => {
        alert('BibTeX citation copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

function importInPython() {
    const code = `# Import the dataset
import pandas as pd

# Load the dataset
df = pd.read_csv('<?php echo e(asset('storage/' . ($defaultFile->file_path ?? ''))); ?>')

# Display basic information
print(df.info())
print(df.describe())`;
    
    navigator.clipboard.writeText(code).then(() => {
        alert('Python import code copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

// Sorting papers
function sortPapers() {
    const sortBy = document.getElementById('sortByYear').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortBy);
    urlParams.set('page', '1'); // Reset to first page when sorting
    window.location.search = urlParams.toString();
}

// Change page size
function changePageSize(size) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('per_page', size);
    urlParams.set('page', '1'); // Reset to first page
    window.location.search = urlParams.toString();
}

// Track dataset view
document.addEventListener('DOMContentLoaded', function() {
    const datasetId = <?php echo e($dataset->dataset_id); ?>;
    const trackUrl = "<?php echo e(route('datasets.track-view', $dataset)); ?>";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content 
        || document.querySelector('[name="_token"]')?.value;
    
    if (trackUrl && csrfToken) {
        fetch(trackUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            console.log('View tracked:', data);
            // Optional: update view count display
            const viewCountEl = document.querySelector('[data-view-count]');
            if (viewCountEl && data.views) {
                viewCountEl.textContent = new Intl.NumberFormat().format(data.views);
            }
        })
        .catch(err => {
            console.warn('Tracking error (non-critical):', err);
            // Fail silently - tracking is optional
        });
    }
});

// Save to collection handler
function addToCollection(datasetId) {
    const saveUrl = "<?php echo e(route('datasets.save', $dataset)); ?>";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action: 'add' })
    })
    .then(response => {
        if (!response.ok) throw new Error('Not authenticated');
        return response.json();
    })
    .then(data => {
        alert(data.message);
        // Update button state
        const btn = event.target.closest('button');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Saved';
            btn.disabled = true;
            btn.classList.replace('btn-outline-info', 'btn-info');
        }
    })
    .catch(err => {
        if (err.message === 'Not authenticated') {
            window.location.href = "<?php echo e(route('login')); ?>?redirect=" + encodeURIComponent(window.location.href);
        } else {
            console.error('Save error:', err);
            alert('Failed to save dataset');
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/datasets/show.blade.php ENDPATH**/ ?>