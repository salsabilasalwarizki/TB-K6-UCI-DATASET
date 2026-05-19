@extends('layouts.app')
@section('title', 'Dataset Donation - Keywords - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 5 of 7: Keywords</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 71%"></div>
            </div>
            <span class="progress-text small text-muted">Page 5 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.keywords.store') }}" method="POST" id="keywordsForm">
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

            <!-- Keywords Section -->
            <div class="form-card">
                <h5 class="card-section-title">Keywords (Optional)</h5>
                <p class="text-muted small mb-4">
                    Add keywords to help users discover your dataset. 
                    Select from popular suggestions or add your own.
                </p>

                <!-- Popular Keywords -->
                @php $popularKeywords = $popularKeywords ?? []; @endphp
                @if(count($popularKeywords) > 0)
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Popular Keywords</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($popularKeywords as $kw)
                        <button type="button" class="btn btn-sm btn-outline-secondary keyword-chip" 
                                onclick="toggleKeyword(this, '{{ addslashes($kw->keyword_name) }}')"
                                data-value="{{ addslashes($kw->keyword_name) }}">
                            {{ $kw->keyword_name }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Selected Keywords -->
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Selected Keywords</label>
                    <div id="selectedKeywords" class="d-flex flex-wrap gap-2 min-h-40">
                        @php
    $oldKeywords = old('keywords');
    $sessionKeywords = session('donation_wizard.keywords', []);
    
    // Handle jika old() return string JSON
    if (is_string($oldKeywords)) {
        $decoded = json_decode($oldKeywords, true);
        $selectedKeywords = is_array($decoded) ? $decoded : [];
    } elseif (is_array($oldKeywords)) {
        $selectedKeywords = $oldKeywords;
    } elseif (is_string($sessionKeywords)) {
        $decoded = json_decode($sessionKeywords, true);
        $selectedKeywords = is_array($decoded) ? $decoded : [];
    } else {
        $selectedKeywords = is_array($sessionKeywords) ? $sessionKeywords : [];
    }
@endphp
                        @foreach($selectedKeywords as $kw)
                        <span class="badge bg-primary d-inline-flex align-items-center gap-1 py-2 px-3">
                            {{ $kw }}
                            <button type="button" class="btn-close btn-close-white btn-sm" 
                                    onclick="removeSelectedKeyword('{{ addslashes($kw) }}')" 
                                    aria-label="Remove"></button>
                        </span>
                        @endforeach
                    </div>
                    <input type="hidden" name="keywords" id="keywordsInput" value="{{ json_encode($selectedKeywords) }}">
                </div>

                <!-- Add Custom Keywords -->
                <div>
                    <label for="new_keywords" class="form-label small">Add Custom Keywords</label>
                    <input type="text" class="form-control @error('new_keywords') is-invalid @enderror" 
                           id="new_keywords" name="new_keywords" 
                           value="{{ old('new_keywords', '') }}" 
                           maxlength="500"
                           placeholder="Type keywords separated by commas (e.g., machine learning, classification, iris)">
                    @error('new_keywords')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-hint">Press Enter or comma to add multiple keywords</div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.files') }}" class="btn btn-outline-secondary">
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

@push('styles')
<style>
    .keyword-chip { border-radius: 20px; padding: 0.3rem 0.8rem; font-size: 0.85rem; transition: all 0.2s; }
    .keyword-chip:hover, .keyword-chip.active { background: #0077b6; color: white; border-color: #0077b6; }
    .min-h-40 { min-height: 2.5rem; }
    .badge .btn-close { filter: invert(1); }
</style>
@endpush

@push('scripts')
<script>
let selectedKeywords = @json(old('keywords', session('donation_wizard.keywords', [])));

function toggleKeyword(btn, keyword) {
    const index = selectedKeywords.indexOf(keyword);
    if (index === -1) {
        // Add keyword
        selectedKeywords.push(keyword);
        btn.classList.add('active');
    } else {
        // Remove keyword
        selectedKeywords.splice(index, 1);
        btn.classList.remove('active');
    }
    updateSelectedDisplay();
    updateHiddenInput();
}

function removeSelectedKeyword(keyword) {
    const index = selectedKeywords.indexOf(keyword);
    if (index !== -1) {
        selectedKeywords.splice(index, 1);
        // Also update chip button state
        document.querySelectorAll('.keyword-chip').forEach(btn => {
            if (btn.dataset.value === keyword) btn.classList.remove('active');
        });
    }
    updateSelectedDisplay();
    updateHiddenInput();
}

function updateSelectedDisplay() {
    const container = document.getElementById('selectedKeywords');
    container.innerHTML = '';
    selectedKeywords.forEach(kw => {
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary d-inline-flex align-items-center gap-1 py-2 px-3';
        badge.innerHTML = `${kw} <button type="button" class="btn-close btn-close-white btn-sm" onclick="removeSelectedKeyword('${kw.replace(/'/g, "\\'")}')" aria-label="Remove"></button>`;
        container.appendChild(badge);
    });
}

function updateHiddenInput() {
    document.getElementById('keywordsInput').value = JSON.stringify(selectedKeywords);
}

// Handle comma/Enter for custom keywords
document.getElementById('new_keywords').addEventListener('keydown', function(e) {
    if (e.key === ',' || e.key === 'Enter') {
        e.preventDefault();
        const input = this.value.trim();
        if (input) {
            const newKws = input.split(',').map(k => k.trim()).filter(k => k && k.length >= 2);
            newKws.forEach(kw => {
                if (!selectedKeywords.includes(kw)) {
                    selectedKeywords.push(kw);
                }
            });
            updateSelectedDisplay();
            updateHiddenInput();
            this.value = '';
        }
    }
});

// Initialize chip states on load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.keyword-chip').forEach(btn => {
        if (selectedKeywords.includes(btn.dataset.value)) {
            btn.classList.add('active');
        }
    });
});
</script>
@endpush