@props(['dataset', 'showStats' => true])

<div class="card h-100 dataset-card-mini hover-lift">
    <div class="card-body">
        <!-- Thumbnail / Icon -->
        <div class="text-center mb-3">
            @if($dataset->thumbnail_url)
                <img src="{{ $dataset->thumbnail_url }}" 
                     alt="{{ $dataset->display_name ?? $dataset->name }}" 
                     class="img-fluid rounded" 
                     style="max-height: 80px; object-fit: cover;">
            @else
                <div class="bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-database fs-3"></i>
                </div>
            @endif
        </div>
        
        <!-- Title -->
        <h6 class="card-title fw-semibold mb-2 text-center">
            <a href="{{ route('datasets.show', [$dataset, $dataset->slug]) }}" 
               class="text-decoration-none text-dark stretched-link">
                {{ $dataset->display_name ?? $dataset->name }}
            </a>
        </h6>
        
        <!-- Badges: Data Type & Task Type (langsung dari field ENUM) -->
        <div class="d-flex justify-content-center gap-1 mb-2">
            @if($dataset->data_type)
                <span class="badge bg-info text-dark" title="Data Type">{{ $dataset->data_type }}</span>
            @endif
            @if($dataset->task_type)
                <span class="badge bg-success" title="Task Type">{{ $dataset->task_type }}</span>
            @endif
        </div>
        
        <!-- Description -->
        <p class="card-text small text-muted text-center mb-3">
            {{ Str::limit($dataset->description ?? $dataset->abstract, 80) }}
        </p>
        
        <!-- Stats (optional) -->
        @if($showStats)
        <div class="d-flex justify-content-center gap-3 small text-muted mb-3">
            @if($dataset->num_instances !== null)
                <span title="Instances">
                    <i class="bi bi-table me-1"></i>
                    {{ $dataset->num_instances >= 1000000 ? number_format($dataset->num_instances / 1000000, 1) . 'M' : number_format($dataset->num_instances) }}
                </span>
            @endif
            @if($dataset->num_features !== null)
                <span title="Features">
                    <i class="bi bi-grid-3x3-gap me-1"></i>{{ number_format($dataset->num_features) }}
                </span>
            @endif
        </div>
        @endif
        
        <!-- Subject Area (field VARCHAR langsung) -->
        @if($dataset->subject_area)
            <div class="text-center mb-3">
                <small class="text-muted">
                    <i class="bi bi-folder me-1"></i>{{ $dataset->subject_area }}
                </small>
            </div>
        @endif
        
        <!-- Quick Action -->
        <div class="text-center">
            <a href="{{ route('datasets.show', [$dataset, $dataset->slug]) }}" 
               class="btn btn-sm btn-outline-primary stretched-link">
                View Details
            </a>
        </div>
    </div>
    
    <!-- Hover overlay for link -->
    <div class="card-overlay"></div>
</div>

@push('styles')
<style>
    .dataset-card-mini {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--bs-border-color);
    }
    
    .hover-lift {
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-color: var(--uci-blue, #0077b6);
    }
    
    .card-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: linear-gradient(to top, rgba(0,119,182,0.03), transparent);
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .hover-lift:hover .card-overlay {
        opacity: 1;
    }
    
    .stretched-link::after {
        z-index: 1;
    }
</style>
@endpush