@extends('layouts.app')
@section('title', $dataset->name)

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
                            <h1 class="h2 mb-2">{{ $dataset->name }}</h1>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-calendar me-1"></i>
                                Donated on {{ $dataset->donated_date?->format('n/j/Y') ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-2 text-md-end mt-3 mt-md-0">
                            @if($dataset->files->first())
                            <img src="{{ asset('storage/' . $dataset->files->first()->filename) }}" 
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
                    <p class="card-text">{{ $dataset->description }}</p>
                </div>
            </div>

            <!-- Dataset Characteristics Grid -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Dataset Characteristics</h6>
                            <p class="mb-0">{{ $dataset->characteristics ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Subject Area</h6>
                            <p class="mb-0">{{ $dataset->subjectArea->area_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Associated Tasks</h6>
                            <p class="mb-0">{{ $dataset->task->task_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-2">Feature Type</h6>
                            <p class="mb-0">{{ $dataset->feature_type ?? 'N/A' }}</p>
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
            </div>

            <!-- Dataset Information -->
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
                        @php
                            $additionalInfo = json_decode($dataset->additional_info ?? '{}', true) ?? [];
                            $descriptiveInfo = $additionalInfo['descriptive'] ?? [];
                        @endphp
                        
                        @if(!empty($descriptiveInfo['instances_represent']))
                        <div class="mb-3">
                            <h6 class="fw-bold">What do the instances in this dataset represent?</h6>
                            <p>{{ $descriptiveInfo['instances_represent'] }}</p>
                        </div>
                        @endif
                        
                        @if(!empty($descriptiveInfo['purpose']) || !empty($additionalInfo['variable_info']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Additional Information</h6>
                            <p>{{ $descriptiveInfo['purpose'] ?? $additionalInfo['variable_info'] }}</p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <h6 class="fw-bold">Has Missing Values?</h6>
                            <p>{{ $dataset->has_missing_values ? 'Yes' : 'No' }}</p>
                        </div>
                        
                        @if(!empty($descriptiveInfo['data_splits']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Recommended Data Splits</h6>
                            <p>{{ $descriptiveInfo['data_splits'] }}</p>
                        </div>
                        @endif
                        
                        @if(!empty($descriptiveInfo['sensitive_data']))
                        <div class="mb-3">
                            <h6 class="fw-bold">Sensitive Data</h6>
                            <p>{{ $descriptiveInfo['sensitive_data'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Introductory Paper -->
            @if($dataset->papers->isNotEmpty())
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
                        @foreach($dataset->papers as $paper)
                        <div class="mb-3">
                            @if($paper->paper_url)
                            <h6>
                                <a href="{{ $paper->paper_url }}" target="_blank" class="text-decoration-none">
                                    {{ $paper->title }}
                                </a>
                            </h6>
                            @else
                            <h6>{{ $paper->title }}</h6>
                            @endif
                            <p class="mb-1 text-muted">By {{ $paper->authors }}</p>
                            <p class="mb-0 small">Published in {{ $paper->venue ?? 'N/A' }}, {{ $paper->publication_year }}</p>
                        </div>
                        @endforeach
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
                                        <th>Missing Values</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataset->variables as $var)
                                    <tr>
                                        <td><strong>{{ $var->variable_name }}</strong></td>
                                        <td><span class="badge bg-info">{{ $var->role }}</span></td>
                                        <td>{{ $var->type }}</td>
                                        <td>{{ $var->description ?? '-' }}</td>
                                        <td>{{ $var->units ?? '-' }}</td>
                                        <td>{{ $var->has_missing ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                                    <th>Size</th>
                                    <th>Format</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataset->files as $file)
                                <tr>
                                    <td>{{ $file->original_filename }}</td>
                                    <td>{{ $file->file_size }}</td>
                                    <td><span class="badge bg-secondary">{{ strtoupper($file->file_format) }}</span></td>
                                    <td>
                                        <a href="{{ route('datasets.download', [$dataset, $file]) }}" class="btn btn-sm btn-outline-primary">
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

            <!-- Papers Citing this Dataset -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Papers Citing this Dataset</h5>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1"></i>SORT BY YEAR, DESC
                    </button>
                </div>
                <div class="card-body">
                    @if($dataset->papers->isNotEmpty())
                    <div class="list-group">
                        @foreach($dataset->papers as $paper)
                        <div class="list-group-item list-group-item-action">
                            @if($paper->paper_url)
                            <h6>
                                <a href="{{ $paper->paper_url }}" target="_blank" class="text-decoration-none">
                                    {{ $paper->title }}
                                </a>
                            </h6>
                            @else
                            <h6>{{ $paper->title }}</h6>
                            @endif
                            <p class="mb-1 text-muted small">By {{ $paper->authors }}</p>
                            <p class="mb-0 small">Published in {{ $paper->venue ?? 'ArXiv' }}, {{ $paper->publication_year }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Rows per page:</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                            </select>
                            <span class="small text-muted">0 to 5 of {{ $dataset->papers->count() }}</span>
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled"><a class="page-link" href="#">‹</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">›</a></li>
                            </ul>
                        </nav>
                    </div>
                    @else
                    <p class="text-muted mb-0">No papers citing this dataset yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    @if($dataset->files->first())
                    <a href="{{ route('datasets.download', [$dataset, $dataset->files->first()]) }}" 
                       class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-download me-2"></i>DOWNLOAD ({{ $dataset->files->first()->file_size }})
                    </a>
                    @endif
                    <button class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-code-slash me-2"></i>IMPORT IN PYTHON
                    </button>
                    <button class="btn btn-warning w-100 mb-3">
                        <i class="bi bi-quote me-2"></i>CITE
                    </button>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-chat-quote me-2"></i>{{ number_format($dataset->citation_count ?? 0) }} citations</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-eye me-2"></i>{{ number_format($dataset->view_count ?? 0) }} views</span>
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
                        <span class="badge bg-light text-dark border">{{ $keyword->keyword_name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Creators -->
            @if($dataset->creators->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Creators</h6>
                </div>
                <div class="card-body">
                    @foreach($dataset->creators as $creator)
                    <div class="mb-2">
                        <i class="bi bi-person me-1"></i>
                        <strong>{{ $creator->name }}</strong>
                        @if($creator->pivot->contribution_role)
                        <span class="badge bg-secondary ms-1">{{ $creator->pivot->contribution_role }}</span>
                        @endif
                        @if($creator->affiliation)
                        <div class="small text-muted">{{ $creator->affiliation }}</div>
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
                    <a href="{{ $dataset->doi->resolution_url }}" target="_blank" class="text-decoration-none">
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
                        This dataset is licensed under a 
                        <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">
                            Creative Commons Attribution 4.0 International
                        </a> (CC BY 4.0) license.
                    </p>
                    <p class="mb-0 small text-muted">
                        This allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given.
                    </p>
                </div>
            </div>
            @endif
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
        color: var(--uci-blue);
        font-weight: 600;
    }
    
    .btn-link:hover {
        color: var(--uci-dark-blue);
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
        color: var(--uci-blue);
    }
    
    .pagination .page-link {
        color: var(--uci-blue);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--uci-blue);
        border-color: var(--uci-blue);
    }
</style>
@endpush