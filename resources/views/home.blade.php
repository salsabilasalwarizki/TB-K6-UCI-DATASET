@extends('layouts.app')
@section('title', 'UCI Machine Learning Repository')
@section('meta_desc', 'A collection of databases for empirical analysis of machine learning algorithms')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Banner -->
    <section class="hero-banner py-5" style="background: linear-gradient(135deg, #0077b6 0%, #005f73 100%); color: white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">
                        Welcome to the UC Irvine Machine Learning Repository
                    </h1>
                    <p class="lead mb-4 opacity-90">
                        We currently maintain <strong>{{ number_format($stats['total'] ?? 0) }}</strong> datasets 
                        as a service to the machine learning community. Here, you can donate and find datasets 
                        used by millions of people all around the world!
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('datasets.index') }}" class="btn btn-light btn-lg fw-semibold">
                            <i class="bi bi-grid-3x3-gap me-2"></i>VIEW DATASETS
                        </a>
                        <a href="{{ route('contribute.policy') }}" class="btn btn-outline-light btn-lg fw-semibold">
                            <i class="bi bi-upload me-2"></i>CONTRIBUTE
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="bi bi-database display-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar py-3 bg-light border-bottom">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-primary fs-4">
                            {{ number_format($stats['total'] ?? 0) }}
                        </div>
                        <div class="stat-label text-muted small">Total Datasets</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-success fs-4">
                            {{ number_format($stats['by_data_type']->sum(fn($v) => $v) ?? 0) }}
                        </div>
                        <div class="stat-label text-muted small">Data Types</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-info fs-4">
                            {{ number_format($stats['by_task_type']->sum(fn($v) => $v) ?? 0) }}
                        </div>
                        <div class="stat-label text-muted small">Task Types</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-value fw-bold text-warning fs-4">
                            {{ number_format(($stats['recent_downloads'] ?? collect())->sum('download_count')) }}
                        </div>
                        <div class="stat-label text-muted small">Downloads</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        @if($popularDatasets->isNotEmpty() || $newDatasets->isNotEmpty())
            <div class="row g-4">
                
                <!-- Popular Datasets -->
                @if($popularDatasets->isNotEmpty())
                <div class="col-12">
                    <div class="section-header d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">
                            <i class="bi bi-fire text-danger me-2"></i>Popular Datasets
                        </h2>
                        <a href="{{ route('datasets.index', ['sort' => 'view_count', 'order' => 'desc']) }}" 
                           class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="row g-3">
                        @foreach($popularDatasets as $dataset)
                        <div class="col-md-6 col-lg-3">
                            @include('components.dataset-card-mini', ['dataset' => $dataset, 'showStats' => true])
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- New Datasets -->
                @if($newDatasets->isNotEmpty())
                <div class="col-12">
                    <div class="section-header d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>New Datasets
                        </h2>
                        <a href="{{ route('datasets.index', ['sort' => 'created_at', 'order' => 'desc']) }}" 
                           class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="row g-3">
                        @foreach($newDatasets as $dataset)
                        <div class="col-md-6 col-lg-3">
                            @include('components.dataset-card-mini', ['dataset' => $dataset, 'showStats' => false])
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5 my-5">
                <div class="mb-4">
                    <i class="bi bi-database display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted mb-3">No datasets available yet</h4>
                <p class="text-muted mb-4">Be the first to contribute a dataset to the repository!</p>
                <a href="{{ route('contribute.policy') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Donate a Dataset
                </a>
            </div>
        @endif

        <!-- Browse by Category -->
        <section class="browse-categories mt-5 pt-4 border-top">
            <h3 class="mb-4">Browse by Category</h3>
            <div class="row g-3">
                <!-- Data Types -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-diagram-3 me-2"></i>Data Types
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(($stats['by_data_type'] ?? collect())->take(8) as $type => $count)
                                @if($type)
                                <a href="{{ route('datasets.index', ['data_type' => $type]) }}" 
                                   class="badge bg-light text-dark border text-decoration-none d-flex align-items-center gap-1">
                                    {{ $type }}
                                    <span class="badge bg-secondary rounded-pill ms-1">{{ $count }}</span>
                                </a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Types -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-semibold">
                            <i class="bi bi-search me-2"></i>Task Types
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(($stats['by_task_type'] ?? collect())->take(8) as $task => $count)
                                @if($task)
                                <a href="{{ route('datasets.index', ['task_type' => $task]) }}" 
                                   class="badge bg-light text-dark border text-decoration-none d-flex align-items-center gap-1">
                                    {{ $task }}
                                    <span class="badge bg-secondary rounded-pill ms-1">{{ $count }}</span>
                                </a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-banner {
        position: relative;
        overflow: hidden;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    
    .stats-bar .stat-value {
        line-height: 1;
    }
    
    .stats-bar .stat-label {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    .section-title {
        font-weight: 700;
        color: var(--bs-body-color);
    }
    
    .section-header {
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bs-border-color);
    }
    
    .browse-categories .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .browse-categories .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
</style>
@endpush