<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 for pop-up notifications -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle success messages from session
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
                
                // Handle error messages from session
                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}',
                        timer: 5000,
                        showConfirmButton: true
                    });
                @endif
                
                // Handle validation errors
                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: `<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                        showConfirmButton: true
                    });
                @endif
            });
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- Left Side - Branding Section -->
            <div class="lg:w-2/5 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 flex flex-col justify-center items-center p-8 lg:p-12">
                <div class="text-center max-w-md">
                    <!-- Logo -->
                    <div class="mb-8">
                        <x-application-logo class="w-24 h-24 lg:w-32 lg:h-32 mx-auto fill-current text-teal-300" />
                    </div>
                    
                    <!-- Company Name -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4 tracking-tight">
                        HI-LEX INDONESIA
                    </h1>
                    
                    <!-- Tagline -->
                    <p class="text-xl lg:text-2xl font-semibold text-teal-300 mb-6">
                        Machine Breakdown Reporting System
                    </p>
                    
                    <!-- Description -->
                    <div class="mt-8 pt-8 border-t border-navy-700/50">
                        <p class="text-teal-200 text-sm lg:text-base leading-relaxed">
                            Sistem pelaporan kerusakan mesin terintegrasi untuk meningkatkan efisiensi maintenance dan mengurangi downtime produksi.
                        </p>
                    </div>
                    
                    <!-- Features List -->
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center text-teal-200">
                            <svg class="w-5 h-5 mr-2 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Real-time Reporting</span>
                        </div>
                        <div class="flex items-center text-teal-200">
                            <svg class="w-5 h-5 mr-2 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Analytics Dashboard</span>
                        </div>
                        <div class="flex items-center text-teal-200">
                            <svg class="w-5 h-5 mr-2 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Multi-role Access</span>
                        </div>
                        <div class="flex items-center text-teal-200">
                            <svg class="w-5 h-5 mr-2 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Mobile Friendly</span>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="mt-12 lg:mt-auto pt-8 border-t border-navy-700/30 w-full text-center">
                    <p class="text-teal-300 text-sm">
                        &copy; {{ date('Y') }} PT. Hi-Lex Indonesia. All rights reserved.
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="lg:w-3/5 flex flex-col justify-center items-center p-6 lg:p-12 bg-gradient-to-br from-teal-50 to-white">
                <div class="w-full max-w-md">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl border border-teal-100 p-8">
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-navy-900">Welcome Back</h2>
                            <p class="text-navy-600 mt-2">Sign in to your account to continue</p>
                        </div>

                        {{ $slot }}
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
</html>
