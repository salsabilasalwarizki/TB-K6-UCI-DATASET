@extends('layouts.app')
@section('title', 'Dataset Donation - Variable Information - UCI Machine Learning Repository')

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
                <div class="progress-bar bg-warning" style="width: 85%"></div>
            </div>
            <span class="progress-text">Page 6 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.variable-info.store') }}" method="POST">
            @csrf

            <!-- Variable Information Section -->
            <div class="form-card">
                <h5 class="card-section-title">Variable Information</h5>
                
                <!-- Class Labels -->
                <div class="form-group mb-4">
                    <label for="class_labels" class="form-label">
                        Provide class labels for categorical data, if applicable.
                    </label>
                    <textarea 
                        class="form-control" 
                        id="class_labels" 
                        name="class_labels" 
                        rows="4"
                        placeholder="e.g., For target variable 'Species': Setosa, Versicolor, Virginica&#10;For target variable 'Outcome': Positive, Negative">{{ old('class_labels', $data['class_labels'] ?? '') }}</textarea>
                    <div class="form-hint mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        List all possible values for categorical variables, one variable per line
                    </div>
                </div>

                <hr class="my-4">

                <!-- Additional Variable Information -->
                <div class="form-group mb-4">
                    <label for="variable_info" class="form-label">
                        Provide additional information about the dataset's variables.
                    </label>
                    <textarea 
                        class="form-control" 
                        id="variable_info" 
                        name="variable_info" 
                        rows="6"
                        placeholder="e.g., &#10;- Age: Measured in years, range 18-80&#10;- Income: Annual income in USD&#10;- Temperature: Measured in Celsius&#10;- Missing values are represented as NA">{{ old('variable_info', $data['variable_info'] ?? '') }}</textarea>
                    <div class="form-hint mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Include units of measurement, value ranges, special codes, or any other relevant details
                    </div>
                </div>

                <!-- Variable List (if available from previous page) -->
                @if(isset($variables) && count($variables) > 0)
                <div class="mt-4">
                    <h6 class="mb-3">Variables from Dataset:</h6>
                    <div class="variables-list">
                        @foreach($variables as $index => $var)
                        <div class="variable-item">
                            <div class="variable-header">
                                <strong>{{ $var['name'] ?? 'Variable ' . ($index + 1) }}</strong>
                                <span class="badge bg-primary">{{ $var['role'] ?? 'Feature' }}</span>
                            </div>
                            <div class="variable-details">
                                <span class="badge bg-secondary me-2">{{ $var['type'] ?? 'Continuous' }}</span>
                                @if(!empty($var['description']))
                                    <small class="text-muted">{{ $var['description'] }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="{{ route('contribute.keywords') }}" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
                <button type="submit" class="btn-next">
                    NEXT </i>
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
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 0.75rem;
    }
    
    .form-control {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
        font-family: inherit;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    .form-hint {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .form-hint i {
        color: #0077b6;
    }
    
    .variables-list {
        margin-top: 1rem;
    }
    
    .variable-item {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .variable-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .variable-header strong {
        color: #0077b6;
        font-size: 1rem;
    }
    
    .variable-details {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 4px;
    }
    
    .badge.bg-primary {
        background-color: #0077b6 !important;
        color: white;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
        color: white;
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
// Auto-save to localStorage
const form = document.querySelector('form');
const inputs = form.querySelectorAll('textarea');

inputs.forEach(input => {
    input.addEventListener('input', function() {
        localStorage.setItem('variable_info_' + this.id, this.value);
    });
    
    // Restore from localStorage
    const saved = localStorage.getItem('variable_info_' + input.id);
    if (saved && !input.value) {
        input.value = saved;
    }
});

// Clear localStorage on successful submit
form.addEventListener('submit', function() {
    inputs.forEach(input => {
        localStorage.removeItem('variable_info_' + input.id);
    });
});

// Character count
inputs.forEach(input => {
    const counter = document.createElement('div');
    counter.className = 'text-muted small mt-1';
    counter.style.textAlign = 'right';
    input.parentNode.appendChild(counter);
    
    function updateCount() {
        const count = input.value.length;
        const max = input.maxLength || 5000;
        counter.textContent = `${count} / ${max} characters`;
        
        if (count > max * 0.9) {
            counter.classList.add('text-warning');
            counter.classList.remove('text-muted');
        } else {
            counter.classList.add('text-muted');
            counter.classList.remove('text-warning');
        }
    }
    
    input.addEventListener('input', updateCount);
    updateCount();
});
</script>
@endpush