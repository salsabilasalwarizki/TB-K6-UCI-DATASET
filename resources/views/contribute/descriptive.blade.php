@extends('layouts.app')
@section('title', 'Dataset Donation - Final Submission - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 7 of 7: Final Review & Submission</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 100%"></div>
            </div>
            <span class="progress-text small text-muted">Page 7 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.submit') }}" method="POST" enctype="multipart/form-data" id="finalForm">
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

            <!-- Descriptive Questions -->
            <div class="form-card">
                <h5 class="card-section-title">Descriptive Questions</h5>
                
                <div class="form-group mb-4">
                    <label for="purpose" class="form-label">What is the purpose of this dataset? <span class="required">*</span></label>
                    <textarea class="form-control @error('purpose') is-invalid @enderror" 
                              id="purpose" name="purpose" rows="3" required maxlength="2000"
                              placeholder="Describe the intended use cases...">{{ old('purpose', session('donation_wizard.descriptive.purpose', '')) }}</textarea>
                    @error('purpose')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint"><span id="purposeCount">0</span>/2000 characters</div>
                </div>

                <div class="form-group mb-4">
                    <label for="instances_represent" class="form-label">What do the instances represent? <span class="required">*</span></label>
                    <textarea class="form-control @error('instances_represent') is-invalid @enderror" 
                              id="instances_represent" name="instances_represent" rows="3" required maxlength="2000"
                              placeholder="e.g., patients, images, transactions, documents...">{{ old('instances_represent', session('donation_wizard.descriptive.instances_represent', '')) }}</textarea>
                    @error('instances_represent')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="funding" class="form-label">Funding Source (Optional)</label>
                        <input type="text" class="form-control @error('funding') is-invalid @enderror" 
                               id="funding" name="funding" 
                               value="{{ old('funding', session('donation_wizard.descriptive.funding', '')) }}" 
                               maxlength="500"
                               placeholder="e.g., NSF Grant #12345">
                        @error('funding')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="data_splits" class="form-label">Recommended Data Splits (Optional)</label>
                        <input type="text" class="form-control @error('data_splits') is-invalid @enderror" 
                               id="data_splits" name="data_splits" 
                               value="{{ old('data_splits', session('donation_wizard.descriptive.data_splits', '')) }}" 
                               maxlength="1000"
                               placeholder="e.g., 70% train, 15% validation, 15% test">
                        @error('data_splits')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="sensitive_data" class="form-label">Does this dataset contain sensitive data?</label>
                    <textarea class="form-control @error('sensitive_data') is-invalid @enderror" 
                              id="sensitive_data" name="sensitive_data" rows="2" maxlength="1000"
                              placeholder="Describe any PII, or write 'None' if not applicable">{{ old('sensitive_data', session('donation_wizard.descriptive.sensitive_data', 'None')) }}</textarea>
                    @error('sensitive_data')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint">e.g., personal health information, financial data, etc.</div>
                </div>

                <div class="form-group mb-4">
                    <label for="preprocessing" class="form-label">Was any preprocessing performed? (Optional)</label>
                    <textarea class="form-control @error('preprocessing') is-invalid @enderror" 
                              id="preprocessing" name="preprocessing" rows="3" maxlength="2000"
                              placeholder="Describe cleaning, normalization, feature engineering steps...">{{ old('preprocessing', session('donation_wizard.descriptive.preprocessing', '')) }}</textarea>
                    @error('preprocessing')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-4">
                    <label for="additional_info" class="form-label">Additional Information (Optional)</label>
                    <textarea class="form-control @error('additional_info') is-invalid @enderror" 
                              id="additional_info" name="additional_info" rows="4" maxlength="5000"
                              placeholder="Any other details that might help users...">{{ old('additional_info', session('donation_wizard.descriptive.additional_info', '')) }}</textarea>
                    @error('additional_info')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint"><span id="additionalCount">0</span>/5000 characters</div>
                </div>

                <div class="form-group mb-0">
                    <label for="citation_requests" class="form-label">Citation Request (Optional)</label>
                    <textarea class="form-control @error('citation_requests') is-invalid @enderror" 
                              id="citation_requests" name="citation_requests" rows="2" maxlength="1000"
                              placeholder="How would you like users to cite this dataset?">{{ old('citation_requests', session('donation_wizard.descriptive.citation_requests', '')) }}</textarea>
                    @error('citation_requests')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Summary Card -->
            <div class="form-card summary-card border-primary">
                <h5 class="card-section-title">Submission Summary</h5>
                @php $summary = $summary ?? session('donation_wizard.summary', []); @endphp
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <strong>Dataset:</strong> {{ $summary['name'] ?? 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Instances:</strong> {{ $summary['num_instances'] ?? 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Features:</strong> {{ $summary['num_features'] ?? 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Subject Area:</strong> {{ $summary['subject_area'] ?? 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Data Type:</strong> {{ $summary['characteristics'] ?? 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Files:</strong> {{ $summary['files_count'] ?? 0 }} file(s)
                    </div>
                    <div class="col-md-6">
                        <strong>Creators:</strong> {{ $summary['creators_count'] ?? 0 }} person(s)
                    </div>
                    <div class="col-md-6">
                        <strong>Keywords:</strong> {{ $summary['keywords_count'] ?? 0 }} keyword(s)
                    </div>
                </div>
            </div>

            <!-- License & Terms Agreement -->
            <div class="form-card bg-light">
                <h5 class="card-section-title">Agreements <span class="required">*</span></h5>
                
                <div class="form-check mb-3">
                    <input class="form-check-input @error('agree_license') is-invalid @enderror" 
                           type="checkbox" name="agree_license" value="1" id="agree_license" required>
                    <label class="form-check-label small" for="agree_license">
                        I agree to license this dataset under <strong>Creative Commons Attribution 4.0 International (CC BY 4.0)</strong>. 
                        <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" class="text-decoration-none">Learn more</a>
                    </label>
                    @error('agree_license')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-check mb-0">
                    <input class="form-check-input @error('agree_terms') is-invalid @enderror" 
                           type="checkbox" name="agree_terms" value="1" id="agree_terms" required>
                    <label class="form-check-label small" for="agree_terms">
                        I confirm that I have permission to share this dataset publicly and that it does not contain unredacted personally identifiable information.
                    </label>
                    @error('agree_terms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.variable-info') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="button" class="btn btn-success btn-lg" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>Submit Dataset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>By submitting, you confirm that:</p>
                <ul class="small">
                    <li>You have permission to share this dataset publicly</li>
                    <li>The dataset does not contain unredacted PII</li>
                    <li>You agree to the CC BY 4.0 license terms</li>
                </ul>
                <p class="text-muted small">Your dataset will be reviewed by our team before being published.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmit">
                    <i class="bi bi-check-circle me-1"></i>Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success display-4"></i>
                </div>
                <h5 class="fw-semibold mb-2">Submission Successful!</h5>
                <p class="text-muted small mb-4">
                    Your dataset is now pending review. You will receive an email notification once it's approved.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.datasets') }}" class="btn btn-primary">View Submitted Dataset</a>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('home') }}'">Return to Home</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 9999; display: none;">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
        <p class="fw-semibold">Submitting your dataset...</p>
        <small class="text-muted">Please do not close this page</small>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-card { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.25rem; }
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; }
    .required { color: #dc3545; }
    .form-label { font-weight: 600; font-size: 0.9rem; color: #333; margin-bottom: 0.4rem; }
    .form-control { border-radius: 6px; padding: 0.6rem 0.8rem; font-size: 0.9rem; }
    .form-control:focus { border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,0.15); }
    .form-hint { font-size: 0.8rem; color: #6c757d; margin-top: 0.3rem; }
    .summary-card { background: #f8f9fa; border-width: 2px !important; }
    .form-navigation { margin-top: 2rem; margin-bottom: 3rem; }
</style>
@endpush

@push('scripts')
<script>
// Character counters
['purpose', 'additional_info'].forEach(id => {
    const el = document.getElementById(id);
    const counter = document.getElementById(id + 'Count');
    if (el && counter) {
        counter.textContent = el.value.length;
        el.addEventListener('input', () => counter.textContent = el.value.length);
    }
});

// Confirmation modal
const submitBtn = document.getElementById('submitBtn');
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
const successModal = new bootstrap.Modal(document.getElementById('successModal'));
const confirmSubmit = document.getElementById('confirmSubmit');
const loadingOverlay = document.getElementById('loadingOverlay');
const form = document.getElementById('finalForm');

submitBtn.addEventListener('click', function() {
    // Validate required fields
    const required = form.querySelectorAll('[required]');
    let valid = true;
    required.forEach(field => {
        if (!field.value.trim() && field.tagName !== 'INPUT') {
            valid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    // Check checkboxes
    const agreeLicense = document.getElementById('agree_license');
    const agreeTerms = document.getElementById('agree_terms');
    if (!agreeLicense.checked || !agreeTerms.checked) {
        valid = false;
        if (!agreeLicense.checked) agreeLicense.classList.add('is-invalid');
        if (!agreeTerms.checked) agreeTerms.classList.add('is-invalid');
    }
    
    if (!valid) {
        alert('Please fill in all required fields and accept the agreements');
        return;
    }
    
    confirmModal.show();
});

confirmSubmit.addEventListener('click', function() {
    confirmModal.hide();
    loadingOverlay.style.display = 'flex';
    submitBtn.disabled = true;
    
    // Submit form
    form.submit();
});

// Show success modal if redirected with success session
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    loadingOverlay.style.display = 'none';
    setTimeout(() => successModal.show(), 300);
});
@endif

// Clear localStorage drafts after submit
form.addEventListener('submit', function() {
    Object.keys(localStorage).forEach(key => {
        if (key.startsWith('donation_')) localStorage.removeItem(key);
    });
});
</script>
@endpush