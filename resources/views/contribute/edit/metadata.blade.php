@extends('layouts.app')
@section('title', 'Edit Dataset Metadata - UCI ML Repository')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Dataset Metadata
                    </h2>
                    <p class="text-muted mb-0">Update information for your approved dataset</p>
                </div>
                <a href="{{ route('profile.edits') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Edits
                </a>
            </div>

            <!-- Alert Info -->
            <div class="alert alert-info d-flex align-items-start mb-4">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>
                    <strong>Important Notice:</strong>
                    <p class="mb-0">Changes to approved datasets will be reviewed by admins before going live. 
                    Your dataset status will change to "pending" after submission.</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('contribute.edit.metadata.update', $dataset) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Dataset Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Dataset Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $dataset->name) }}" 
                                   required
                                   placeholder="Enter dataset name">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use a clear, descriptive name for your dataset</div>
                        </div>

                        <!-- Abstract/Description -->
                        <div class="mb-4">
                            <label for="abstract" class="form-label fw-semibold">
                                Abstract <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('abstract') is-invalid @enderror" 
                                      id="abstract" 
                                      name="abstract" 
                                      rows="5" 
                                      required
                                      placeholder="Provide a concise summary of the dataset">{{ old('abstract', $dataset->abstract) }}</textarea>
                            @error('abstract')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Brief description (max 2000 characters)</div>
                        </div>

                        <!-- Detailed Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Detailed Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6"
                                      placeholder="Provide comprehensive details about the dataset">{{ old('description', $dataset->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Area & Data Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_area" class="form-label fw-semibold">
                                    Subject Area
                                </label>
                                <select class="form-select @error('subject_area') is-invalid @enderror" 
                                        id="subject_area" 
                                        name="subject_area">
                                    <option value="">Select subject area</option>
                                    <option value="Biology" {{ old('subject_area', $dataset->subject_area) == 'Biology' ? 'selected' : '' }}>Biology</option>
                                    <option value="Computer Science" {{ old('subject_area', $dataset->subject_area) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Medicine" {{ old('subject_area', $dataset->subject_area) == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                                    <option value="Engineering" {{ old('subject_area', $dataset->subject_area) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="Social Sciences" {{ old('subject_area', $dataset->subject_area) == 'Social Sciences' ? 'selected' : '' }}>Social Sciences</option>
                                    <option value="Business" {{ old('subject_area', $dataset->subject_area) == 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Other" {{ old('subject_area', $dataset->subject_area) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="data_type" class="form-label fw-semibold">
                                    Data Type
                                </label>
                                <select class="form-select @error('data_type') is-invalid @enderror" 
                                        id="data_type" 
                                        name="data_type">
                                    <option value="">Select data type</option>
                                    <option value="Multivariate" {{ old('data_type', $dataset->data_type) == 'Multivariate' ? 'selected' : '' }}>Multivariate</option>
                                    <option value="Univariate" {{ old('data_type', $dataset->data_type) == 'Univariate' ? 'selected' : '' }}>Univariate</option>
                                    <option value="Sequential" {{ old('data_type', $dataset->data_type) == 'Sequential' ? 'selected' : '' }}>Sequential</option>
                                    <option value="Time-Series" {{ old('data_type', $dataset->data_type) == 'Time-Series' ? 'selected' : '' }}>Time-Series</option>
                                    <option value="Text" {{ old('data_type', $dataset->data_type) == 'Text' ? 'selected' : '' }}>Text</option>
                                    <option value="Image" {{ old('data_type', $dataset->data_type) == 'Image' ? 'selected' : '' }}>Image</option>
                                    <option value="Tabular" {{ old('data_type', $dataset->data_type) == 'Tabular' ? 'selected' : '' }}>Tabular</option>
                                </select>
                                @error('data_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Task Type -->
                        <div class="mb-4">
                            <label for="task_type" class="form-label fw-semibold">
                                Associated Task
                            </label>
                            <select class="form-select @error('task_type') is-invalid @enderror" 
                                    id="task_type" 
                                    name="task_type">
                                <option value="">Select task type</option>
                                <option value="Classification" {{ old('task_type', $dataset->task_type) == 'Classification' ? 'selected' : '' }}>Classification</option>
                                <option value="Regression" {{ old('task_type', $dataset->task_type) == 'Regression' ? 'selected' : '' }}>Regression</option>
                                <option value="Clustering" {{ old('task_type', $dataset->task_type) == 'Clustering' ? 'selected' : '' }}>Clustering</option>
                                <option value="Causal Discovery" {{ old('task_type', $dataset->task_type) == 'Causal Discovery' ? 'selected' : '' }}>Causal Discovery</option>
                                <option value="Other" {{ old('task_type', $dataset->task_type) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('task_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Number of Instances & Features -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="num_instances" class="form-label fw-semibold">
                                    Number of Instances
                                </label>
                                <input type="number" 
                                       class="form-control @error('num_instances') is-invalid @enderror" 
                                       id="num_instances" 
                                       name="num_instances" 
                                       value="{{ old('num_instances', $dataset->num_instances) }}"
                                       min="0"
                                       placeholder="e.g., 150">
                                @error('num_instances')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="num_features" class="form-label fw-semibold">
                                    Number of Features
                                </label>
                                <input type="number" 
                                       class="form-control @error('num_features') is-invalid @enderror" 
                                       id="num_features" 
                                       name="num_features" 
                                       value="{{ old('num_features', $dataset->num_features) }}"
                                       min="0"
                                       placeholder="e.g., 4">
                                @error('num_features')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Status Badge -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current Status</label>
                            <div>
                                <span class="badge bg-{{ $dataset->status === 'approved' ? 'success' : 'info' }} fs-6">
                                    {{ ucfirst($dataset->status) }}
                                </span>
                                <small class="text-muted ms-2">
                                    Last updated: {{ $dataset->updated_at?->diffForHumans() ?? 'N/A' }}
                                </small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('profile.edits') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Submit for Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0077b6;
        box-shadow: 0 0 0 0.2rem rgba(0, 119, 182, 0.25);
    }
    
    .card {
        border-radius: 12px;
    }
    
    .form-label {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
@endpush