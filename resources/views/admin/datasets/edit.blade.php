@extends('layouts.admin')
@section('title', 'Edit Dataset')
@section('page-title', 'Edit Dataset')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Dataset
                    </h2>
                    <p class="text-muted mb-0">Update dataset information</p>
                </div>
                <a href="{{ route('admin.datasets.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Alert Info -->
            <div class="alert alert-info d-flex align-items-start mb-4">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>
                    <strong>Important Notice:</strong>
                    <p class="mb-0">Changes to this dataset will be saved immediately. Make sure all information is accurate before saving.</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.datasets.update', $dataset) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h5>

                        <!-- Name -->
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
                        </div>

                        <!-- Display Name -->
                        <div class="mb-4">
                            <label for="display_name" class="form-label fw-semibold">
                                Display Name
                            </label>
                            <input type="text" 
                                   class="form-control @error('display_name') is-invalid @enderror" 
                                   id="display_name" 
                                   name="display_name" 
                                   value="{{ old('display_name', $dataset->display_name) }}"
                                   placeholder="Display name (optional)">
                            @error('display_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      placeholder="Provide a comprehensive description">{{ old('description', $dataset->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Abstract -->
                        <div class="mb-4">
                            <label for="abstract" class="form-label fw-semibold">
                                Abstract
                            </label>
                            <textarea class="form-control @error('abstract') is-invalid @enderror" 
                                      id="abstract" 
                                      name="abstract" 
                                      rows="3"
                                      placeholder="Brief abstract">{{ old('abstract', $dataset->abstract) }}</textarea>
                            @error('abstract')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Area & Data Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_area" class="form-label fw-semibold">
                                    Subject Area
                                </label>
                                <input type="text" 
                                       class="form-select @error('subject_area') is-invalid @enderror" 
                                       id="subject_area" 
                                       name="subject_area" 
                                       value="{{ old('subject_area', $dataset->subject_area) }}"
                                       placeholder="e.g., Computer Science">
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

                        <!-- Task Type & Domain -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="task_type" class="form-label fw-semibold">
                                    Task Type
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
                            <div class="col-md-6">
                                <label for="domain" class="form-label fw-semibold">
                                    Domain
                                </label>
                                <input type="text" 
                                       class="form-control @error('domain') is-invalid @enderror" 
                                       id="domain" 
                                       name="domain" 
                                       value="{{ old('domain', $dataset->domain) }}"
                                       placeholder="e.g., Machine Learning">
                                @error('domain')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dataset Statistics -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-bar-chart me-2"></i>Dataset Statistics
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <label for="num_classes" class="form-label fw-semibold">
                                    Number of Classes
                                </label>
                                <input type="number" 
                                       class="form-control @error('num_classes') is-invalid @enderror" 
                                       id="num_classes" 
                                       name="num_classes" 
                                       value="{{ old('num_classes', $dataset->num_classes) }}"
                                       min="0"
                                       placeholder="e.g., 3">
                                @error('num_classes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status & Visibility -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-shield-check me-2"></i>Status & Visibility
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="pending" {{ old('status', $dataset->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $dataset->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $dataset->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="available" {{ old('status', $dataset->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="deprecated" {{ old('status', $dataset->status) == 'deprecated' ? 'selected' : '' }}>Deprecated</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="has_missing_values" class="form-label fw-semibold">
                                    Has Missing Values
                                </label>
                                <select class="form-select @error('has_missing_values') is-invalid @enderror" 
                                        id="has_missing_values" 
                                        name="has_missing_values">
                                    <option value="0" {{ old('has_missing_values', $dataset->has_missing_values) == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('has_missing_values', $dataset->has_missing_values) == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('has_missing_values')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="mb-4">
                            <label for="admin_notes" class="form-label fw-semibold">
                                Admin Notes
                            </label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                      id="admin_notes" 
                                      name="admin_notes" 
                                      rows="3"
                                      placeholder="Internal notes for administrators">{{ old('admin_notes', $dataset->admin_notes) }}</textarea>
                            @error('admin_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">These notes are only visible to administrators</div>
                        </div>

                        <!-- URLs -->
                        <h5 class="mb-3 text-primary border-bottom pb-2 mt-5">
                            <i class="bi bi-link-45deg me-2"></i>External Links
                        </h5>

                        <div class="mb-4">
                            <label for="dataset_url" class="form-label fw-semibold">
                                Dataset URL
                            </label>
                            <input type="url" 
                                   class="form-control @error('dataset_url') is-invalid @enderror" 
                                   id="dataset_url" 
                                   name="dataset_url" 
                                   value="{{ old('dataset_url', $dataset->dataset_url) }}"
                                   placeholder="https://...">
                            @error('dataset_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="detail_url" class="form-label fw-semibold">
                                Detail URL
                            </label>
                            <input type="url" 
                                   class="form-control @error('detail_url') is-invalid @enderror" 
                                   id="detail_url" 
                                   name="detail_url" 
                                   value="{{ old('detail_url', $dataset->detail_url) }}"
                                   placeholder="https://...">
                            @error('detail_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('admin.datasets.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Update Dataset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Dataset Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Created</small>
                            <span class="fw-semibold">{{ $dataset->created_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Last Updated</small>
                            <span class="fw-semibold">{{ $dataset->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Dataset ID</small>
                            <span class="fw-semibold">{{ $dataset->dataset_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label {
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        padding: 0.6rem 0.8rem;
        font-size: 0.95rem;
    }
    
    .card {
        border-radius: 12px;
    }
    
    .card-body {
        border-radius: 12px;
    }
</style>
@endpush