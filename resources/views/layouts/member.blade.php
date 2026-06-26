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

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/design_tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('custom_ui.css') }}">
    
    <style>
        /* Modern UI style for member area */
        body {
            background-color: var(--background);
            color: var(--text);
            padding-bottom: 5rem; /* Space for bottom nav */
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
            z-index: 50;
        }
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .bottom-nav-item:hover, .bottom-nav-item.active {
            color: var(--primary);
        }
        .bottom-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Impersonating Banner -->
    @if(session()->has('impersonate'))
        <div class="bg-yellow-500 text-slate-950 font-bold px-4 py-2.5 text-center flex items-center justify-center gap-2 relative z-50 text-sm">
            <i class="fa fa-exclamation-triangle"></i>
            <span>You are currently impersonating <strong>{{ Auth::user()->name }}</strong> (ID: {{ Auth::user()->id }}).</span>
            <a href="{{ route('admin.leave_impersonate') }}" class="underline hover:text-slate-800 ml-2">
                <i class="fa fa-sign-out"></i> Return to Admin Session
            </a>
        </div>
    @endif

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-premium rounded-lg flex items-center justify-center text-white shadow">
                    <i class="fa fa-globe"></i>
                </div>
                <div>
                    <span class="font-extrabold text-sm tracking-tight text-slate-900 block leading-tight">GLOBAL</span>
                    <span class="text-[9px] text-teal-600 tracking-widest block font-bold uppercase leading-tight">Interactive Markets</span>
                </div>
            </a>

            <!-- User Menu -->
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-500 hidden sm:inline-block">
                    Country: <strong>{{ Auth::user()->country_name }}</strong> ({{ Auth::user()->currency_code }})
                </span>

                <!-- Profile and Logout -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-950 flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                        <i class="fa fa-user-circle text-lg"></i>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                    </a>
                    
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.index') }}" class="text-xs font-bold uppercase tracking-wider bg-teal-100 text-teal-800 px-2 py-1 rounded hover:bg-teal-200 transition">
                            Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-600 p-2 rounded-lg hover:bg-slate-100 transition" title="Log Out">
                            <i class="fa fa-power-off text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main View Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-lg shadow-sm flex items-center gap-3 animate-fade-in-up">
                <i class="fa fa-check-circle text-lg"></i>
                <span class="text-sm font-medium">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg shadow-sm animate-fade-in-up">
                <div class="flex items-center gap-3 mb-2 text-sm font-bold">
                    <i class="fa fa-times-circle text-lg"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav">
        <div class="max-w-lg mx-auto grid grid-cols-6 h-16">
            <!-- Deposit -->
            <a href="{{ route('wallet.deposit') }}" class="bottom-nav-item {{ request()->routeIs('wallet.deposit') ? 'active' : '' }}">
                <i class="fa fa-arrow-circle-down"></i>
                <span>Deposit</span>
            </a>

            <!-- Invest -->
            <a href="{{ route('investment.index') }}" class="bottom-nav-item {{ request()->routeIs('investment.index') ? 'active' : '' }}">
                <i class="fa fa-line-chart"></i>
                <span>Invest</span>
            </a>

            <!-- Home (Dashboard) -->
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa fa-home"></i>
                <span>Home</span>
            </a>

            <!-- History -->
            <a href="{{ route('investment.history') }}" class="bottom-nav-item {{ request()->routeIs('investment.history') ? 'active' : '' }}">
                <i class="fa fa-history"></i>
                <span>History</span>
            </a>

            <!-- Withdraw -->
            <a href="{{ route('wallet.withdraw') }}" class="bottom-nav-item {{ request()->routeIs('wallet.withdraw') ? 'active' : '' }}">
                <i class="fa fa-arrow-circle-up"></i>
                <span>Withdraw</span>
            </a>

            <!-- Transfer -->
            <a href="{{ route('wallet.transfer') }}" class="bottom-nav-item {{ request()->routeIs('wallet.transfer') ? 'active' : '' }}">
                <i class="fa fa-exchange"></i>
                <span>Transfer</span>
            </a>
        </div>
    </nav>

</body>
</html>
