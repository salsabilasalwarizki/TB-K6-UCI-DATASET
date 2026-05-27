@extends('layouts.app')
@section('title', $dataset->display_name ?? $dataset->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Header -->
            <div class="card mb-4" style="background: linear-gradient(135deg, #0077b6 0%, #005f73 100%); color: white;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-10">
                            <h1 class="h2 mb-2">{{ $dataset->display_name ?? $dataset->name }}</h1>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-calendar me-1"></i>
                                Donated on {{ $dataset->donated_date?->format('n/j/Y') ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-2 text-md-end mt-3 mt-md-0">
                            @if($dataset->thumbnail_url)
                            <img src="{{ $dataset->thumbnail_url }}" 
                                 alt="{{ $dataset->name }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 100px; max-width: 150px;">
                            @elseif($dataset->large_image_url)
                            <img src="{{ $dataset->large_image_url }}" 
                                 alt="{{ $dataset->name }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 100px; max-width: 150px;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abstract/Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-text">{{ $dataset->abstract ?? $dataset->description }}</p>
                    @if($dataset->summary)
                    <p class="card-text mt-3"><strong>Summary:</strong> {{ $dataset->summary }}</p>
                    @endif
                </div>
            </div>

            <!-- Dataset Characteristics Grid -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Dataset Characteristics</h6>
                            <p class="mb-0">{{ $dataset->data_type ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Subject Area</h6>
                            <p class="mb-0">{{ $dataset->subject_area ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Associated Tasks</h6>
                            <p class="mb-0">{{ $dataset->task_type ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Domain</h6>
                            <p class="mb-0">{{ $dataset->domain ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Instances</h6>
                            <p class="mb-0">{{ number_format($dataset->num_instances ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Features</h6>
                            <p class="mb-0">{{ number_format($dataset->num_features ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                @if($dataset->num_classes)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"># Classes</h6>
                            <p class="mb-0">{{ number_format($dataset->num_classes) }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Dataset Information Section -->
            @if($dataset->descriptionDetails)
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
                        
                        @if($dataset->descriptionDetails->instances_represent)
                        <div class="mb-3">
                            <h6 class="fw-bold">What do the instances represent?</h6>
                            <p>{{ $dataset->descriptionDetails->instances_represent }}</p>
                        </div>
                        @endif
                        
                        @if($dataset->descriptionDetails->purpose)
                        <div class="mb-3">
                            <h6 class="fw-bold">Purpose</h6>
                            <p>{{ $dataset->descriptionDetails->purpose }}</p>
                        </div>
                        @endif
                        
                        @if($dataset->descriptionDetails->funding)
                        <div class="mb-3">
                            <h6 class="fw-bold">Funding</h6>
                            <p>{{ $dataset->descriptionDetails->funding }}</p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <h6 class="fw-bold">Has Missing Values?</h6>
                            <p>{{ $dataset->has_missing_values ? 'Yes' : 'No' }}</p>
                        </div>
                        
                        @if($dataset->descriptionDetails->data_splits)
                        <div class="mb-3">
                            <h6 class="fw-bold">Recommended Data Splits</h6>
                            <p>{{ $dataset->descriptionDetails->data_splits }}</p>
                        </div>
                        @endif
                        
                        @if($dataset->descriptionDetails->sensitive_data)
                        <div class="mb-3">
                            <h6 class="fw-bold">Sensitive Data</h6>
                            <p>{{ $dataset->descriptionDetails->sensitive_data }}</p>
                        </div>
                        @endif
                        
                        @if($dataset->descriptionDetails->additional_info)
                        <div class="mb-3">
                            <h6 class="fw-bold">Additional Information</h6>
                            <p>{{ $dataset->descriptionDetails->additional_info }}</p>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
            @endif

            <!-- Variables Table -->
            @if($dataset->variables->isNotEmpty())
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
                                    @foreach($dataset->variables as $var)
                                    <tr>
                                        <td><strong>{{ $var->display_name ?? $var->variable_name }}</strong></td>
                                        <td><span class="badge bg-info">{{ ucfirst($var->role) }}</span></td>
                                        <td>{{ $var->variable_type }}</td>
                                        <td>{{ $var->description ?? '-' }}</td>
                                        <td>{{ $var->unit ?? '-' }}</td>
                                        <td>{{ $var->min_value ?? '-' }}</td>
                                        <td>{{ $var->max_value ?? '-' }}</td>
                                        <td>{{ $var->missing_count > 0 ? $var->missing_count : 'No' }}</td>
                                        <td>{{ $var->unique_count ?? '-' }}</td>
                                    </tr>
                                    @if($var->variable_type === 'Categorical' && $var->categories->isNotEmpty())
                                    <tr class="table-light">
                                        <td colspan="9">
                                            <small class="text-muted"><strong>Categories:</strong> 
                                            @foreach($var->categories as $index => $cat)
                                                {{ $cat->category_label ?? $cat->category_value }}@if(!$loop->last), @endif
                                            @endforeach
                                            </small>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Introductory Paper -->
            @php
                $introductoryPapers = $dataset->papers->where('pivot.citation_type', 'introductory')->take(1);
            @endphp
            @if($introductoryPapers->isNotEmpty())
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
                        @foreach($introductoryPapers as $paper)
                        <div class="mb-3">
                            @if($paper->url)
                            <h6>
                                <a href="{{ $paper->url }}" target="_blank" class="text-decoration-none">
                                    {{ $paper->title }}
                                </a>
                            </h6>
                            @else
                            <h6>{{ $paper->title }}</h6>
                            @endif
                            <p class="mb-1 text-muted">By {{ $paper->authors }}</p>
                            <p class="mb-0 small">Published in {{ $paper->venue ?? 'N/A' }}, {{ $paper->publication_year }}</p>
                            @if($paper->abstract)
                            <p class="mt-2 small">{{ Str::limit($paper->abstract, 200) }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Dataset Files -->
            @if($dataset->files->isNotEmpty())
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
                                @foreach($dataset->files as $file)
                                <tr>
                                    <td>{{ $file->original_filename ?? $file->filename }}</td>
                                    <td><span class="badge bg-secondary">{{ strtoupper($file->file_format) }}</span></td>
                                    <td>{{ $file->file_size_bytes ? number_format($file->file_size_bytes / 1024, 2) . ' KB' : 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst($file->pivot->file_role ?? 'data') }}</span></td>
                                    <td>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           download>
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Papers Citing this Dataset (FIXED) -->
            @php
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
            @endphp
            
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Papers Citing this Dataset ({{ $totalPapers }})</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" id="sortByYear" onchange="sortPapers()">
                            <option value="year_desc" {{ request('sort') === 'year_desc' || !request('sort') ? 'selected' : '' }}>Year (Newest)</option>
                            <option value="year_asc" {{ request('sort') === 'year_asc' ? 'selected' : '' }}>Year (Oldest)</option>
                            <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                            <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    @if($paginatedPapers->isNotEmpty())
                    <div class="list-group" id="papersList">
                        @foreach($paginatedPapers as $paper)
                        <div class="list-group-item list-group-item-action">
                            @if($paper->url)
                            <h6>
                                <a href="{{ $paper->url }}" target="_blank" class="text-decoration-none">
                                    {{ $paper->title }}
                                </a>
                            </h6>
                            @else
                            <h6>{{ $paper->title }}</h6>
                            @endif
                            <p class="mb-1 text-muted small">By {{ $paper->authors }}</p>
                            <p class="mb-0 small">Published in {{ $paper->venue ?? 'ArXiv' }}, {{ $paper->publication_year }}</p>
                            @if($paper->doi)
                            <p class="mb-0 small text-primary">DOI: {{ $paper->doi }}</p>
                            @endif
                            @if($paper->abstract)
                            <p class="mt-2 small text-muted">{{ Str::limit($paper->abstract, 150) }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Rows per page:</span>
                            <select class="form-select form-select-sm" style="width: auto;" onchange="changePageSize(this.value)">
                                <option value="5" {{ $papersPerPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $papersPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $papersPerPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ $papersPerPage == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="small text-muted">
                                {{ $totalPapers > 0 ? $startIndex + 1 : 0 }} to {{ $endIndex }} of {{ $totalPapers }}
                            </span>
                        </div>
                        
                        @if($totalPages > 1)
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="?page={{ $currentPage - 1 }}&per_page={{ $papersPerPage }}&sort={{ request('sort', 'year_desc') }}">‹</a>
                                </li>
                                
                                @for($i = 1; $i <= $totalPages; $i++)
                                    @if($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link" href="?page={{ $i }}&per_page={{ $papersPerPage }}&sort={{ request('sort', 'year_desc') }}">{{ $i }}</a>
                                        </li>
                                    @elseif($i == $currentPage - 3 || $i == $currentPage + 3)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endfor
                                
                                <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                    <a class="page-link" href="?page={{ $currentPage + 1 }}&per_page={{ $papersPerPage }}&sort={{ request('sort', 'year_desc') }}">›</a>
                                </li>
                            </ul>
                        </nav>
                        @endif
                    </div>
                    @else
                    <p class="text-muted mb-0">No papers citing this dataset yet.</p>
                    @endif
                </div>
            </div>

            <!-- Related Papers -->
            @php
                $relatedPapers = $dataset->papers->where('pivot.citation_type', 'related');
            @endphp
            @if($relatedPapers->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Related Papers</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($relatedPapers->take(3) as $paper)
                        <div class="list-group-item list-group-item-action">
                            <h6>{{ $paper->title }}</h6>
                            <p class="mb-1 text-muted small">By {{ $paper->authors }}</p>
                            <p class="mb-0 small">{{ $paper->venue ?? 'N/A' }}, {{ $paper->publication_year }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Reviews Section -->
            @if($dataset->reviews->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">User Reviews ({{ $dataset->reviews->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($dataset->reviews->take(5) as $review)
                    <div class="mb-3 border-bottom pb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $review->title ?? 'Untitled Review' }}</h6>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ number_format($review->rating, 1) }})</span>
                            </div>
                        </div>
                        <p class="mb-1">{{ $review->content }}</p>
                        @if($review->pros)
                        <p class="mb-1 small text-success"><strong>Pros:</strong> {{ $review->pros }}</p>
                        @endif
                        @if($review->cons)
                        <p class="mb-1 small text-danger"><strong>Cons:</strong> {{ $review->cons }}</p>
                        @endif
                        <small class="text-muted">
                            By {{ $review->user->name ?? 'Anonymous' }} 
                            on {{ $review->created_at->format('M d, Y') }}
                            @if($review->is_verified)
                            <span class="badge bg-success ms-1">Verified</span>
                            @endif
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    @php
                        $defaultFile = $dataset->files->where('pivot.is_default', 1)->first() ?? $dataset->files->first();
                    @endphp
                    @if($defaultFile)
                    <a href="{{ asset('storage/' . $defaultFile->file_path) }}" 
                       class="btn btn-primary w-100 mb-2"
                       download>
                        <i class="bi bi-download me-2"></i>DOWNLOAD ({{ $defaultFile->file_size_bytes ? number_format($defaultFile->file_size_bytes / 1024, 2) . ' KB' : 'N/A' }})
                    </a>
                    @endif
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="importInPython()">
                        <i class="bi bi-code-slash me-2"></i>IMPORT IN PYTHON
                    </button>
                    <button class="btn btn-warning w-100 mb-3" onclick="showCitation()">
                        <i class="bi bi-quote me-2"></i>CITE
                    </button>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-chat-quote me-2"></i>{{ number_format($dataset->citation_count ?? 0) }} citations</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-eye me-2"></i>{{ number_format($dataset->view_count ?? 0) }} views</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-cloud-download me-2"></i>{{ number_format($dataset->download_count ?? 0) }} downloads</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keywords -->
            @if($dataset->keywords->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Keywords</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($dataset->keywords as $keyword)
                        <a href="{{ route('datasets.index', ['keyword' => $keyword->slug]) }}" 
                           class="badge bg-light text-dark border text-decoration-none">
                            {{ $keyword->keyword_name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Creators -->
            @php
                $creators = $dataset->contributors->where('pivot.contribution_role', 'creator');
            @endphp
            @if($creators->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Creators</h6>
                </div>
                <div class="card-body">
                    @foreach($creators as $creator)
                    <div class="mb-2">
                        <i class="bi bi-person me-1"></i>
                        <strong>{{ $creator->name }}</strong>
                        @if($creator->pivot->contribution_role)
                        <span class="badge bg-secondary ms-1">{{ ucfirst($creator->pivot->contribution_role) }}</span>
                        @endif
                        @if($creator->affiliation)
                        <div class="small text-muted">{{ $creator->affiliation }}</div>
                        @endif
                        @if($creator->orcid)
                        <div class="small">
                            <a href="https://orcid.org/{{ $creator->orcid }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-orcid"></i> {{ $creator->orcid }}
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Contributors -->
            @php
                $otherContributors = $dataset->contributors->whereNotIn('pivot.contribution_role', ['creator']);
            @endphp
            @if($otherContributors->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Contributors</h6>
                </div>
                <div class="card-body">
                    @foreach($otherContributors as $contributor)
                    <div class="mb-2">
                        <i class="bi bi-person me-1"></i>
                        <strong>{{ $contributor->name }}</strong>
                        @if($contributor->pivot->contribution_role)
                        <span class="badge bg-secondary ms-1">{{ ucfirst($contributor->pivot->contribution_role) }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- DOI -->
            @if($dataset->doi)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">DOI</h6>
                </div>
                <div class="card-body">
                    <a href="{{ $dataset->doi->resolution_url ?? 'https://doi.org/' . $dataset->doi->doi_string }}" 
                       target="_blank" 
                       class="text-decoration-none">
                        {{ $dataset->doi->doi_string }}
                    </a>
                </div>
            </div>
            @endif

            <!-- License -->
            @if($dataset->license)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">License</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 small">
                        @if($dataset->license->license_url)
                        <a href="{{ $dataset->license->license_url }}" target="_blank">
                            {{ $dataset->license->license_name }}
                        </a>
                        @else
                        {{ $dataset->license->license_name }}
                        @endif
                    </p>
                    @if($dataset->license->description)
                    <p class="mb-0 small text-muted">{{ Str::limit($dataset->license->description, 150) }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Dataset Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'available' => 'primary',
                            'deprecated' => 'secondary'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$dataset->status] ?? 'secondary' }}">
                        {{ ucfirst($dataset->status) }}
                    </span>
                    @if($dataset->approved_at)
                    <div class="mt-2 small text-muted">
                        Approved on {{ $dataset->approved_at->format('M d, Y') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Dataset Details</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if($dataset->uci_id)
                        <li class="mb-2">
                            <small class="text-muted">UCI ID:</small><br>
                            <strong>{{ $dataset->uci_id }}</strong>
                        </li>
                        @endif
                        @if($dataset->dataset_url)
                        <li class="mb-2">
                            <small class="text-muted">Source URL:</small><br>
                            <a href="{{ $dataset->dataset_url }}" target="_blank" class="text-decoration-none">
                                View Original Source
                            </a>
                        </li>
                        @endif
                        <li class="mb-2">
                            <small class="text-muted">Added:</small><br>
                            <strong>{{ $dataset->created_at->format('M d, Y') }}</strong>
                        </li>
                        <li>
                            <small class="text-muted">Last Updated:</small><br>
                            <strong>{{ $dataset->updated_at->format('M d, Y') }}</strong>
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
                <pre class="bg-light p-3 rounded"><code>@dataset{{ $dataset->dataset_id }},
  title = { {{ $dataset->name }} },
  @if($dataset->user)author = { {{ $dataset->user->name }} },
  @endif
  year = { {{ $dataset->created_at->year }} },
  @if($dataset->doi)doi = { {{ $dataset->doi->doi_string }} },
  @endif
  url = { {{ route('datasets.show', $dataset) }} }
}</code></pre>
                
                <h6 class="mt-3">APA Style</h6>
                <p class="bg-light p-3 rounded">
                    @if($dataset->user){{ $dataset->user->name }}@endif. 
                    ({{ $dataset->created_at->year }}). 
                    <em>{{ $dataset->name }}</em>. 
                    @if($dataset->doi)https://doi.org/{{ $dataset->doi->doi_string }}@endif
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyCitation()">Copy BibTeX</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<script>
function showCitation() {
    const modal = new bootstrap.Modal(document.getElementById('citationModal'));
    modal.show();
}

function copyCitation() {
    const bibtex = `@dataset{{ $dataset->dataset_id }},
  title = { {{ $dataset->name }} },
  @if($dataset->user)author = { {{ $dataset->user->name }} },
  @endif
  year = { {{ $dataset->created_at->year }} },
  @if($dataset->doi)doi = { {{ $dataset->doi->doi_string }} },
  @endif
  url = { {{ route('datasets.show', $dataset) }} }
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
df = pd.read_csv('{{ asset('storage/' . ($defaultFile->file_path ?? '')) }}')

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
    const datasetId = {{ $dataset->dataset_id }};
    const trackUrl = "{{ route('datasets.track-view', $dataset) }}";
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
    const saveUrl = "{{ route('datasets.save', $dataset) }}";
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
            window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.href);
        } else {
            console.error('Save error:', err);
            alert('Failed to save dataset');
        }
    });
}
</script>
@endpush