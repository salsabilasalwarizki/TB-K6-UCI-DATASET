@extends('layouts.app')
@section('title', 'Dataset Donation - Files - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 4 of 7: Dataset Files</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 57%"></div>
            </div>
            <span class="progress-text small text-muted">Page 4 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.files.store') }}" method="POST" enctype="multipart/form-data" id="filesForm">
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

            <!-- Files Section -->
            <div class="form-card">
                <h5 class="card-section-title">Dataset Files <span class="required">*</span></h5>
                <p class="text-muted small mb-4">
                    Upload the actual dataset files. At least one file is required.
                    <br>Supported formats: CSV, ARFF, TXT, JSON, XLSX, ZIP (max 500MB per file)
                </p>

                <!-- Files Container -->
                <div id="filesContainer">
                    @php
                        $filesData = old('files', session('donation_wizard.files', []));
                        if (empty($filesData)) {
                            $filesData = [['filename' => '', 'file_format' => 'csv', 'file_role' => 'data', 'is_default' => true]];
                        }
                    @endphp
                    
                    @foreach($filesData as $index => $file)
                    <div class="file-item p-3 mb-3 border rounded" data-index="{{ $index }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-primary">File {{ $index + 1 }}</h6>
                            @if($index > 0)
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile({{ $index }})">
                                <i class="bi bi-trash me-1"></i>Remove
                            </button>
                            @endif
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Display Filename <span class="required">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('files.'.$index.'.filename') is-invalid @enderror" 
                                       name="files[{{ $index }}][filename]" 
                                       value="{{ $file['filename'] ?? '' }}" 
                                       required maxlength="255"
                                       placeholder="e.g., dataset.csv">
                                @error('files.'.$index.'.filename')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Format <span class="required">*</span></label>
                                <select class="form-select form-select-sm @error('files.'.$index.'.file_format') is-invalid @enderror" 
                                        name="files[{{ $index }}][file_format]" required>
                                    @foreach(['csv', 'arff', 'txt', 'json', 'xlsx', 'zip', 'tar.gz', 'pdf', 'other'] as $fmt)
                                    <option value="{{ $fmt }}" {{ ($file['file_format'] ?? '') == $fmt ? 'selected' : '' }}>{{ strtoupper($fmt) }}</option>
                                    @endforeach
                                </select>
                                @error('files.'.$index.'.file_format')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Role <span class="required">*</span></label>
                                <select class="form-select form-select-sm @error('files.'.$index.'.file_role') is-invalid @enderror" 
                                        name="files[{{ $index }}][file_role]" required>
                                    <option value="data" {{ ($file['file_role'] ?? '') == 'data' ? 'selected' : '' }}>Data</option>
                                    <option value="documentation" {{ ($file['file_role'] ?? '') == 'documentation' ? 'selected' : '' }}>Documentation</option>
                                    <option value="code" {{ ($file['file_role'] ?? '') == 'code' ? 'selected' : '' }}>Code</option>
                                    <option value="example" {{ ($file['file_role'] ?? '') == 'example' ? 'selected' : '' }}>Example</option>
                                    <option value="test" {{ ($file['file_role'] ?? '') == 'test' ? 'selected' : '' }}>Test</option>
                                    <option value="other" {{ ($file['file_role'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('files.'.$index.'.file_role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small">Upload File</label>
                                <input type="file" class="form-control form-control-sm" 
                                       name="files[{{ $index }}][file]" 
                                       accept=".csv,.txt,.arff,.json,.xlsx,.zip,.tar.gz,.pdf">
                                <div class="form-hint">Max 500MB. Leave empty to skip upload for this entry.</div>
                                @if(isset($file['original_filename']))
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Already uploaded: {{ $file['original_filename'] }}</small>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="files[{{ $index }}][is_default]" value="1"
                                           id="default_{{ $index }}"
                                           {{ !empty($file['is_default']) ? 'checked' : '' }}
                                           onchange="updateDefaultFile({{ $index }})">
                                    <label class="form-check-label small" for="default_{{ $index }}">
                                        Set as default download file
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Add File Button -->
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addFile()">
                    <i class="bi bi-plus-circle me-1"></i>Add Another File
                </button>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.creators') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Template for New File -->
<template id="fileTemplate">
    <div class="file-item p-3 mb-3 border rounded" data-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-primary">File __INDEX__</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(__INDEX__)">
                <i class="bi bi-trash me-1"></i>Remove
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small">Display Filename <span class="required">*</span></label>
                <input type="text" class="form-control form-control-sm" name="files[__INDEX__][filename]" required maxlength="255" placeholder="e.g., dataset.csv">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Format <span class="required">*</span></label>
                <select class="form-select form-select-sm" name="files[__INDEX__][file_format]" required>
                    <option value="csv">CSV</option>
                    <option value="arff">ARFF</option>
                    <option value="txt">TXT</option>
                    <option value="json">JSON</option>
                    <option value="xlsx">XLSX</option>
                    <option value="zip">ZIP</option>
                    <option value="tar.gz">TAR.GZ</option>
                    <option value="pdf">PDF</option>
                    <option value="other">OTHER</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Role <span class="required">*</span></label>
                <select class="form-select form-select-sm" name="files[__INDEX__][file_role]" required>
                    <option value="data">Data</option>
                    <option value="documentation">Documentation</option>
                    <option value="code">Code</option>
                    <option value="example">Example</option>
                    <option value="test">Test</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label small">Upload File</label>
                <input type="file" class="form-control form-control-sm" name="files[__INDEX__][file]" accept=".csv,.txt,.arff,.json,.xlsx,.zip,.tar.gz,.pdf">
                <div class="form-hint">Max 500MB</div>
            </div>
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="files[__INDEX__][is_default]" value="1" id="default___INDEX__" onchange="updateDefaultFile(__INDEX__)">
                    <label class="form-check-label small" for="default___INDEX__">Set as default download file</label>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let fileIndex = {{ count($filesData) }};

function addFile() {
    const template = document.getElementById('fileTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.querySelector('.file-item').outerHTML.replace(/__INDEX__/g, fileIndex);
    
    document.getElementById('filesContainer').insertAdjacentHTML('beforeend', html);
    fileIndex++;
}

function removeFile(index) {
    const item = document.querySelector(`.file-item[data-index="${index}"]`);
    if (item) {
        item.remove();
        // Re-index remaining items
        document.querySelectorAll('.file-item').forEach((el, i) => {
            el.setAttribute('data-index', i);
            el.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                if (input.id) input.id = input.id.replace(/\d+/, i);
            });
        });
        fileIndex = document.querySelectorAll('.file-item').length;
    }
}

function updateDefaultFile(selectedIndex) {
    // Only one file can be default
    document.querySelectorAll('input[name*="[is_default]"]').forEach((cb, i) => {
        if (i !== selectedIndex) cb.checked = false;
    });
}

// Form validation
document.getElementById('filesForm').addEventListener('submit', function(e) {
    const files = document.querySelectorAll('.file-item');
    if (files.length === 0) {
        e.preventDefault();
        alert('Please add at least one file');
        return false;
    }
    
    let valid = true;
    files.forEach(file => {
        const filename = file.querySelector('input[name*="[filename]"]');
        const format = file.querySelector('select[name*="[file_format]"]');
        const role = file.querySelector('select[name*="[file_role]"]');
        
        if (!filename.value.trim()) {
            valid = false;
            filename.classList.add('is-invalid');
        } else {
            filename.classList.remove('is-invalid');
        }
        if (!format.value) {
            valid = false;
            format.classList.add('is-invalid');
        } else {
            format.classList.remove('is-invalid');
        }
        if (!role.value) {
            valid = false;
            role.classList.add('is-invalid');
        } else {
            role.classList.remove('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields for each file');
        return false;
    }
});
</script>
@endpush