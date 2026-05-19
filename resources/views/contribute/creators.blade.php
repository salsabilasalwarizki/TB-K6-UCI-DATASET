@extends('layouts.app')
@section('title', 'Dataset Donation - Creators - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 3 of 7: Dataset Creators</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 42.5%"></div>
            </div>
            <span class="progress-text small text-muted">Page 3 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.creators.store') }}" method="POST" id="creatorsForm">
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

            <!-- Creators Section -->
            <div class="form-card">
                <h5 class="card-section-title">Dataset Creators (Optional)</h5>
                <p class="text-muted small mb-4">
                    Add people who created or contributed to this dataset. 
                    At least one creator is recommended for proper attribution.
                </p>

                <!-- Creators Container -->
                <div id="creatorsContainer">
                    @php
                        $creatorsData = old('creators', session('donation_wizard.creators', []));
                        if (empty($creatorsData)) {
                            $creatorsData = [['name' => '', 'email' => '', 'affiliation' => '', 'orcid' => '', 'contribution_role' => 'Creator']];
                        }
                    @endphp
                    
                    @foreach($creatorsData as $index => $creator)
                    <div class="creator-item p-3 mb-3 border rounded" data-index="{{ $index }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-primary">Creator {{ $index + 1 }}</h6>
                            @if($index > 0)
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCreator({{ $index }})">
                                <i class="bi bi-trash me-1"></i>Remove
                            </button>
                            @endif
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Full Name <span class="required">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('creators.'.$index.'.name') is-invalid @enderror" 
                                       name="creators[{{ $index }}][name]" 
                                       value="{{ $creator['name'] ?? '' }}" 
                                       required maxlength="255"
                                       placeholder="e.g., Jane Doe">
                                @error('creators.'.$index.'.name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Contribution Role <span class="required">*</span></label>
                                <select class="form-select form-select-sm @error('creators.'.$index.'.contribution_role') is-invalid @enderror" 
                                        name="creators[{{ $index }}][contribution_role]" required>
                                    <option value="Creator" {{ ($creator['contribution_role'] ?? '') == 'Creator' ? 'selected' : '' }}>Creator</option>
                                    <option value="Donor" {{ ($creator['contribution_role'] ?? '') == 'Donor' ? 'selected' : '' }}>Donor</option>
                                    <option value="Analyst" {{ ($creator['contribution_role'] ?? '') == 'Analyst' ? 'selected' : '' }}>Analyst</option>
                                    <option value="Data Collector" {{ ($creator['contribution_role'] ?? '') == 'Data Collector' ? 'selected' : '' }}>Data Collector</option>
                                    <option value="Other" {{ ($creator['contribution_role'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('creators.'.$index.'.contribution_role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Affiliation</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="creators[{{ $index }}][affiliation]" 
                                       value="{{ $creator['affiliation'] ?? '' }}" 
                                       maxlength="255"
                                       placeholder="e.g., University of California">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Email</label>
                                <input type="email" class="form-control form-control-sm" 
                                       name="creators[{{ $index }}][email]" 
                                       value="{{ $creator['email'] ?? '' }}" 
                                       maxlength="255"
                                       placeholder="creator@example.com">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small">ORCID</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="creators[{{ $index }}][orcid]" 
                                       value="{{ $creator['orcid'] ?? '' }}" 
                                       pattern="^\d{4}-\d{4}-\d{4}-\d{4}$"
                                       maxlength="19"
                                       placeholder="0000-0000-0000-0000">
                                <div class="form-hint">Format: 0000-0000-0000-0000</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Add Creator Button -->
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCreator()">
                    <i class="bi bi-plus-circle me-1"></i>Add Another Creator
                </button>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.paper') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Template for New Creator -->
<template id="creatorTemplate">
    <div class="creator-item p-3 mb-3 border rounded" data-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-primary">Creator __INDEX__</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCreator(__INDEX__)">
                <i class="bi bi-trash me-1"></i>Remove
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small">Full Name <span class="required">*</span></label>
                <input type="text" class="form-control form-control-sm" name="creators[__INDEX__][name]" required maxlength="255" placeholder="e.g., Jane Doe">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Contribution Role <span class="required">*</span></label>
                <select class="form-select form-select-sm" name="creators[__INDEX__][contribution_role]" required>
                    <option value="Creator">Creator</option>
                    <option value="Donor">Donor</option>
                    <option value="Analyst">Analyst</option>
                    <option value="Data Collector">Data Collector</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Affiliation</label>
                <input type="text" class="form-control form-control-sm" name="creators[__INDEX__][affiliation]" maxlength="255" placeholder="e.g., University of California">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Email</label>
                <input type="email" class="form-control form-control-sm" name="creators[__INDEX__][email]" maxlength="255" placeholder="creator@example.com">
            </div>
            <div class="col-md-12">
                <label class="form-label small">ORCID</label>
                <input type="text" class="form-control form-control-sm" name="creators[__INDEX__][orcid]" pattern="^\d{4}-\d{4}-\d{4}-\d{4}$" maxlength="19" placeholder="0000-0000-0000-0000">
                <div class="form-hint">Format: 0000-0000-0000-0000</div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let creatorIndex = {{ count($creatorsData) }};

function addCreator() {
    const template = document.getElementById('creatorTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.querySelector('.creator-item').outerHTML.replace(/__INDEX__/g, creatorIndex);
    
    document.getElementById('creatorsContainer').insertAdjacentHTML('beforeend', html);
    creatorIndex++;
}

function removeCreator(index) {
    const item = document.querySelector(`.creator-item[data-index="${index}"]`);
    if (item) {
        item.remove();
        // Re-index remaining items to maintain sequential indices
        document.querySelectorAll('.creator-item').forEach((el, i) => {
            el.setAttribute('data-index', i);
            el.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
            });
        });
        creatorIndex = document.querySelectorAll('.creator-item').length;
    }
}

// Form validation
document.getElementById('creatorsForm').addEventListener('submit', function(e) {
    const creators = document.querySelectorAll('.creator-item');
    let valid = true;
    
    creators.forEach(creator => {
        const nameInput = creator.querySelector('input[name*="[name]"]');
        const roleSelect = creator.querySelector('select[name*="[contribution_role]"]');
        
        if (!nameInput.value.trim()) {
            valid = false;
            nameInput.classList.add('is-invalid');
        } else {
            nameInput.classList.remove('is-invalid');
        }
        
        if (!roleSelect.value) {
            valid = false;
            roleSelect.classList.add('is-invalid');
        } else {
            roleSelect.classList.remove('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields for each creator');
        return false;
    }
});
</script>
@endpush