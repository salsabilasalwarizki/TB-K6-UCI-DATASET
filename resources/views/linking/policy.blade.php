@extends('layouts.app')
@section('title', 'Linking Policy - UCI Machine Learning Repository')

@section('content')
<div class="linking-policy-container">
    <div class="container">
        <!-- Header -->
        <div class="policy-header">
            <h1 class="page-title">Linking Policy</h1>
        </div>

        <!-- Main Content -->
        <div class="policy-content">
            <h3 class="important-heading">
                Before linking an external dataset, please read the IMPORTANT information below:
            </h3>

            <ol class="policy-list">
                <li>The dataset must be widely known and high quality for it to be accepted.</li>
                <li>The download link should be visible on linked page.</li>
            </ol>

            <p class="contact-info">
                For questions, please email <a href="mailto:ml-repository@ics.uci.edu">ml-repository@ics.uci.edu</a>
            </p>

            <hr class="policy-divider">

            <!-- Consent Section -->
            <div class="consent-section">
                <h3 class="consent-heading">Consent</h3>
                
                <p class="consent-description">
                    By clicking yes below, I am agreeing to the inclusion of the external dataset in 
                    the UCI Machine Learning Repository.
                </p>

                @auth
                    <form action="{{ route('contribute.external.form') }}" method="GET" class="consent-form">
                        <input type="hidden" name="agreed" value="1">
                        <button type="submit" class="btn btn-agree">
                            I AGREE
                        </button>
                    </form>
                @else
                    <div class="login-required">
                        <p class="text-muted mb-3">You must be logged in to link an external dataset.</p>
                        <a href="{{ route('login') }}" class="btn btn-agree">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Continue
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .linking-policy-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 4rem 2rem 3rem;
        min-height: 60vh;
    }

    .page-title {
        color: #0077b6;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0 0 2rem 0;
        text-align: left;
    }

    .policy-content {
        background: white;
        padding: 0;
    }

    .important-heading {
        color: #0077b6;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .policy-list {
        padding-left: 1.5rem;
        margin: 1.5rem 0 2rem;
    }

    .policy-list li {
        margin-bottom: 1rem;
        line-height: 1.7;
        color: #444;
        font-size: 1rem;
    }

    .contact-info {
        margin-top: 2rem;
        margin-bottom: 2rem;
        font-size: 1rem;
        color: #444;
    }

    .contact-info a {
        color: #0077b6;
        text-decoration: underline;
        font-weight: 500;
    }

    .contact-info a:hover {
        color: #005f73;
    }

    .policy-divider {
        border: none;
        border-top: 1px solid #e0e0e0;
        margin: 2.5rem 0;
    }

    .consent-section {
        padding-top: 0.5rem;
    }

    .consent-heading {
        color: #0077b6;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .consent-description {
        color: #444;
        line-height: 1.7;
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    .btn-agree {
        background-color: #ffd60a;
        color: #000;
        font-weight: 700;
        padding: 1rem 4rem;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-block;
        text-decoration: none;
    }

    .btn-agree:hover {
        background-color: #ffc300;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 214, 10, 0.3);
        color: #000;
    }

    .login-required {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem 2rem;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .linking-policy-container {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .important-heading {
            font-size: 1.1rem;
        }

        .policy-list li {
            font-size: 0.95rem;
        }

        .btn-agree {
            width: 100%;
            padding: 1rem 2rem;
        }
    }
</style>
@endpush