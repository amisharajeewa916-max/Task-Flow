<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskFlow - Plan Smart. Work Easy. Finish More.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,450,550,650,750,850|plus-jakarta-sans:300,400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-[#090A0F] text-[#F3F4F6] selection:bg-indigo-500 selection:text-white overflow-x-hidden">
    
    <!-- Decorative glow effects -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-[20%] right-[10%] w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="min-h-screen flex flex-col justify-between">
        <!-- Header -->
        <header class="max-w-7xl mx-auto px-6 py-6 w-full flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.jpeg') }}" alt="TaskFlow Logo" class="h-10 w-10 rounded-xl shadow-lg border border-gray-800" />
                <span class="font-outfit text-xl font-bold tracking-tight bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
                    TaskFlow
                </span>
            </div>

            <nav class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl text-sm font-semibold shadow-lg shadow-indigo-600/20 transition-all duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-white text-sm font-semibold transition">
                            Sign In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white rounded-full text-sm font-semibold shadow-xl shadow-indigo-500/25 ring-1 ring-indigo-500/20 transition-all duration-200 transform hover:-translate-y-0.5">
                                Start for Free
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="max-w-7xl mx-auto px-6 py-12 lg:py-24 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10 flex-1">
            <div class="space-y-8">
                <!-- SaaS Premium Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
                    <span class="h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Now in Laravel 12
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-outfit font-extrabold tracking-tight text-white leading-tight">
                    Plan smart.<br>
                    Work easy.<br>
                    <span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                        Finish more.
                    </span>
                </h1>

                <p class="text-gray-400 text-base sm:text-lg max-w-lg leading-relaxed font-light">
                    The complete university-migrated SaaS Task Management tool. Seamlessly manage academic and professional workflows, interact real-time with teams, and elevate your productivity.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 text-center bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white rounded-xl text-base font-bold shadow-xl shadow-indigo-500/20 transition transform hover:-translate-y-0.5">
                        Get Started Free
                    </a>
                    <a href="#features" class="px-8 py-4 text-center bg-gray-900/60 hover:bg-gray-900 border border-gray-850 hover:border-gray-700 text-gray-300 hover:text-white rounded-xl text-base font-semibold transition">
                        Explore Features &rarr;
                    </a>
                </div>

                <!-- Stats Summary -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-800/60 max-w-md">
                    <div>
                        <div class="text-2xl font-bold text-white font-outfit">10k+</div>
                        <div class="text-xs text-gray-500">Active Users</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white font-outfit">99.9%</div>
                        <div class="text-xs text-gray-500">Uptime SLA</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white font-outfit">COMP</div>
                        <div class="text-xs text-gray-500">50016 Passed</div>
                    </div>
                </div>
            </div>

            <!-- Beautiful UI mock block -->
            <div class="relative lg:block hidden">
                <div class="absolute -inset-1 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-600 opacity-20 blur-xl"></div>
                <div class="relative bg-gray-900/80 border border-gray-800 rounded-2xl p-6 shadow-2xl backdrop-blur-md">
                    <!-- Fake Card header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-800 mb-6">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-xs font-mono text-gray-500">TaskFlow Workspace</span>
                    </div>

                    <!-- Fake Card Tasklist -->
                    <div class="space-y-4">
                        <div class="bg-gray-950/60 border border-gray-800/80 p-4 rounded-xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="h-5 w-5 rounded-full border border-indigo-500 flex items-center justify-center">
                                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                </span>
                                <span class="text-sm font-medium text-gray-200">Migrate COMP50016 Assignment to Laravel 12</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-xxs font-bold uppercase">High</span>
                        </div>

                        <div class="bg-gray-950/60 border border-gray-800/80 p-4 rounded-xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="h-5 w-5 rounded-full border border-gray-700"></span>
                                <span class="text-sm font-medium text-gray-300">Set up Sanctum APIs & Security.md</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-xxs font-bold uppercase">Med</span>
                        </div>

                        <div class="bg-gray-950/30 border border-dashed border-gray-800 p-4 rounded-xl flex items-center justify-center text-xs text-gray-500 cursor-pointer hover:border-indigo-500 transition">
                            + Add New Task
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features/Tiers -->
        <section id="features" class="max-w-7xl mx-auto px-6 py-12 lg:py-20 w-full relative z-10 border-t border-gray-900">
            <div class="text-center max-w-xl mx-auto mb-16 space-y-3">
                <h2 class="text-3xl font-outfit font-extrabold text-white">Compare Our Plan Tiers</h2>
                <p class="text-gray-400 text-sm font-light">Experience Freemium at its finest, tailored to students, researchers, and small teams.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-gray-950/40 border border-gray-800/60 rounded-2xl p-8 flex flex-col justify-between hover:border-gray-700/60 transition">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-gray-200">Free Tier</h3>
                            <p class="text-xs text-gray-500">Perfect for individual study and basic task tracking.</p>
                        </div>
                        <div class="text-3xl font-bold font-outfit text-white">$0 <span class="text-sm text-gray-500 font-normal">/ forever</span></div>
                        <ul class="space-y-3.5 text-xs text-gray-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Basic task creation & updates
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Filter by status and priorities
                            </li>
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-gray-750 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                No comments or collaborative spaces
                            </li>
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-gray-750 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                No secure private attachments
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 block py-2.5 text-center bg-gray-900 border border-gray-850 hover:bg-gray-800 text-white rounded-xl text-sm font-semibold transition">
                        Get Started
                    </a>
                </div>

                <!-- Premium Plan -->
                <div class="bg-gradient-to-b from-gray-950 to-gray-900/60 border border-indigo-500/20 rounded-2xl p-8 flex flex-col justify-between hover:border-indigo-500/40 transition relative">
                    <span class="absolute top-0 right-6 -translate-y-1/2 px-2.5 py-0.5 rounded-full bg-indigo-500 text-white text-xxs font-extrabold uppercase tracking-wide">Premium</span>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-white">Premium Tier</h3>
                            <p class="text-xs text-gray-400">Complete, unlimited team collaboration space.</p>
                        </div>
                        <div class="text-3xl font-bold font-outfit text-white">$9.99 <span class="text-sm text-gray-500 font-normal">/ month</span></div>
                        <ul class="space-y-3.5 text-xs text-gray-300">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Everything in Free, plus:
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Team workspaces & member assignments
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Real-time dynamic comments
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Secure file uploads & private storage access
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Real-time Notification bells
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 block py-2.5 text-center bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/20 transition">
                        Unlock Premium
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto px-6 py-8 w-full border-t border-gray-900 text-center text-xs text-gray-500 relative z-10">
            &copy; 2026 TaskFlow. COMP50016 Server Side Programming II. All rights reserved.
        </footer>
    </div>
</body>
</html>
