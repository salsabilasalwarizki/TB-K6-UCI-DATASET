@extends('layouts.app')
@section('title', 'Donate a Dataset - UCI Machine Learning Repository')

@section('content')
<div class="contribute-container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.datasets') }}">My Datasets</a></li>
            <li class="breadcrumb-item active">Donate Dataset</li>
        </ol>
    </nav>
    
    <h1 class="text-center mb-2" style="color: var(--uci-blue); font-weight: 700;">
        <i class="bi bi-cloud-arrow-up me-2"></i>Donate a Dataset
    </h1>
    <p class="text-center text-muted mb-4">Fill in the details below to contribute your dataset to the repository.</p>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('contribute.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Dataset Information -->
        <div class="form-section">
            <h5><i class="bi bi-info-circle me-2"></i>Dataset Information</h5>
            
            <div class="mb-3">
                <label class="form-label">Dataset Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required 
                       placeholder="e.g., Iris Flower Dataset">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="5" required 
                          placeholder="Provide a detailed description of your dataset...">{{ old('description') }}</textarea>
            </div>
        </div>
        
        <!-- Dataset Properties -->
        <div class="form-section">
            <h5><i class="bi bi-sliders me-2"></i>Dataset Properties</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Task Type *</label>
                    <select name="task_id" class="form-select" required>
                        <option value="">Select Task Type</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->task_id }}" {{ old('task_id') == $task->task_id ? 'selected' : '' }}>
                                {{ $task->task_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Subject Area *</label>
                    <select name="subject_area_id" class="form-select" required>
                        <option value="">Select Subject Area</option>
                        @foreach($subjectAreas as $area)
                            <option value="{{ $area->area_id }}" {{ old('subject_area_id') == $area->area_id ? 'selected' : '' }}>
                                {{ $area->area_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Characteristics *</label>
                    <select name="characteristics" class="form-select" required>
                        <option value="">Select Characteristics</option>
                        <option value="Multivariate" {{ old('characteristics') == 'Multivariate' ? 'selected' : '' }}>Multivariate</option>
                        <option value="Univariate" {{ old('characteristics') == 'Univariate' ? 'selected' : '' }}>Univariate</option>
                        <option value="Sequential" {{ old('characteristics') == 'Sequential' ? 'selected' : '' }}>Sequential</option>
                        <option value="Time-Series" {{ old('characteristics') == 'Time-Series' ? 'selected' : '' }}>Time-Series</option>
                        <option value="Spatial" {{ old('characteristics') == 'Spatial' ? 'selected' : '' }}>Spatial</option>
                        <option value="Text" {{ old('characteristics') == 'Text' ? 'selected' : '' }}>Text</option>
                        <option value="Domain-Theory" {{ old('characteristics') == 'Domain-Theory' ? 'selected' : '' }}>Domain-Theory</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Feature Type *</label>
                    <select name="feature_type" class="form-select" required>
                        <option value="">Select Feature Type</option>
                        <option value="Continuous" {{ old('feature_type') == 'Continuous' ? 'selected' : '' }}>Continuous</option>
                        <option value="Categorical" {{ old('feature_type') == 'Categorical' ? 'selected' : '' }}>Categorical</option>
                        <option value="Integer" {{ old('feature_type') == 'Integer' ? 'selected' : '' }}>Integer</option>
                        <option value="Mixed" {{ old('feature_type') == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                        <option value="Real" {{ old('feature_type') == 'Real' ? 'selected' : '' }}>Real</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Number of Instances *</label>
                    <input type="number" name="num_instances" class="form-control" value="{{ old('num_instances') }}" required min="0">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Number of Features *</label>
                    <input type="number" name="num_features" class="form-control" value="{{ old('num_features') }}" required min="0">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Has Missing Values?</label>
                    <select name="has_missing_values" class="form-select">
                        <option value="0" {{ old('has_missing_values', '0') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('has_missing_values') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">License *</label>
                <select name="license_id" class="form-select" required>
                    <option value="">Select License</option>
                    @foreach($licenses as $license)
                        <option value="{{ $license->license_id }}" {{ old('license_id') == $license->license_id ? 'selected' : '' }}>
                            {{ $license->license_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Keywords</label>
                <div class="row">
                    @foreach($keywords as $keyword)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keywords[]" 
                                       value="{{ $keyword->keyword_id }}" id="kw_{{ $keyword->keyword_id }}"
                                    {{ in_array($keyword->keyword_id, old('keywords', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kw_{{ $keyword->keyword_id }}">
                                    {{ $keyword->keyword_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- File Upload -->
        <div class="form-section">
            <h5><i class="bi bi-upload me-2"></i>Upload Dataset Files</h5>
            
            <div class="mb-3">
                <label class="form-label">Data Files (CSV, ARFF, TXT, JSON, ZIP)</label>
                <input type="file" name="data_files[]" class="form-control" multiple accept=".csv,.txt,.arff,.json,.zip">
                <div class="form-text">Maximum file size: 50MB per file. Multiple files allowed.</div>
            </div>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> Variables (columns) will be automatically extracted from the first uploaded CSV file.
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="text-center mt-4 mb-5">
            <button type="submit" class="btn btn-submit me-3">
                <i class="bi bi-check-circle me-2"></i>Submit Dataset
            </button>
            <a href="{{ route('profile.datasets') }}" class="btn btn-cancel">
                <i class="bi bi-arrow-left me-2"></i>Cancel
            </a>
        </div>
    </form>
</div>
@endsection