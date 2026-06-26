<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Global Interactive Markets — Premier Investment Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/design_tokens.css') }}">
        <link rel="stylesheet" href="{{ asset('custom_ui.css') }}">

        <style>
            body {
                background-color: #0f172a; /* Force dark background for landing page */
                color: #f8fafc;
            }
            .hero-glow {
                position: absolute;
                top: -10%;
                left: 50%;
                transform: translateX(-50%);
                width: 600px;
                height: 300px;
                background: radial-gradient(circle, rgba(13,148,136,0.15) 0%, rgba(15,23,42,0) 70%);
                filter: blur(50px);
                z-index: 0;
                pointer-events: none;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen flex flex-col justify-between">
        
        <!-- Header -->
        <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-premium rounded-lg flex items-center justify-center text-white shadow-lg">
                    <i class="fa fa-globe text-xl"></i>
                </div>
                <div>
                    <span class="font-extrabold text-lg tracking-tight text-white block">GLOBAL</span>
                    <span class="text-xs text-teal-400 tracking-widest block -mt-1 font-semibold uppercase">Interactive Markets</span>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary flex items-center gap-2">
                            <span>Go to Dashboard</span>
                            <i class="fa fa-arrow-right text-sm"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-semibold transition text-sm">
                            Log In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-sm">
                                Register Now
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col justify-center relative">
            <div class="hero-glow"></div>

            <!-- Hero Section -->
            <section class="max-w-7xl mx-auto px-6 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                <div class="lg:col-span-7 flex flex-col items-start text-left animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-teal-400 text-xs font-semibold mb-6">
                        <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                        <span>Now Global: Multi-National Accounts Active</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-none mb-6">
                        Empower Your Financial Future
                    </h1>
                    
                    <p class="text-slate-400 text-lg md:text-xl max-w-2xl mb-8 leading-relaxed">
                        Access cutting-edge tiered investment plans designed to maximize your yield. Securely deposit, invest, monitor maturity, and withdraw profits on a world-class platform.
                    </p>

                    <div class="flex flex-wrap gap-4 mb-12">
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-3 text-base flex items-center gap-2 shadow-lg">
                            <span>Get Started</span>
                            <i class="fa fa-rocket"></i>
                        </a>
                        <a href="#features" class="px-6 py-3 border border-slate-700 rounded-lg hover:border-slate-500 text-slate-300 hover:text-white font-semibold text-base transition">
                            Learn More
                        </a>
                    </div>

                    <!-- Live Stats -->
                    <div class="grid grid-cols-3 gap-6 md:gap-10 border-t border-slate-800 pt-8 w-full">
                        <div>
                            <span class="block text-2xl md:text-3xl font-extrabold text-white">$48.2M+</span>
                            <span class="text-xs md:text-sm text-slate-500 uppercase tracking-wider font-semibold">Total Invested</span>
                        </div>
                        <div>
                            <span class="block text-2xl md:text-3xl font-extrabold text-white">12.4K+</span>
                            <span class="text-xs md:text-sm text-slate-500 uppercase tracking-wider font-semibold">Active Members</span>
                        </div>
                        <div>
                            <span class="block text-2xl md:text-3xl font-extrabold text-white">99.9%</span>
                            <span class="text-xs md:text-sm text-slate-500 uppercase tracking-wider font-semibold">Platform Uptime</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side Graphic / Mockup -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl relative">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                            <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Market Overview</span>
                            <span class="text-teal-400 text-xs font-semibold flex items-center gap-1">
                                <span class="w-2.5 h-2.5 bg-teal-400 rounded-full inline-block animate-ping"></span>
                                Live Trading Feed
                            </span>
                        </div>
                        <!-- Mini widget showing trading info -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-slate-950 p-3 rounded-lg border border-slate-800">
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-bitcoin text-yellow-500 text-xl"></i>
                                    <div>
                                        <span class="block text-xs font-bold text-white">BTC / USD</span>
                                        <span class="block text-[10px] text-slate-500">Bitcoin</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-teal-400">+$1,290.40</span>
                                    <span class="block text-[10px] text-teal-500">+2.15%</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center bg-slate-950 p-3 rounded-lg border border-slate-800">
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-cubes text-purple-500 text-xl"></i>
                                    <div>
                                        <span class="block text-xs font-bold text-white">ETH / USD</span>
                                        <span class="block text-[10px] text-slate-500">Ethereum</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-teal-400">+$84.10</span>
                                    <span class="block text-[10px] text-teal-500">+3.40%</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center bg-slate-950 p-3 rounded-lg border border-slate-800">
                                <div class="flex items-center gap-2">
                                    <i class="fa fa-line-chart text-blue-500 text-xl"></i>
                                    <div>
                                        <span class="block text-xs font-bold text-white">XAU / USD</span>
                                        <span class="block text-[10px] text-slate-500">Gold Spot</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-red-400">-$12.50</span>
                                    <span class="block text-[10px] text-red-500">-0.58%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gradient-premium rounded-xl text-white">
                            <span class="block text-xs text-teal-200 uppercase tracking-widest font-bold">Featured Catalog</span>
                            <span class="block text-lg font-bold mt-1">BASIC 1 Plan</span>
                            <div class="flex justify-between items-center mt-3 text-sm">
                                <div>
                                    <span class="block text-[10px] text-teal-200 uppercase">Capital</span>
                                    <span class="font-extrabold">$500</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] text-teal-200 uppercase">Expected Profit</span>
                                    <span class="font-extrabold text-teal-200">$15,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-800">
                <h2 class="text-3xl font-bold text-center text-white mb-16">Designed for Global Prosperity</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-slate-900 border border-slate-800 p-8 rounded-xl">
                        <div class="w-12 h-12 bg-teal-500/10 text-teal-400 rounded-lg flex items-center justify-center mb-6">
                            <i class="fa fa-shield text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Enterprise Grade Security</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Your transactions are fully secured. We employ strict wallet isolation, database locking mechanics, and full CSRF tokens to safeguard all funds.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-slate-900 border border-slate-800 p-8 rounded-xl">
                        <div class="w-12 h-12 bg-teal-500/10 text-teal-400 rounded-lg flex items-center justify-center mb-6">
                            <i class="fa fa-line-chart text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Tiered Investment Yields</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Access high-performance plans categorized from BASIC to VVIP levels. Benefit from fully automated maturity triggers that credit returns to your wallet.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-slate-900 border border-slate-800 p-8 rounded-xl">
                        <div class="w-12 h-12 bg-teal-500/10 text-teal-400 rounded-lg flex items-center justify-center mb-6">
                            <i class="fa fa-exchange text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Seamless Peer Transfer</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Send money globally to any member email instantly. Peer-to-peer transfers are fully auto-approved with low transaction latency.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-800 py-8 bg-slate-950">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-slate-500 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa fa-globe"></i>
                    <span>&copy; {{ date('Y') }} Global Interactive Markets. All rights reserved.</span>
                </div>
                <div class="max-w-md text-xs text-center md:text-right text-slate-600">
                    Disclaimer: This is a simulated high-yield investment demonstration platform. All market assets shown are virtual and do not represent real-world securities or financial products.
                </div>
            </div>
        </footer>

    </body>
</html>
