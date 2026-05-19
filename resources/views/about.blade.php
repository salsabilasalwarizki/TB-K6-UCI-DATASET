@extends('layouts.app')
@section('title', 'About Us - UCI Machine Learning Repository')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5" style="color: #0077b6;">About Us</h1>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="mb-3">UC Irvine Machine Learning Repository</h3>
                    <p class="text-muted">
                        The UCI Machine Learning Repository is a collection of databases, domain theories, and data generators 
                        that are used by the machine learning community for the empirical analysis of machine learning algorithms.
                    </p>
                    
                    <h5 class="mt-4 mb-3">Our Mission</h5>
                    <p>
                        To provide a centralized repository of datasets that can be used by researchers, educators, and students 
                        worldwide for machine learning research and education.
                    </p>
                    
                    <h5 class="mt-4 mb-3">Statistics</h5>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h2 class="text-primary">{{ number_format($totalDatasets ?? 689) }}</h2>
                            <p class="text-muted">Datasets</p>
                        </div>
                        <div class="col-md-4">
                            <h2 class="text-primary">Millions</h2>
                            <p class="text-muted">Downloads</p>
                        </div>
                        <div class="col-md-4">
                            <h2 class="text-primary">30+</h2>
                            <p class="text-muted">Years of Service</p>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Contact</h5>
                    <p>
                        For questions or contributions, please contact us at:<br>
                        <a href="mailto:ml-repository@ics.uci.edu">ml-repository@ics.uci.edu</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection