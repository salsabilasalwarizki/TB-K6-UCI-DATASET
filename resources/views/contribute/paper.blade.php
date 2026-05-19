@extends('layouts.app')
@section('title', 'Dataset Donation - Paper - UCI Machine Learning Repository')

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
                <div class="progress-bar bg-warning" style="width: 28.5%"></div>
            </div>
            <span class="progress-text">Page 2 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.paper.store') }}" method="POST">
            @csrf
           
            @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <strong>Form has errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <!-- Introductory Paper Section -->
            <div class="form-card">
                <h5 class="card-section-title">Introductory Paper</h5>
                
                <p class="text-muted mb-4">
                    Optional: Provide a paper ID and its type to auto-fill the fields
                </p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="paper_id_type" class="form-label">Paper ID Type</label>
                        <select class="form-control" id="paper_id_type" name="paper_id_type">
                            <option value="None" {{ old('paper_id_type', $oldPaper['paper_id_type'] ?? 'None') == 'None' ? 'selected' : '' }}>None</option>
                            <option value="DOI" {{ old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'DOI' ? 'selected' : '' }}>DOI</option>
                            <option value="arXiv" {{ old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'arXiv' ? 'selected' : '' }}>arXiv</option>
                            <option value="PubMed" {{ old('paper_id_type', $oldPaper['paper_id_type'] ?? '') == 'PubMed' ? 'selected' : '' }}>PubMed</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="paper_id" class="form-label">Paper ID</label>
                        <input type="text" 
                               class="form-control" 
                               id="paper_id" 
                               name="paper_id" 
                               value="{{ old('paper_id', $oldPaper['paper_id'] ?? '') }}"
                               placeholder="e.g., 10.1000/xyz123 or arXiv:2101.12345">
                    </div>
                </div>

                <button type="button" class="btn-find mb-4" id="btnFindPaper">
                    FIND <i class="bi bi-search ms-2"></i>
                </button>

                <hr class="my-4">

                <div class="form-group">
                    <label for="title" class="form-label">Title <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $oldPaper['title'] ?? '') }}" 
                           required
                           placeholder="Enter paper title">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="authors" class="form-label">Authors <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control @error('authors') is-invalid @enderror" 
                           id="authors" 
                           name="authors" 
                           value="{{ old('authors', $oldPaper['authors'] ?? '') }}" 
                           required
                           placeholder="e.g., J. Smith, A. Johnson">
                    @error('authors')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="venue" class="form-label">Venue <span class="required">*</span></label>
                    <input type="text" 
                           class="form-control @error('venue') is-invalid @enderror" 
                           id="venue" 
                           name="venue" 
                           value="{{ old('venue', $oldPaper['venue'] ?? '') }}" 
                           required
                           placeholder="e.g., NeurIPS 2024, Journal of ML Research">
                    @error('venue')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="year" class="form-label">Year <span class="required">*</span></label>
                    <input type="number" 
                           class="form-control @error('year') is-invalid @enderror" 
                           id="year" 
                           name="year" 
                           value="{{ old('year', $oldPaper['year'] ?? date('Y')) }}" 
                           required
                           min="1900"
                           max="{{ date('Y') }}"
                           placeholder="e.g., 2024">
                    @error('year')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" 
                           class="form-control @error('url') is-invalid @enderror" 
                           id="url" 
                           name="url" 
                           value="{{ old('url', $oldPaper['url'] ?? '') }}"
                           placeholder="https://example.com/paper.pdf">
                    @error('url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="{{ route('contribute.metadata') }}" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
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
        padding: 2rem;
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
    
    .btn-find {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
        padding: 0.5rem 2rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .btn-find:hover:not(:disabled) {
        background-color: #0077b6;
        color: white;
        border-color: #0077b6;
    }
    
    .btn-find:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-back {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-back:hover {
        background-color: #dc3545;
        color: white;
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
    
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem 1rem;
        }
        
        .form-card {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Paper ID Type change handler - enable/disable FIND button
// Debug: Cek apakah form submit
document.querySelector('form').addEventListener('submit', function(e) {
    console.log('Form sedang di-submit!');
    alert('Form akan di-submit...');
});
document.getElementById('paper_id_type').addEventListener('change', function() {
    const btnFind = document.getElementById('btnFindPaper');
    const paperIdInput = document.getElementById('paper_id');
    
    if (this.value === 'None' || !paperIdInput.value.trim()) {
        btnFind.disabled = true;
        btnFind.style.opacity = '0.5';
    } else {
        btnFind.disabled = false;
        btnFind.style.opacity = '1';
    }
});

document.getElementById('paper_id').addEventListener('input', function() {
    const btnFind = document.getElementById('btnFindPaper');
    const paperType = document.getElementById('paper_id_type').value;
    
    if (paperType === 'None' || !this.value.trim()) {
        btnFind.disabled = true;
        btnFind.style.opacity = '0.5';
    } else {
        btnFind.disabled = false;
        btnFind.style.opacity = '1';
    }
});

// Initial state
(function() {
    const btnFind = document.getElementById('btnFindPaper');
    const paperType = document.getElementById('paper_id_type').value;
    const paperId = document.getElementById('paper_id').value;
    
    if (paperType === 'None' || !paperId.trim()) {
        btnFind.disabled = true;
        btnFind.style.opacity = '0.5';
    }
})();

// FIND button - Auto-fill functionality
document.getElementById('btnFindPaper').addEventListener('click', function() {
    const paperType = document.getElementById('paper_id_type').value;
    const paperId = document.getElementById('paper_id').value.trim();
    
    if (!paperId || paperType === 'None') {
        alert('Please enter a valid Paper ID');
        return;
    }
    
    // Show loading state
    const btn = this;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Searching...';
    
    // Here you would make an API call to Crossref/arXiv/PubMed
    // For now, just show alert
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        alert('Auto-fill feature will be implemented with Crossref/arXiv API integration.');
    }, 1500);
});
</script>
@endpush