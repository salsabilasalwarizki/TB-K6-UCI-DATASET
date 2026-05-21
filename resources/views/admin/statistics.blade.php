@extends('layouts.admin')
@section('title', 'Statistics')
@section('page-title', 'Platform Statistics')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Statistics Dashboard</h2>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $stats['total_datasets'] }}</h3>
                    <p class="mb-0">Total Datasets</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $stats['total_users'] }}</h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $stats['total_papers'] }}</h3>
                    <p class="mb-0">Total Papers</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection