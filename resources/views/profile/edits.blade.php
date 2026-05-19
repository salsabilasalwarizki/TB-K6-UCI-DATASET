@extends('layouts.app')
@section('title', 'Edits - UCI Machine Learning Repository')

@section('content')
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <a href="{{ route('profile') }}" class="nav-link">
            <i class="bi bi-person-fill"></i> Profile
        </a>
        <a href="{{ route('profile.datasets') }}" class="nav-link">
            <i class="bi bi-grid-3x3-gap-fill"></i> Datasets
        </a>
        <a href="{{ route('profile.edits') }}" class="nav-link active">
            <i class="bi bi-pencil-fill"></i> Edits
        </a>
    </aside>
    
    <!-- Content -->
    <div class="profile-content">
        <div class="section-header">
            <i class="bi bi-pencil-fill"></i>
            <h2>Your Edits</h2>
        </div>
        
        <div class="empty-state">
            <p class="text-muted">You haven't made any edits to datasets yet.</p>
            <a href="{{ route('datasets.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-search me-2"></i>Browse Datasets
            </a>
        </div>
    </div>
</div>
@endsection