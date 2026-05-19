@extends('layouts.app')
@section('title', 'Contribute Dataset - UCI Machine Learning Repository')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5" style="color: #0077b6;">Contribute a Dataset</h1>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <p class="lead text-muted mb-4">
                        Share your dataset with the machine learning community
                    </p>
                    
                    <form>
                        <h5 class="mb-3">Dataset Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Dataset Name *</label>
                            <input type="text" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Task Type</label>
                                <select class="form-select">
                                    <option>Classification</option>
                                    <option>Regression</option>
              .                      <option>Clustering</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject Area</label>
                                <select class="form-select">
                                    <option>Biology</option>
                                    <option>Health & Medicine</option>
                                    <option>Computer Science</option>
                                    <option>Social Sciences</option>
                                    <option>Physical Sciences</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Upload Files</label>
                            <input type="file" class="form-control" multiple>
                            <div class="form-text">Accepted formats: CSV, ARFF, TXT, ZIP</div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Please ensure your dataset is properly documented and does not contain sensitive or private information.
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-contribute btn-lg">
                                <i class="bi bi-upload me-2"></i>Submit Dataset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection