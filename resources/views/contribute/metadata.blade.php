@extends('layouts.app')
@section('title', 'Donate Dataset - Metadata - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">
                We offer users the option to upload their dataset data to our repository.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 14.28%"></div>
            </div>
            <span class="progress-text small text-muted">Page 1 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.metadata.store') }}" method="POST" enctype="multipart/form-data" id="metadataForm">
            @csrf
            
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Form has errors:</h6>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Basic Info Section -->
            <div class="form-card">
                <h5 class="card-section-title">Basic Info <span class="required">*</span></h5>
                
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Dataset Name <span class="required">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" 
                           value="{{ old('name', session('donation_wizard.metadata.name', '')) }}" 
                           required maxlength="255"
                           placeholder="e.g., Iris Flower Dataset">
                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint">Maximum 255 characters.</div>
                </div>

                <div class="form-group mb-3">
                    <label for="abstract" class="form-label">Abstract <span class="required">*</span></label>
                    <textarea class="form-control @error('abstract') is-invalid @enderror" 
                              id="abstract" name="abstract" rows="4" required maxlength="2000"
                              placeholder="Brief description of the dataset...">{{ old('abstract', session('donation_wizard.metadata.abstract', '')) }}</textarea>
                    @error('abstract')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint"><span id="abstractCount">0</span>/2000 characters</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="num_instances" class="form-label">Number of Instances (Rows) <span class="required">*</span></label>
                        <input type="number" class="form-control @error('num_instances') is-invalid @enderror" 
                               id="num_instances" name="num_instances" 
                               value="{{ old('num_instances', session('donation_wizard.metadata.num_instances', '')) }}" 
                               required min="0" placeholder="e.g., 150">
                        @error('num_instances')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="num_features" class="form-label">Number of Features (Columns)</label>
                        <input type="number" class="form-control @error('num_features') is-invalid @enderror" 
                               id="num_features" name="num_features" 
                               value="{{ old('num_features', session('donation_wizard.metadata.num_features', '')) }}" 
                               min="0" placeholder="e.g., 4">
                        @error('num_features')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="doi" class="form-label">Dataset DOI (Optional)</label>
                    <input type="text" class="form-control @error('doi') is-invalid @enderror" 
                           id="doi" name="doi" 
                           value="{{ old('doi', session('donation_wizard.metadata.doi', '')) }}" 
                           pattern="^10\.\d{4,}/.+$"
                           placeholder="e.g., 10.24432/C5XXXX">
                    @error('doi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint">If not provided, a DOI will be generated upon approval.</div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Thumbnail Image (Optional)</label>
                    <div class="upload-box" id="thumbnailDrop" onclick="document.getElementById('graphics').click()">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p class="upload-text">Click or drag image here</p>
                        <small class="text-muted">PNG, JPG, GIF up to 5MB</small>
                    </div>
                    <input type="file" id="graphics" name="graphics" class="d-none" accept="image/png,image/jpeg,image/gif">
                    <div id="graphics-preview" class="mt-2"></div>
                    @error('graphics')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Dataset Characteristics Section -->
            <div class="form-card">
                <h5 class="card-section-title">Dataset Characteristics <span class="required">*</span></h5>
                <p class="text-muted small mb-3">Select all that apply. First selection will be used as primary data_type.</p>
                
                @php
                    $characteristics = ['Multivariate', 'Text', 'Image', 'Time-Series', 'Sequential', 'Tabular', 'Relational', 'Domain-Theory', 'Data-Generator', 'Univariate', 'Spatiotemporal', 'Other'];
                    $oldChars = old('characteristics', session('donation_wizard.metadata.characteristics', []));
                @endphp
                
                <div class="row g-2">
                    @foreach($characteristics as $char)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="characteristics[]" 
                                   value="{{ $char }}" id="char_{{ str_replace('-', '_', $char) }}"
                                   {{ in_array($char, $oldChars) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="char_{{ str_replace('-', '_', $char) }}">
                                {{ $char }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('characteristics')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Subject Area Section -->
            <div class="form-card">
                <h5 class="card-section-title">Subject Area <span class="required">*</span></h5>
                
                @php
                    $subjectAreas = ['Biology', 'Business', 'Climate and Environment', 'Computer Science', 'Engineering', 'Games', 'Health and Medicine', 'Law', 'Physics and Chemistry', 'Social Science', 'Other'];
                    $oldArea = old('subject_area', session('donation_wizard.metadata.subject_area', ''));
                @endphp
                
                <div class="row g-2">
                    @foreach($subjectAreas as $area)
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="subject_area" 
                                   value="{{ $area }}" id="area_{{ str_replace(' ', '_', $area) }}"
                                   {{ $oldArea == $area ? 'checked' : '' }}>
                            <label class="form-check-label small" for="area_{{ str_replace(' ', '_', $area) }}">
                                {{ $area }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('subject_area')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Associated Tasks Section -->
            <div class="form-card">
                <h5 class="card-section-title">Associated Tasks <span class="required">*</span></h5>
                <p class="text-muted small mb-3">Select all applicable tasks. First selection will be used as primary task_type.</p>
                
                @php
                    $tasks = ['Classification', 'Regression', 'Clustering', 'Causal-Discovery', 'Relational-Learning', 'Other'];
                    $oldTasks = old('associated_tasks', session('donation_wizard.metadata.associated_tasks', []));
                @endphp
                
                <div class="row g-2">
                    @foreach($tasks as $task)
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="associated_tasks[]" 
                                   value="{{ $task }}" id="task_{{ str_replace('-', '_', $task) }}"
                                   {{ in_array($task, $oldTasks) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="task_{{ str_replace('-', '_', $task) }}">
                                {{ $task }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('associated_tasks')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Feature Types Section -->
            <div class="form-card">
                <h5 class="card-section-title">Feature Types (Optional)</h5>
                <p class="text-muted small mb-3">Types of values that features can take.</p>
                
                @php
                    $featureTypes = ['Categorical', 'Integer', 'Real', 'Text', 'Binary', 'Ordinal', 'Nominal', 'DateTime'];
                    $oldFTypes = old('feature_types', session('donation_wizard.metadata.feature_types', []));
                @endphp
                
                <div class="row g-2">
                    @foreach($featureTypes as $ftype)
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="feature_types[]" 
                                   value="{{ $ftype }}" id="ftype_{{ strtolower($ftype) }}"
                                   {{ in_array($ftype, $oldFTypes) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="ftype_{{ strtolower($ftype) }}">
                                {{ $ftype }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.policy') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Policy
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-title { color: #0077b6; font-weight: 700; font-size: 1.75rem; }
    .page-description { color: #666; font-size: 0.95rem; line-height: 1.6; }
    .progress-wrapper { display: flex; align-items: center; gap: 1rem; }
    .progress { flex: 1; background: #e9ecef; border-radius: 4px; }
    .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.25rem; }
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; }
    .required { color: #dc3545; }
    .form-label { font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.4rem; }
    .form-control { border-radius: 6px; padding: 0.6rem 0.8rem; font-size: 0.9rem; }
    .form-control:focus { border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,0.15); }
    .form-hint { font-size: 0.8rem; color: #6c757d; margin-top: 0.3rem; }
    .upload-box { border: 2px dashed #0077b6; border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer; background: #f8f9fa; transition: all 0.2s; }
    .upload-box:hover { background: #e9f5f9; border-color: #005f73; }
    .upload-icon { font-size: 2rem; color: #0077b6; display: block; margin-bottom: 0.5rem; }
    .upload-text { margin: 0; color: #333; font-size: 0.95rem; font-weight: 500; }
    .form-check-label { cursor: pointer; font-size: 0.9rem; }
    .form-navigation { margin-top: 2rem; margin-bottom: 3rem; }
    @media (max-width: 768px) {
        .form-card { padding: 1.25rem; }
        .page-title { font-size: 1.4rem; }
    }
</style>
@endpush

@push('scripts')
<script>
// Character counter for abstract
const abstract = document.getElementById('abstract');
const abstractCount = document.getElementById('abstractCount');
if (abstract && abstractCount) {
    abstractCount.textContent = abstract.value.length;
    abstract.addEventListener('input', () => {
        abstractCount.textContent = abstract.value.length;
    });
}

// Image preview
document.getElementById('graphics').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('graphics-preview');
    
    if (file) {
        if (!file.type.match('image.*')) {
            alert('Please select an image file (PNG, JPG, GIF)');
            this.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Image must be less than 5MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Drag & drop for image
const dropZone = document.getElementById('thumbnailDrop');
if (dropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => 
        dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); })
    );
    ['dragenter', 'dragover'].forEach(evt => 
        dropZone.addEventListener(evt, () => dropZone.style.borderColor = '#005f73')
    );
    ['dragleave', 'drop'].forEach(evt => 
        dropZone.addEventListener(evt, () => dropZone.style.borderColor = '#0077b6')
    );
    dropZone.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (file) {
            const input = document.getElementById('graphics');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
    });
}

// Form validation before submit
document.getElementById('metadataForm').addEventListener('submit', function(e) {
    const chars = document.querySelectorAll('input[name="characteristics[]"]:checked');
    if (chars.length === 0) {
        e.preventDefault();
        alert('Please select at least one dataset characteristic');
        return false;
    }
    
    const area = document.querySelector('input[name="subject_area"]:checked');
    if (!area) {
        e.preventDefault();
        alert('Please select a subject area');
        return false;
    }
    
    const tasks = document.querySelectorAll('input[name="associated_tasks[]"]:checked');
    if (tasks.length === 0) {
        e.preventDefault();
        alert('Please select at least one associated task');
        return false;
    }
});
</script>
@endpush