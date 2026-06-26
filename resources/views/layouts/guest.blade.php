<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Global Interactive Markets') }}</title>

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
                background-color: #0f172a; /* Force dark background */
                color: #f8fafc;
                font-family: 'Poppins', sans-serif;
            }
            .glass-card {
                background: rgba(30, 41, 59, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .auth-input {
                background-color: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #f8fafc;
                transition: all 0.2s ease;
            }
            .auth-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2);
                background-color: rgba(15, 23, 42, 0.9);
            }
        </style>
    </head>
    <body class="antialiased min-h-screen">
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 overflow-hidden">
            
            <!-- Left Side: Branding & Aesthetics (Visible on lg screens) -->
            <div class="hidden lg:flex lg:col-span-5 bg-slate-950 flex-col justify-between p-12 relative overflow-hidden border-r border-slate-800">
                <!-- Background decorative glow -->
                <div class="absolute -top-40 -left-40 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 relative z-10">
                    <div class="w-10 h-10 bg-gradient-premium rounded-lg flex items-center justify-center text-white shadow-lg">
                        <i class="fa fa-globe text-xl"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-lg tracking-tight text-white block">GLOBAL</span>
                        <span class="text-xs text-teal-400 tracking-widest block -mt-1 font-semibold uppercase">Interactive Markets</span>
                    </div>
                </a>

                <!-- Marketing Text -->
                <div class="my-auto relative z-10 space-y-6">
                    <h2 class="text-3xl font-extrabold text-white tracking-tight leading-tight">
                        Step into the Future of Wealth Management.
                    </h2>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        Experience automated tiered investment returns, ultra-secure wallet operations, and instantaneous global peer-to-peer transfers.
                    </p>
                    
                    <!-- Bullet benefits -->
                    <div class="space-y-3 pt-4">
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i class="fa fa-check-circle text-teal-400 text-base"></i>
                            <span>Tiered Investment Packages from BASIC to VVIP</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i class="fa fa-check-circle text-teal-400 text-base"></i>
                            <span>Instant, Fee-Free Peer-to-Peer Transfers</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i class="fa fa-check-circle text-teal-400 text-base"></i>
                            <span>Comprehensive Know-Your-Customer Verification</span>
                        </div>
                    </div>
                </div>

                <!-- Footer copy -->
                <div class="text-xs text-slate-500 relative z-10">
                    &copy; {{ date('Y') }} Global Interactive Markets. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Forms (Central container) -->
            <div class="lg:col-span-7 flex flex-col items-center justify-center p-6 sm:p-12 relative bg-slate-900">
                <!-- Background decorative glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Logo for mobile screens -->
                <div class="lg:hidden mb-8 flex flex-col items-center">
                    <a href="/" class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-gradient-premium rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i class="fa fa-globe text-xl"></i>
                        </div>
                        <span class="font-extrabold text-lg tracking-tight text-white">GLOBAL</span>
                    </a>
                    <span class="text-[9px] text-teal-400 tracking-widest font-bold uppercase">Interactive Markets</span>
                </div>

                <!-- Form Card -->
                <div class="w-full max-w-md p-8 rounded-2xl glass-card shadow-2xl relative z-10">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
