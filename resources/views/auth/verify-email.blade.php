@extends('layouts.auth')
@section('title', 'Verify Email - UCI Machine Learning Repository')

@section('content')
<div class="auth-card text-center">
    <div style="font-size: 3rem; margin-bottom: 1rem;">📧</div>
    <h2>Verify Your Email</h2>
    <p class="auth-subtitle">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
    </p>
    
    @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success auth-alert" role="alert">
            A new verification link has been sent to your email address.
        </div>
    @endif
    
    <form class="auth-form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-submit mb-3">
            RESEND VERIFICATION EMAIL
        </button>
    </form>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none" style="color: var(--uci-blue);">
            Log Out
        </button>
    </form>
</div>
@endsection