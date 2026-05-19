@extends('layouts.auth')
@section('title', 'Reset Password - UCI Machine Learning Repository')

@section('content')
<div class="auth-card">
    <h2>Reset Password</h2>
    <p class="auth-subtitle">
        Enter your new password below.
    </p>
    
    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   autofocus
                   autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   required
                   autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   required
                   autocomplete="new-password">
        </div>
        
        <button type="submit" class="btn btn-submit">
            RESET PASSWORD
        </button>
    </form>
</div>
@endsection