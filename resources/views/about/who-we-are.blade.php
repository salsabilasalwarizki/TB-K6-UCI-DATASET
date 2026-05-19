@extends('layouts.app')
@section('title', 'Who We Are - UCI Machine Learning Repository')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-blue-600 mb-8">Who We Are</h1>
        
        <!-- Content Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm">
            
            <h2 class="text-xl font-semibold text-blue-600 mb-4">About the UCI Machine Learning Repository</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                The UCI Machine Learning Repository is a collection of databases, domain theories, and data generators 
                that are used by the machine learning community for the empirical analysis of machine learning algorithms.
            </p>
            
            <h3 class="text-lg font-semibold text-blue-600 mt-8 mb-3">Our Mission</h3>
            <p class="text-gray-600 leading-relaxed">
                To provide a centralized repository of datasets that can be used by researchers, educators, and students 
                worldwide for machine learning research and education.
            </p>
            
            <h3 class="text-lg font-semibold text-blue-600 mt-8 mb-3">History</h3>
            <p class="text-gray-600 leading-relaxed">
                The repository was established in 1987 by David Aha and has since become one of the most widely used 
                resources for machine learning research, serving millions of users around the world.
            </p>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-10 pt-6 border-t border-gray-100">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">689+</div>
                    <div class="text-sm text-gray-500">Datasets</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">30+</div>
                    <div class="text-sm text-gray-500">Years</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">Millions</div>
                    <div class="text-sm text-gray-500">Users</div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection