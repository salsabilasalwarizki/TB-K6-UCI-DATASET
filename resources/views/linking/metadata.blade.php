@extends('layouts.app')
@section('title', 'Link External Dataset - UCI Machine Learning Repository')

@section('content')

<div class="linking-page">
    <div class="container">
        <!-- Header -->
        <div class="linking-header">
            <h1 class="page-title">Dataset Linking Form</h1>
            <p class="page-description">
                We offer users the option to list a dataset in our dataset index without actually hosting the data itself in our repository.
            </p>
            <p class="page-description">
                Instead, users can provide a link to an external webpage from which the dataset can be downloaded. Linking a dataset in 
                our repository can help increase the dataset's visibility and also allows users to use our dataset filtering and search 
                capabilities to identify if the dataset is useful for them.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 16.67%"></div>
            </div>
            <span class="progress-text">Page 1 / 6</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.linking.metadata.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Info Section -->
            <div class="form-card">
                <h5 class="card-section-title">Basic Info</h5>
                
                <div class="form-group">
                    <label for="external_url" class="form-label">Dataset URL <span class="required">*</span></label>
                    <input type="url" 
                           class="form-control" 
                           id="external_url" 
                           name="external_url" 
                           value="{{ old('external_url') }}" 
                           required
                           placeholder="https://example.com/dataset">
                    <div class="form-hint">The direct link to the dataset or its download page.</div>
                    @error('external_url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Dataset Name <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           placeholder="e.g., Iris Dataset">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="abstract" class="form-label">Abstract <span class="required">*</span></label>
                    <textarea class="form-control" 
                              id="abstract" 
                              name="abstract" 
                              rows="4" 
                              required
                              maxlength="1000"
                              placeholder="Provide a detailed description of the dataset...">{{ old('abstract') }}</textarea>
                    <div class="form-hint">Maximum 1000 characters.</div>
                    @error('abstract')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="num_instances" class="form-label">Number of Instances (Rows) in Dataset <span class="required">*</span></label>
                    <input type="number" 
                           class="form-control" 
                           id="num_instances" 
                           name="num_instances" 
                           value="{{ old('num_instances') }}" 
                           required 
                           min="0"
                           placeholder="e.g., 150">
                    @error('num_instances')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="num_features" class="form-label">Number of Features in Dataset</label>
                    <input type="number" 
                           class="form-control" 
                           id="num_features" 
                           name="num_features" 
                           value="{{ old('num_features') }}" 
                           min="0"
                           placeholder="e.g., 4">
                    @error('num_features')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="doi" class="form-label">Dataset DOI</label>
                    <input type="text" 
                           class="form-control" 
                           id="doi" 
                           name="doi" 
                           value="{{ old('doi') }}"
                           placeholder="e.g., 10.24433/CO.1234567.v1">
                    <div class="form-hint">Digital Object Identifier for the dataset (if available).</div>
                </div>

                <!-- Graphics Upload Section -->
                <div class="form-group">
                    <label class="form-label">
                        Graphics 
                        <i class="bi bi-info-circle tooltip-icon" 
                           data-bs-toggle="tooltip" 
                           title="Upload a representative image for your dataset"></i>
                    </label>
                    <div class="upload-box" onclick="document.getElementById('graphics').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                        <small class="text-muted">PNG, JPG, GIF (max 5MB)</small>
                    </div>
                    <input type="file" id="graphics" name="graphics" class="d-none" accept="image/*">
                    <div id="graphics-preview" class="mt-2"></div>
                    @error('graphics')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dataset Characteristics Section -->
            <div class="form-card">
                <h5 class="card-section-title">Dataset Characteristics <span class="required">*</span></h5>
                
                @php
                    $characteristics = ['Tabular', 'Sequential', 'Multivariate', 'Time-Series', 'Text', 'Image', 'Spatiotemporal', 'Other'];
                @endphp
                
                @foreach($characteristics as $char)
                <div class="checkbox-item">
                    <span class="checkbox-label">{{ $char }}</span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="characteristics[]" 
                           value="{{ $char }}" 
                           id="char_{{ $char }}"
                           {{ in_array($char, old('characteristics', [])) ? 'checked' : '' }}>
                </div>
                @endforeach
                
                @error('characteristics')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Subject Area Section -->
            <div class="form-card">
                <h5 class="card-section-title">Subject Area <span class="required">*</span></h5>
                
                @php
                    $subjectAreas = [
                        'Biology',
                        'Business',
                        'Climate and Environment',
                        'Computer Science',
                        'Engineering',
                        'Games',
                        'Health and Medicine',
                        'Law',
                        'Physics and Chemistry',
                        'Social Science',
                        'Other'
                    ];
                @endphp
                
                @foreach($subjectAreas as $area)
                <div class="radio-item">
                    <span class="radio-label">{{ $area }}</span>
                    <input type="radio" 
                           class="custom-radio" 
                           name="subject_area" 
                           value="{{ $area }}" 
                           id="area_{{ $loop->index }}"
                           {{ old('subject_area') == $area ? 'checked' : '' }}>
                </div>
                @endforeach
                
                @error('subject_area')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Associated Tasks Section -->
            <div class="form-card">
                <h5 class="card-section-title">Associated Tasks <span class="required">*</span></h5>
                
                @php
                    $tasks = ['Classification', 'Regression', 'Clustering', 'Other'];
                @endphp
                
                @foreach($tasks as $task)
                <div class="checkbox-item">
                    <span class="checkbox-label">{{ $task }}</span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="associated_tasks[]" 
                           value="{{ $task }}" 
                           id="task_{{ $task }}"
                           {{ in_array($task, old('associated_tasks', [])) ? 'checked' : '' }}>
                </div>
                @endforeach
                
                @error('associated_tasks')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Feature Types Section -->
            <div class="form-card">
                <h5 class="card-section-title">Feature Types</h5>
                
                @php
                    $featureTypes = ['Real', 'Categorical', 'Integer'];
                @endphp
                
                @foreach($featureTypes as $ftype)
                <div class="checkbox-item">
                    <span class="checkbox-label">{{ $ftype }}</span>
                    <input type="checkbox" 
                           class="custom-checkbox" 
                           name="feature_types[]" 
                           value="{{ $ftype }}" 
                           id="ftype_{{ $ftype }}"
                           {{ in_array($ftype, old('feature_types', [])) ? 'checked' : '' }}>
                </div>
                @endforeach
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <button type="submit" class="btn-next">
                    NEXT <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .linking-page {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .page-title {
        padding-top: 50px;
        color: #0077b6;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .page-description {
        color: #555;
        line-height: 1.7;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .progress-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    
    .progress {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-text {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
    }
    
    .form-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }
    
    .card-section-title {
        color: #0077b6;
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
    }
    
    .required {
        color: #dc3545;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-hint {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.4rem;
    }
    
    .upload-box {
        border: 2px dashed #0077b6;
        border-radius: 8px;
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f8f9fa;
    }
    
    .upload-box:hover {
        background-color: #e9f5f9;
        border-color: #005f73;
    }
    
    .upload-icon {
        font-size: 2.5rem;
        color: #0077b6;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .upload-text {
        margin: 0;
        color: #333;
        font-size: 0.95rem;
    }
    
    #graphics-preview img {
        max-width: 200px;
        border-radius: 8px;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .checkbox-item, .radio-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .checkbox-item:last-child, .radio-item:last-child {
        border-bottom: none;
    }
    
    .checkbox-label, .radio-label {
        font-size: 0.95rem;
        color: #333;
    }
    
    .custom-checkbox, .custom-radio {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #0077b6;
    }
    
    .tooltip-icon {
        color: #0077b6;
        cursor: help;
        font-size: 0.9rem;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }
    
    .form-navigation {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
    .btn-next {
        background-color: #0077b6;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-next:hover {
        background-color: #005f73;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem 1rem;
        }
        
        .form-card {
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Graphics upload preview
document.getElementById('graphics').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            this.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Only JPG, PNG, and GIF files are allowed');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('graphics-preview').innerHTML = 
                '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; border-radius: 8px; margin-top: 1rem;">' +
                '<p class="text-success mt-2 mb-0"><i class="bi bi-check-circle me-1"></i>' + file.name + '</p>';
        };
        reader.readAsDataURL(file);
    }
});

// Tooltip initialization
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush