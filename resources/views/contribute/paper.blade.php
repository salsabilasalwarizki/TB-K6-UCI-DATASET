@extends('layouts.app')
@section('title', 'Dataset Donation - Paper - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 2 of 7: Introductory Paper</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 28.5%"></div>
            </div>
            <span class="progress-text small text-muted">Page 2 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.paper.store') }}" method="POST" id="paperForm">
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

            <!-- Paper Section -->
            <div class="form-card">
                <h5 class="card-section-title">Introductory Paper (Optional)</h5>
                <p class="text-muted small mb-4">
                    Provide details about the paper that introduced this dataset. 
                    <br><strong>Tip:</strong> Enter DOI/arXiv ID and click "Find" to auto-fill.
                </p>

                <!-- Auto-fill Section -->
                <div class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-md-4">
                        <label for="paper_id_type" class="form-label small">ID Type</label>
                        <select class="form-select form-select-sm" id="paper_id_type" name="paper_id_type">
                            <option value="None" {{ old('paper_id_type', session('donation_wizard.paper.paper_id_type', 'None')) == 'None' ? 'selected' : '' }}>None</option>
                            <option value="DOI" {{ old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'DOI' ? 'selected' : '' }}>DOI</option>
                            <option value="arXiv" {{ old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'arXiv' ? 'selected' : '' }}>arXiv</option>
                            <option value="PubMed" {{ old('paper_id_type', session('donation_wizard.paper.paper_id_type', '')) == 'PubMed' ? 'selected' : '' }}>PubMed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="paper_id" class="form-label small">Paper ID</label>
                        <input type="text" class="form-control form-control-sm" id="paper_id" name="paper_id" 
                               value="{{ old('paper_id', session('donation_wizard.paper.paper_id', '')) }}"
                               placeholder="e.g., 10.1000/xyz123 or arXiv:2101.12345">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" id="btnFindPaper" disabled>
                            <i class="bi bi-search me-1"></i>Find
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Manual Entry Fields -->
                <div class="form-group mb-3">
                    <label for="title" class="form-label">Paper Title <span class="required">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" 
                           value="{{ old('title', session('donation_wizard.paper.title', '')) }}" 
                           required maxlength="500"
                           placeholder="Enter the full paper title">
                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label for="authors" class="form-label">Authors <span class="required">*</span></label>
                    <input type="text" class="form-control @error('authors') is-invalid @enderror" 
                           id="authors" name="authors" 
                           value="{{ old('authors', session('donation_wizard.paper.authors', '')) }}" 
                           required maxlength="500"
                           placeholder="e.g., J. Smith, A. Johnson, K. Lee">
                    @error('authors')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint">Separate multiple authors with commas.</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="venue" class="form-label">Venue/Journal <span class="required">*</span></label>
                        <input type="text" class="form-control @error('venue') is-invalid @enderror" 
                               id="venue" name="venue" 
                               value="{{ old('venue', session('donation_wizard.paper.venue', '')) }}" 
                               required maxlength="255"
                               placeholder="e.g., NeurIPS 2024, JMLR">
                        @error('venue')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year <span class="required">*</span></label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" 
                               id="year" name="year" 
                               value="{{ old('year', session('donation_wizard.paper.year', date('Y'))) }}" 
                               required min="1900" max="{{ date('Y') }}"
                               placeholder="e.g., 2024">
                        @error('year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="url" class="form-label">URL (Optional)</label>
                        <input type="url" class="form-control @error('url') is-invalid @enderror" 
                               id="url" name="url" 
                               value="{{ old('url', session('donation_wizard.paper.url', '')) }}" 
                               maxlength="500"
                               placeholder="https://...">
                        @error('url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.metadata') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Enable/disable Find button based on input
const paperType = document.getElementById('paper_id_type');
const paperId = document.getElementById('paper_id');
const btnFind = document.getElementById('btnFindPaper');

function updateFindButton() {
    if (paperType.value !== 'None' && paperId.value.trim()) {
        btnFind.disabled = false;
        btnFind.classList.remove('btn-outline-secondary');
        btnFind.classList.add('btn-outline-primary');
    } else {
        btnFind.disabled = true;
        btnFind.classList.remove('btn-outline-primary');
        btnFind.classList.add('btn-outline-secondary');
    }
}

paperType.addEventListener('change', updateFindButton);
paperId.addEventListener('input', updateFindButton);
updateFindButton();

// Find paper (mock implementation - replace with actual API call)
btnFind.addEventListener('click', function() {
    const type = paperType.value;
    const id = paperId.value.trim();
    
    if (!id) {
        alert('Please enter a Paper ID');
        return;
    }
    
    // Show loading
    const originalText = btnFind.innerHTML;
    btnFind.disabled = true;
    btnFind.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Searching...';
    
    // Mock API call (replace with actual fetch to Crossref/arXiv)
    setTimeout(() => {
        alert('Auto-fill feature requires API integration.\n\nFor now, please enter paper details manually.');
        btnFind.disabled = false;
        btnFind.innerHTML = originalText;
    }, 1500);
});

// Form validation
document.getElementById('paperForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const authors = document.getElementById('authors').value.trim();
    const venue = document.getElementById('venue').value.trim();
    const year = document.getElementById('year').value;
    
    if (!title || !authors || !venue || !year) {
        e.preventDefault();
        alert('Please fill in all required fields marked with *');
        return false;
    }
});
</script>
@endpush