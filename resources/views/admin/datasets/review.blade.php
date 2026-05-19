@extends('layouts.admin')
@section('title', 'Review: ' . $dataset->name)
@section('page-title', 'Review Dataset')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.datasets.index') }}">Datasets</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($dataset->name, 30) }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Dataset Info -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $dataset->name }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $dataset->description }}</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <strong>Subject Area:</strong> {{ $dataset->subjectArea->area_name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Task:</strong> {{ $dataset->task->task_name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mt-2">
                        <strong>Instances:</strong> {{ number_format($dataset->num_instances ?? 0) }}
                    </div>
                    <div class="col-md-6 mt-2">
                        <strong>Features:</strong> {{ number_format($dataset->num_features ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Files -->
        @if($dataset->files->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Files ({{ $dataset->files->count() }})</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($dataset->files as $file)
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>{{ $file->original_filename }}</span>
                        <span class="badge bg-secondary">{{ strtoupper($file->file_format) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Variables -->
        @if($dataset->variables->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Variables ({{ $dataset->variables->count() }})</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataset->variables->take(10) as $var)
                        <tr>
                            <td>{{ $var->variable_name }}</td>
                            <td><span class="badge bg-info">{{ $var->role }}</span></td>
                            <td>{{ $var->type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Panel -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Review Actions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-{{ $dataset->status === 'approved' ? 'success' : ($dataset->status === 'rejected' ? 'danger' : 'warning') }} fs-6">
                        {{ ucfirst($dataset->status ?? 'pending') }}
                    </span>
                </div>
                
                @if($dataset->status === 'pending')
                <form method="POST" action="{{ route('admin.datasets.approve', $dataset) }}" class="mb-2">
                    @csrf
                    <textarea name="admin_notes" class="form-control form-control-sm mb-2" 
                              placeholder="Admin notes (optional)"></textarea>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>Approve Dataset
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.datasets.reject', $dataset) }}">
                    @csrf
                    <textarea name="rejection_reason" class="form-control form-control-sm mb-2" 
                              placeholder="Reason for rejection (required)" required></textarea>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-x-circle me-2"></i>Reject Dataset
                    </button>
                </form>
                @else
                <div class="alert alert-info">
                    This dataset has already been {{ $dataset->status }}.
                </div>
                @endif
                
                <hr>
                
                <h6>Donor Info</h6>
                @foreach($dataset->creators as $creator)
                <div class="small">
                    <strong>{{ $creator->name }}</strong><br>
                    {{ $creator->email }}<br>
                    {{ $creator->affiliation }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection