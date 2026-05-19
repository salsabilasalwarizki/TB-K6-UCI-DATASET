@extends('layouts.app')
@section('title', 'Donate Dataset - UCI Machine Learning Repository')

@section('content')

<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">
                We offer users the option to upload their dataset data to our repository.
            </p>
            <p class="page-description">
                Users can provide tabular or non-tabular dataset data which will be made publicly available on our repository. 
                Donators are free to edit their donated datasets, but edits must be approved before finalizing.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 14.28%"></div>
            </div>
            <span class="progress-text">Page 1 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.metadata.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Info Section -->
            <div class="form-card">
                <h5 class="card-section-title">Basic Info</h5>
                
                <div class="form-group">
                    <label for="name" class="form-label">Dataset Name <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required>
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
                              maxlength="1000">{{ old('abstract') }}</textarea>
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
                           min="0">
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
                           min="0">
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
                           value="{{ old('doi') }}">
                    <div class="form-hint">If a DOI is not provided, one will be generated for the dataset.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Graphics <i class="bi bi-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Upload a representative image for your dataset"></i></label>
                    <div class="upload-box" onclick="document.getElementById('graphics').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Choose a file or drag and drop here</p>
                    </div>
                    <input type="file" id="graphics" name="graphics" class="d-none" accept="image/*">
                    <div id="graphics-preview" class="mt-2"></div>
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
                    NEXT
                </button>
            </div>
        </form>
    </div>
</div>


@endsection

@push('styles')
<style>
    
    .page-title {
        padding-top : 50px;
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
    
    /* Footer UCI */
    .uci-footer {
        background-color: #0077b6;
        color: white;
        padding: 3rem 0 2rem;
        margin-top: 4rem;
    }
    
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 2rem;
    }
    
    .footer-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .footer-logo {
        width: 60px;
        height: 60px;
    }
    
    .footer-brand-text {
        font-size: 0.85rem;
        line-height: 1.4;
        font-weight: 600;
    }
    
    .footer-col h6 {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    
    .footer-col a {
        display: block;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        transition: color 0.2s;
    }
    
    .footer-col a:hover {
        color: white;
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
        
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .footer-brand {
            flex-direction: column;
            text-align: center;
        }
        
        .footer-col {
            text-align: center;
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
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('graphics-preview').innerHTML = 
                '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; border-radius: 8px; margin-top: 1rem;">';
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