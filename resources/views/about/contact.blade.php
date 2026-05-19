@extends('layouts.app')
@section('title', 'Contact Information - UCI Machine Learning Repository')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold text-blue-600 mb-8">Contact Information</h1>
        
        <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm">
            
            <h2 class="text-xl font-semibold text-blue-600 mb-6">Get in Touch</h2>
            
            <!-- Contact Items -->
            <div class="space-y-6">
                
                <!-- Email -->
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                        <p class="text-gray-600">
                            For general inquiries: 
                            <a href="mailto:ml-repository@ics.uci.edu" class="text-blue-600 hover:underline">
                                ml-repository@ics.uci.edu
                            </a>
                        </p>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Address</h3>
                        <p class="text-gray-600">
                            UCI Machine Learning Repository<br>
                            Donald Bren School of Information and Computer Sciences<br>
                            University of California, Irvine<br>
                            Irvine, CA 92697-3425
                        </p>
                    </div>
                </div>
                
                <!-- Bug Reports -->
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Bug Reports & Feature Requests</h3>
                        <p class="text-gray-600">
                            Please use our 
                            <a href="#" class="text-blue-600 hover:underline">GitHub Issues</a> 
                            page to report bugs or request new features.
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Social Links -->
            <div class="mt-10 pt-6 border-t border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-600 hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-blue-100 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-600 hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection