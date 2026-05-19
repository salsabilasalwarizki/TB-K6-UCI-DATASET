@extends('layouts.auth')
@section('title', 'Forgot Password - UCI Machine Learning Repository')

@section('content')
<div class="auth-card">
    <h2>Forgot Password</h2>
    <p class="auth-subtitle">
        Enter your email address and we'll send you a link to reset your password.
    </p>
    
    @if(session('status'))
        <div class="alert alert-success auth-alert" role="alert">
            {{ session('status') }}
        </div>
    @endif
    
    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus
                   autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-submit mb-3">
            SEND RESET LINK
        </button>
        
        <div class="text-center">
            <a href="{{ route('login') }}" class="forgot-link">
                <i class="bi bi-arrow-left me-1"></i>Back to Sign In
            </a>
        </div>
    </form>
</div>
@endsection