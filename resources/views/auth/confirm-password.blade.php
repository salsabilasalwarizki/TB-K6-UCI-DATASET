@extends('layouts.auth')
@section('title', 'Confirm Password - UCI Machine Learning Repository')

@section('content')
<div class="auth-card">
    <h2>Confirm Password</h2>
    <p class="auth-subtitle">
        This is a secure area. Please confirm your password before continuing.
    </p>
    
    <form class="auth-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   required 
                   autofocus
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-submit">
            CONFIRM
        </button>
    </form>
</div>
@endsection/