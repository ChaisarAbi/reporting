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
    <body class="font-sans antialiased bg-gradient-to-br from-teal-50 to-white text-navy-800">
        <div class="min-h-screen bg-gradient-to-br from-teal-50 to-white">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl border border-teal-100 p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
