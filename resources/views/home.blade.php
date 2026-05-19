@extends('layouts.app')
@section('title', 'UCI Machine Learning Repository')
@section('meta_desc', 'A collection of databases for empirical analysis of machine learning algorithms')

@section('content')
<div class="container">
    <!-- Banner Notice -->
    <!-- <div class="banner-notice">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <strong><i class="bi bi-info-circle me-2"></i>Test out our new website</strong>
                <div class="text-muted">Want to visit our new website?</div>
            </div>
            <a href="#" class="btn">NEW WEBSITE</a>
        </div>
    </div> -->

    <!-- Welcome Section -->
    <div class="welcome-section gap-3">
        <h1>Welcome to the UC Irvine Machine Learning Repository</h1>
        <p>
            We currently maintain {{ number_format($totalDatasets ?? 0) }} datasets as a service to the machine learning community. 
            Here, you can donate and find datasets used by millions of people all around the world!
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('datasets.index') }}" class="btn-view">
                <i class="bi bi-grid me-2"></i>VIEW DATASETS
            </a>
            <a href="{{ route('contribute.policy') }}" class="btn-contribute-nav">
                <i class="bi bi-upload me-2"></i>CONTRIBUTE A DATASET
            </a>
        </div>
    </div>

    <!-- Datasets Sections -->
    @if($popularDatasets->isNotEmpty() || $newDatasets->isNotEmpty())
        <div class="datasets-container">
            <!-- Popular Datasets -->
            @if($popularDatasets->isNotEmpty())
            <div class="datasets-section">
                <h2 class="section-title">Popular Datasets</h2>
                
                @foreach($popularDatasets as $dataset)
                    <a href="{{ route('datasets.show', $dataset) }}" class="dataset-card">
                        <div class="dataset-icon">
                            <i class="bi bi-database"></i>
                        </div>
                        <div>
                            <div class="dataset-card-title">{{ $dataset->name }}</div>
                            <div class="dataset-card-desc">{{ Str::limit($dataset->description, 100) }}</div>
                            <div class="dataset-card-meta">
                                @if($dataset->task)
                                    <span class="meta-badge">
                                        <i class="bi bi-search"></i> {{ $dataset->task->task_name }}
                                    </span>
                                @endif
                                <span><i class="bi bi-hdd me-1"></i>{{ number_format($dataset->num_instances ?? 0) }} Instances</span>
                                <span><i class="bi bi-diagram-3 me-1"></i>{{ $dataset->num_features ?? 0 }} Features</span>
                            </div>
                        </div>
                    </a>
                @endforeach
                
                <div class="text-center mt-3">
                    <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary btn-sm">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- New Datasets -->
            @if($newDatasets->isNotEmpty())
            <div class="datasets-section">
                <h2 class="section-title">New Datasets</h2>
                
                @foreach($newDatasets as $dataset)
                    <a href="{{ route('datasets.show', $dataset) }}" class="dataset-card">
                        <div class="dataset-icon">
                            <i class="bi bi-database"></i>
                        </div>
                        <div>
                            <div class="dataset-card-title">{{ $dataset->name }}</div>
                            <div class="dataset-card-desc">{{ Str::limit($dataset->description, 100) }}</div>
                            <div class="dataset-card-meta">
                                @if($dataset->task)
                                    <span class="meta-badge">
                                        <i class="bi bi-search"></i> {{ $dataset->task->task_name }}
                                    </span>
                                @endif
                                <span><i class="bi bi-hdd me-1"></i>
                                    @if(($dataset->num_instances ?? 0) >= 1000000)
                                        {{ number_format(($dataset->num_instances ?? 0) / 1000000, 2) }}M Instances
                                    @else
                                        {{ number_format($dataset->num_instances ?? 0) }} Instances
                                    @endif
                                </span>
                                <span><i class="bi bi-diagram-3 me-1"></i>{{ $dataset->num_features ?? 0 }} Features</span>
                            </div>
                        </div>
                    </a>
                @endforeach
                
                <div class="text-center mt-3">
                    <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary btn-sm">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-database" style="font-size: 4rem; color: #ddd;"></i>
            <h4 class="text-muted mt-3">No datasets yet</h4>
            <p class="text-muted">Be the first to contribute a dataset!</p>
            <a href="{{ route('contribute.policy') }}" class="btn-submit">
                <i class="bi bi-plus-circle me-2"></i>Donate a Dataset
            </a>
        </div>
    @endif
</div>
@endsection