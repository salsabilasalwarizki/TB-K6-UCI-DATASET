@extends('layouts.app')
@section('title', 'Dataset Donation - Variables - UCI Machine Learning Repository')

@section('content')
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header text-center mb-4">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">Page 6 of 7: Variable Information</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper mb-4">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: 85.5%"></div>
            </div>
            <span class="progress-text small text-muted">Page 6 / 7</span>
        </div>

        <!-- Form -->
        <form action="{{ route('contribute.variable-info.store') }}" method="POST" id="variablesForm">
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

            <!-- Variables Section -->
            <div class="form-card">
                <h5 class="card-section-title">Variable Information (Optional)</h5>
                <p class="text-muted small mb-4">
                    Describe the variables (columns) in your dataset. 
                    This helps users understand your data structure.
                </p>

                <!-- Variables Table -->
                <div class="table-responsive">
                    <table class="table table-sm variables-table" id="variablesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%">Name *</th>
                                <th style="width: 15%">Display Name</th>
                                <th style="width: 12%">Role *</th>
                                <th style="width: 12%">Type *</th>
                                <th style="width: 20%">Description</th>
                                <th style="width: 10%">Unit</th>
                                <th style="width: 8%">Min</th>
                                <th style="width: 8%">Max</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody id="variablesBody">
                            @php
                                $variablesData = old('variables', session('donation_wizard.variables', []));
                                if (empty($variablesData)) {
                                    $variablesData = [[
                                        'variable_name' => '', 'display_name' => '', 'role' => 'feature', 
                                        'variable_type' => 'Real', 'description' => '', 'unit' => '', 
                                        'min_value' => '', 'max_value' => '', 'categories' => ''
                                    ]];
                                }
                            @endphp
                            
                            @foreach($variablesData as $index => $var)
                            <tr data-index="{{ $index }}">
                                <td>
                                    <input type="text" class="form-control form-control-sm @error('variables.'.$index.'.variable_name') is-invalid @enderror" 
                                           name="variables[{{ $index }}][variable_name]" 
                                           value="{{ $var['variable_name'] ?? '' }}" 
                                           required maxlength="100" placeholder="column_name">
                                    @error('variables.'.$index.'.variable_name')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="variables[{{ $index }}][display_name]" 
                                           value="{{ $var['display_name'] ?? '' }}" 
                                           maxlength="100" placeholder="Human readable">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm @error('variables.'.$index.'.role') is-invalid @enderror" 
                                            name="variables[{{ $index }}][role]" required>
                                        <option value="feature" {{ ($var['role'] ?? '') == 'feature' ? 'selected' : '' }}>Feature</option>
                                        <option value="target" {{ ($var['role'] ?? '') == 'target' ? 'selected' : '' }}>Target</option>
                                        <option value="id" {{ ($var['role'] ?? '') == 'id' ? 'selected' : '' }}>ID</option>
                                        <option value="metadata" {{ ($var['role'] ?? '') == 'metadata' ? 'selected' : '' }}>Metadata</option>
                                        <option value="other" {{ ($var['role'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm @error('variables.'.$index.'.variable_type') is-invalid @enderror var-type-select" 
                                            name="variables[{{ $index }}][variable_type]" required onchange="toggleCategoriesField(this)">
                                        @foreach(['Categorical', 'Integer', 'Real', 'Text', 'Binary', 'Ordinal', 'Nominal', 'DateTime'] as $vtype)
                                        <option value="{{ $vtype }}" {{ ($var['variable_type'] ?? '') == $vtype ? 'selected' : '' }}>{{ $vtype }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="variables[{{ $index }}][description]" 
                                           value="{{ $var['description'] ?? '' }}" 
                                           maxlength="500" placeholder="Brief description">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="variables[{{ $index }}][unit]" 
                                           value="{{ $var['unit'] ?? '' }}" 
                                           maxlength="50" placeholder="e.g., kg, °C">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control form-control-sm" 
                                           name="variables[{{ $index }}][min_value]" 
                                           value="{{ $var['min_value'] ?? '' }}" placeholder="Min">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control form-control-sm" 
                                           name="variables[{{ $index }}][max_value]" 
                                           value="{{ $var['max_value'] ?? '' }}" placeholder="Max">
                                </td>
                                <td class="text-center">
                                    @if($index > 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariable({{ $index }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            <!-- Categories field for Categorical variables -->
                            @if(($var['variable_type'] ?? '') == 'Categorical')
                            <tr class="categories-row" data-index="{{ $index }}">
                                <td colspan="9">
                                    <small class="text-muted"><strong>Categories (comma-separated):</strong></small>
                                    <input type="text" class="form-control form-control-sm mt-1" 
                                           name="variables[{{ $index }}][categories]" 
                                           value="{{ $var['categories'] ?? '' }}" 
                                           placeholder="e.g., low, medium, high">
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Add Variable Button -->
                <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addVariable()">
                    <i class="bi bi-plus-circle me-1"></i>Add Variable
                </button>
            </div>

            <!-- Navigation -->
            <div class="form-navigation d-flex justify-content-between mt-4">
                <a href="{{ route('contribute.keywords') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Row Template -->
<template id="variableRowTemplate">
    <tr data-index="__INDEX__">
        <td>
            <input type="text" class="form-control form-control-sm" name="variables[__INDEX__][variable_name]" required maxlength="100" placeholder="column_name">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="variables[__INDEX__][display_name]" maxlength="100" placeholder="Human readable">
        </td>
        <td>
            <select class="form-select form-select-sm" name="variables[__INDEX__][role]" required>
                <option value="feature">Feature</option>
                <option value="target">Target</option>
                <option value="id">ID</option>
                <option value="metadata">Metadata</option>
                <option value="other">Other</option>
            </select>
        </td>
        <td>
            <select class="form-select form-select-sm var-type-select" name="variables[__INDEX__][variable_type]" required onchange="toggleCategoriesField(this)">
                <option value="Categorical">Categorical</option>
                <option value="Integer">Integer</option>
                <option value="Real">Real</option>
                <option value="Text">Text</option>
                <option value="Binary">Binary</option>
                <option value="Ordinal">Ordinal</option>
                <option value="Nominal">Nominal</option>
                <option value="DateTime">DateTime</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="variables[__INDEX__][description]" maxlength="500" placeholder="Brief description">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="variables[__INDEX__][unit]" maxlength="50" placeholder="e.g., kg, °C">
        </td>
        <td>
            <input type="number" step="any" class="form-control form-control-sm" name="variables[__INDEX__][min_value]" placeholder="Min">
        </td>
        <td>
            <input type="number" step="any" class="form-control form-control-sm" name="variables[__INDEX__][max_value]" placeholder="Max">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariable(__INDEX__)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
    <tr class="categories-row" data-index="__INDEX__" style="display: none;">
        <td colspan="9">
            <small class="text-muted"><strong>Categories (comma-separated):</strong></small>
            <input type="text" class="form-control form-control-sm mt-1" name="variables[__INDEX__][categories]" placeholder="e.g., low, medium, high">
        </td>
    </tr>
</template>
@endsection

@push('styles')
<style>
    .variables-table th { font-weight: 600; font-size: 0.8rem; }
    .variables-table td { padding: 0.4rem 0.3rem; vertical-align: middle; }
    .variables-table .form-control, .variables-table .form-select { font-size: 0.8rem; padding: 0.3rem 0.5rem; }
    .categories-row { background: #f8f9fa; }
    .categories-row td { padding-top: 0.2rem !important; padding-bottom: 0.5rem !important; }
</style>
@endpush

@push('scripts')
<script>
let varIndex = {{ count($variablesData) }};

function addVariable() {
    const template = document.getElementById('variableRowTemplate');
    const clone = template.content.cloneNode(true);
    const rows = clone.querySelectorAll('tr');
    
    rows.forEach(row => {
        const html = row.outerHTML.replace(/__INDEX__/g, varIndex);
        document.getElementById('variablesBody').insertAdjacentHTML('beforeend', html);
    });
    
    varIndex++;
}

function removeVariable(index) {
    document.querySelectorAll(`tr[data-index="${index}"]`).forEach(row => row.remove());
    // Re-index remaining rows
    let newIndex = 0;
    document.querySelectorAll('#variablesBody tr[data-index]').forEach(row => {
        const idx = row.dataset.index;
        if (!row.classList.contains('categories-row')) {
            row.setAttribute('data-index', newIndex);
            row.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });
            newIndex++;
        }
    });
    varIndex = newIndex;
}

function toggleCategoriesField(select) {
    const row = select.closest('tr');
    const index = row.dataset.index;
    const catRow = document.querySelector(`.categories-row[data-index="${index}"]`);
    
    if (catRow) {
        catRow.style.display = select.value === 'Categorical' ? '' : 'none';
    }
}

// Initialize categories visibility on load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.var-type-select').forEach(select => {
        toggleCategoriesField(select);
    });
});

// Form validation
document.getElementById('variablesForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#variablesBody tr[data-index]:not(.categories-row)');
    if (rows.length === 0) return true; // Optional section
    
    let valid = true;
    rows.forEach(row => {
        const name = row.querySelector('input[name*="[variable_name]"]');
        const role = row.querySelector('select[name*="[role]"]');
        const type = row.querySelector('select[name*="[variable_type]"]');
        
        if (!name.value.trim()) {
            valid = false;
            name.classList.add('is-invalid');
        } else {
            name.classList.remove('is-invalid');
        }
        if (!role.value) {
            valid = false;
            role.classList.add('is-invalid');
        } else {
            role.classList.remove('is-invalid');
        }
        if (!type.value) {
            valid = false;
            type.classList.add('is-invalid');
        } else {
            type.classList.remove('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields for each variable');
        return false;
    }
});
</script>
@endpush