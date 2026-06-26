<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Global Interactive Markets - Admin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/design_tokens.css') }}">
        <link rel="stylesheet" href="{{ asset('custom_ui.css') }}">

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #020617; /* Dark slate 950 */
                color: #f8fafc;
            }
            
            /* Class-scoped admin dark overrides */
            .admin-container {
                background-color: #020617;
                color: #e2e8f0;
            }
            .admin-container .bg-white {
                background-color: #0f172a !important; /* Slate 900 */
                border: 1px solid #1e293b !important; /* Slate 800 */
            }
            .admin-container .border-gray-100,
            .admin-container .border-gray-200,
            .admin-container .border-slate-200,
            .admin-container .divide-gray-100 > * {
                border-color: #1e293b !important;
            }
            .admin-container .text-gray-900,
            .admin-container .text-gray-800 {
                color: #f8fafc !important;
            }
            .admin-container .text-gray-700 {
                color: #cbd5e1 !important;
            }
            .admin-container .text-gray-600,
            .admin-container .text-gray-500,
            .admin-container .text-gray-400 {
                color: #94a3b8 !important;
            }
            .admin-container input[type="text"],
            .admin-container input[type="email"],
            .admin-container input[type="number"],
            .admin-container select,
            .admin-container textarea {
                background-color: #0b0f19 !important;
                border: 1px solid #334155 !important;
                color: #f8fafc !important;
            }
            .admin-container input[type="text"]:focus,
            .admin-container input[type="email"]:focus,
            .admin-container input[type="number"]:focus,
            .admin-container select:focus,
            .admin-container textarea:focus {
                border-color: #0d9488 !important;
                box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2) !important;
            }
            .admin-container .hover\:bg-slate-50:hover,
            .admin-container .hover\:bg-gray-50:hover,
            .admin-container tr.hover\:bg-slate-50:hover {
                background-color: #1e293b !important;
            }
            .admin-container .bg-slate-50 {
                background-color: #0f172a !important;
            }

            /* Scoped nav link styling for admin navigation */
            .admin-container nav a {
                transition: all 0.2s ease !important;
            }
            .admin-container nav a[class*="border-indigo-400"] {
                border-color: #0d9488 !important; /* Premium Teal */
                color: #f8fafc !important;
            }
            .admin-container nav a[class*="border-transparent"]:hover {
                border-color: rgba(13, 148, 136, 0.4) !important;
                color: #ffffff !important;
            }
            
            /* Scoped responsive nav links */
            .admin-container nav a[class*="border-l-4"][class*="border-indigo-400"] {
                border-color: #0d9488 !important;
                background-color: rgba(13, 148, 136, 0.1) !important;
                color: #f8fafc !important;
            }
            .admin-container nav a[class*="border-l-4"][class*="border-transparent"]:hover {
                border-color: rgba(13, 148, 136, 0.4) !important;
                background-color: #1e293b !important;
                color: #ffffff !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-100 bg-slate-950">
        <div class="min-h-screen bg-slate-950 flex flex-col justify-between admin-container">
            <div>
                <!-- Navigation -->
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-slate-900 border-b border-slate-800 py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                    {{ $slot }}
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-slate-900 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
                <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="font-bold flex items-center gap-1 text-slate-300">
                        <i class="fa fa-globe text-teal-500"></i>
                        GLOBAL INTERACTIVE MARKETS &bull; Admin Panel
                    </span>
                    <span>&copy; {{ date('Y') }} Admin Dashboard. Secure Audit Mode Active.</span>
                </div>
            </footer>
        </div>
    </body>
</html>
