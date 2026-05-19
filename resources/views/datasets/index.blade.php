@extends('layouts.app')
@section('title', 'Browse Datasets')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="filters-sidebar">
                <h3 class="filters-title mb-4">Filters</h3>
                
                <!-- Search -->
                <form method="GET" action="{{ route('datasets.index') }}" id="filterForm">
                    <div class="filter-section mb-4">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Search datasets..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Keywords -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#keywordsFilter">
                            <span>Keywords</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="keywordsFilter" class="collapse show">
                            <input type="text" 
                                   class="form-control form-control-sm mb-2" 
                                   placeholder="Search keywords...">
                            <div class="keywords-list" style="max-height: 150px; overflow-y: auto;">
                                @forelse($keywords->take(10) as $keyword)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="keywords[]" 
                                           value="{{ $keyword->keyword_id }}"
                                           id="kw_{{ $keyword->keyword_id }}"
                                           {{ in_array($keyword->keyword_id, request('keywords', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kw_{{ $keyword->keyword_id }}">
                                        {{ $keyword->keyword_name }}
                                    </label>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attributes -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#attributesFilter">
                            <span>Attributes</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="attributesFilter" class="collapse show">
                            <input type="text" class="form-control form-control-sm" placeholder="Filter attributes...">
                        </div>
                    </div>
                    
                    <!-- Data Type -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#dataTypeFilter">
                            <span>Data Type</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="dataTypeFilter" class="collapse show">
                            @php
                                $dataTypes = ['Image', 'Multivariate', 'Sequential', 'Spatiotemporal', 'Tabular', 'Text', 'Time-Series', 'Other'];
                            @endphp
                            @foreach($dataTypes as $type)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="data_type" 
                                       value="{{ $type }}"
                                       id="dt_{{ str_replace(' ', '', $type) }}"
                                       {{ request('data_type') == $type ? 'checked' : '' }}>
                                <label class="form-check-label" for="dt_{{ str_replace(' ', '', $type) }}">
                                    {{ $type }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Subject Area -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#subjectAreaFilter">
                            <span>Subject Area</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="subjectAreaFilter" class="collapse show">
                            @forelse($subjectAreas->take(15) as $area)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="subject_area[]" 
                                       value="{{ $area->area_id }}"
                                       id="sa_{{ $area->area_id }}"
                                       {{ in_array($area->area_id, request('subject_area', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sa_{{ $area->area_id }}">
                                    {{ $area->area_name }}
                                </label>
                            </div>
                            @endforelse 
                        </div>
                    </div>
                    
                    <!-- Task -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#taskFilter">
                            <span>Task</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="taskFilter" class="collapse show">
                            @foreach($tasks as $task)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="task" 
                                       value="{{ $task->task_id }}"
                                       id="task_{{ $task->task_id }}"
                                       {{ request('task') == $task->task_id ? 'checked' : '' }}>
                                <label class="form-check-label" for="task_{{ $task->task_id }}">
                                    {{ $task->task_name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- # Instances -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#instancesFilter">
                            <span># Instances</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="instancesFilter" class="collapse show">
                            <input type="range" 
                                   class="form-range" 
                                   name="instances_min" 
                                   min="{{ $stats['min_instances'] }}" 
                                   max="{{ $stats['max_instances'] }}"
                                   value="{{ request('instances_min', $stats['min_instances']) }}"
                                   id="instancesRange">
                            <div class="d-flex justify-content-between">
                                <small>0</small>
                                <small id="instancesValue">{{ number_format(request('instances_min', $stats['min_instances'])) }}</small>
                                <small>{{ number_format($stats['max_instances']) }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- # Features -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#featuresFilter">
                            <span># Features</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="featuresFilter" class="collapse show">
                            <input type="range" 
                                   class="form-range" 
                                   name="features_min" 
                                   min="{{ $stats['min_features'] }}" 
                                   max="{{ $stats['max_features'] }}"
                                   value="{{ request('features_min', $stats['min_features']) }}"
                                   id="featuresRange">
                            <div class="d-flex justify-content-between">
                                <small>0</small>
                                <small id="featuresValue">{{ number_format(request('features_min', $stats['min_features'])) }}</small>
                                <small>{{ number_format($stats['max_features']) }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature Type -->
                    <div class="filter-section mb-3">
                        <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#featureTypeFilter">
                            <span>Feature Type</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="featureTypeFilter" class="collapse show">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Numerical" id="ft_numerical">
                                <label class="form-check-label" for="ft_numerical">Numerical</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Categorical" id="ft_categorical">
                                <label class="form-check-label" for="ft_categorical">Categorical</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clear Filters -->
                    <div class="mt-4">
                        <a href="{{ route('datasets.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-x-circle me-2"></i>Clear All Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <h2 class="mb-4">Browse Datasets</h2>
            
            <!-- Sort and View Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            id="sortDropdown">
                        <i class="bi bi-funnel me-2"></i>
                        SORT BY 
                        @php
                            $sortLabels = [
                                'views' => '# VIEWS',
                                'name' => 'NAME',
                                'instances' => '# INSTANCES',
                                'features' => '# FEATURES',
                                'donated_date' => 'DATE DONATED',
                            ];
                            $currentSort = request('sort', 'views');
                        @endphp
                        {{ $sortLabels[$currentSort] ?? '# VIEWS' }}, {{ strtoupper(request('order', 'desc')) }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ $currentSort == 'views' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'order' => 'desc']) }}">
                            # Views, DESC
                        </a></li>
                        <li><a class="dropdown-item {{ $currentSort == 'name' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc']) }}">
                            Name, ASC
                        </a></li>
                        <li><a class="dropdown-item {{ $currentSort == 'instances' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'instances', 'order' => 'desc']) }}">
                            # Instances, DESC
                        </a></li>
                        <li><a class="dropdown-item {{ $currentSort == 'features' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'features', 'order' => 'desc']) }}">
                            # Features, DESC
                        </a></li>
                        <li><a class="dropdown-item {{ $currentSort == 'donated_date' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'donated_date', 'order' => 'desc']) }}">
                            Date Donated, DESC
                        </a></li>
                    </ul>
                </div>
                
                <!-- Expand/Collapse All Button -->
                <button class="btn btn-primary" id="toggleExpandAll" onclick="toggleAllCards()">
                    <i class="bi bi-eye me-2" id="expandIcon"></i>
                    <span id="expandText">EXPAND ALL</span>
                </button>
            </div>
            
            <!-- Datasets List -->
            <div class="datasets-list">
                @forelse($datasets as $dataset)
                <div class="dataset-card card mb-3" data-dataset-id="{{ $dataset->dataset_id }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                @if($dataset->files->first())
                                    <img src="{{ asset('storage/' . $dataset->files->first()->filename) }}" 
                                         alt="{{ $dataset->name }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 80px;">
                                @else
                                    <div class="dataset-icon-placeholder bg-primary text-white rounded d-flex align-items-center justify-content-center" 
                                         style="height: 80px;">
                                        <i class="bi bi-database fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h5 class="card-title">
                                    <a href="{{ route('datasets.show', $dataset) }}" class="text-decoration-none">
                                        {{ $dataset->name }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($dataset->description, 150) }}
                                </p>
                                
                                <div class="dataset-meta d-flex flex-wrap gap-3 mb-2">
                                    @if($dataset->task)
                                    <span class="meta-item">
                                        <i class="bi bi-search me-1"></i>
                                        {{ $dataset->task->task_name }}
                                    </span>
                                    @endif
                                    
                                    @if($dataset->characteristics)
                                    <span class="meta-item">
                                        <i class="bi bi-database me-1"></i>
                                        {{ Str::limit($dataset->characteristics, 20) }}
                                    </span>
                                    @endif
                                    
                                    @if($dataset->num_instances)
                                    <span class="meta-item">
                                        <i class="bi bi-table me-1"></i>
                                        {{ number_format($dataset->num_instances) }} Instances
                                    </span>
                                    @endif
                                    
                                    @if($dataset->num_features)
                                    <span class="meta-item">
                                        <i class="bi bi-grid-3x3-gap me-1"></i>
                                        {{ number_format($dataset->num_features) }} Features
                                    </span>
                                    @endif
                                    
                                    <!-- Expand/Collapse Toggle Icon -->
                                    <span class="meta-item ms-auto cursor-pointer" onclick="toggleCard({{ $dataset->dataset_id }})">
                                        <i class="bi bi-chevron-down" id="icon-{{ $dataset->dataset_id }}"></i>
                                    </span>
                                </div>
                                
                                <div class="dataset-actions mt-2">
                                    <a href="{{ route('datasets.show', $dataset) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                    @if($dataset->files->first())
                                    <a href="{{ route('datasets.download', [$dataset, $dataset->files->first()]) }}" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expanded Detail Section -->
                        <div class="dataset-detail collapse mt-3 pt-3 border-top" id="detail-{{ $dataset->dataset_id }}">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <strong class="d-block small text-primary">
                                        <i class="bi bi-geo-alt me-1"></i>Subject Area
                                    </strong>
                                    <span class="small">{{ $dataset->subjectArea->area_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong class="d-block small text-primary">
                                        <i class="bi bi-diagram-3 me-1"></i>Feature Type
                                    </strong>
                                    <span class="small">{{ $dataset->feature_type ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong class="d-block small text-primary">
                                        <i class="bi bi-calendar me-1"></i>Date Donated
                                    </strong>
                                    <span class="small">{{ $dataset->donated_date?->format('n/j/Y') ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong class="d-block small text-primary">
                                        <i class="bi bi-eye me-1"></i>Views
                                    </strong>
                                    <span class="small">{{ number_format($dataset->view_count ?? 0) }}</span>
                                </div>
                            </div>
                            
                            @if($dataset->keywords->isNotEmpty())
                            <div class="mt-2">
                                <strong class="d-block small text-primary mb-1">
                                    <i class="bi bi-tags me-1"></i>Keywords
                                </strong>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($dataset->keywords->take(5) as $keyword)
                                    <span class="badge bg-light text-dark">{{ $keyword->keyword_name }}</span>
                                    @endforeach
                                    @if($dataset->keywords->count() > 5)
                                    <span class="badge bg-light text-dark">+{{ $dataset->keywords->count() - 5 }} more</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No datasets found. Try adjusting your filters or <a href="{{ route('datasets.index') }}">clear all filters</a>.
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($datasets->hasPages())
            <div class="mt-4">
                {{ $datasets->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .filters-sidebar {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .filters-title {
        color: var(--uci-blue);
        font-weight: 700;
    }
    
    .filter-section {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 1rem;
    }
    
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
        color: var(--uci-text);
        padding: 0.5rem 0;
    }
    
    .filter-header i {
        transition: transform 0.2s;
    }
    
    .filter-header.collapsed i {
        transform: rotate(-90deg);
    }
    
    .dataset-card {
        transition: box-shadow 0.2s;
    }
    
    .dataset-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .dataset-icon-placeholder {
        width: 100%;
    }
    
    .meta-item {
        font-size: 0.85rem;
        color: var(--uci-gray);
    }
    
    .meta-item i {
        color: var(--uci-blue);
    }
    
    .form-range::-webkit-slider-thumb {
        background: var(--uci-blue);
    }
    
    .form-range::-moz-range-thumb {
        background: var(--uci-blue);
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .dataset-detail {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 1rem;
    }
    
    .dataset-detail .badge {
        font-weight: 500;
    }
    
    .rotate-icon {
        transform: rotate(180deg);
        transition: transform 0.3s;
    }
</style>
@endpush

@push('scripts')
<script>
// Range slider updates
document.getElementById('instancesRange')?.addEventListener('input', function() {
    document.getElementById('instancesValue').textContent = 
        parseInt(this.value).toLocaleString();
});

document.getElementById('featuresRange')?.addEventListener('input', function() {
    document.getElementById('featuresValue').textContent = 
        parseInt(this.value).toLocaleString();
});

// Toggle individual card expand/collapse
function toggleCard(datasetId) {
    const detail = document.getElementById('detail-' + datasetId);
    const icon = document.getElementById('icon-' + datasetId);
    const bsCollapse = new bootstrap.Collapse(detail, {toggle: false});
    
    if (detail.classList.contains('show')) {
        bsCollapse.hide();
        icon.classList.remove('rotate-icon');
    } else {
        bsCollapse.show();
        icon.classList.add('rotate-icon');
    }
}

// Toggle all cards expand/collapse
let allExpanded = false;

function toggleAllCards() {
    const allDetails = document.querySelectorAll('.dataset-detail');
    const allIcons = document.querySelectorAll('[id^="icon-"]');
    const expandIcon = document.getElementById('expandIcon');
    const expandText = document.getElementById('expandText');
    
    allExpanded = !allExpanded;
    
    allDetails.forEach((detail, index) => {
        const bsCollapse = new bootstrap.Collapse(detail, {toggle: false});
        
        if (allExpanded) {
            bsCollapse.show();
            allIcons[index].classList.add('rotate-icon');
        } else {
            bsCollapse.hide();
            allIcons[index].classList.remove('rotate-icon');
        }
    });
    
    // Update button text and icon
    if (allExpanded) {
        expandIcon.className = 'bi bi-eye-slash me-2';
        expandText.textContent = 'COLLAPSE ALL';
    } else {
        expandIcon.className = 'bi bi-eye me-2';
        expandText.textContent = 'EXPAND ALL';
    }
}

// Auto-submit form on filter change (optional)
document.querySelectorAll('.form-check-input').forEach(input => {
    input.addEventListener('change', function() {
        // Uncomment to auto-submit on filter change
        // document.getElementById('filterForm').submit();
    });
});
</script>
@endpush