{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Admin') — Virtual Tour</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .nav-link {
                @apply flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200;
            }
            .nav-link-active {
                @apply bg-brand-500 text-white shadow-md shadow-brand-500/20;
            }
            .nav-link-inactive {
                @apply text-slate-300 hover:bg-white/10 hover:text-white;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen flex font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white">

{{-- ═══════════════════════════════════════════
     SIDEBAR
     Mobile: Hidden by default, toggled with JS
     Desktop: Fixed on left
═══════════════════════════════════════════ --}}
<div id="sidebar-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden md:hidden transition-all duration-300"></div>

<aside id="admin-sidebar" class="w-72 min-h-screen bg-navy-900 border-e border-slate-800 text-white flex flex-col fixed top-0 start-0 z-40 shadow-2xl transition-transform duration-300 ltr:-translate-x-full rtl:translate-x-full md:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-4 px-6 py-6 border-b border-white/10">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shrink-0 shadow-lg shadow-brand-500/30">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945
                       M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104
                       0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064
                       M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-bold tracking-wide text-white">{{ __('Virtual Tour') }}</p>
            <p class="text-xs text-brand-200 mt-0.5 font-medium uppercase tracking-wider">{{ __('Admin Panel') }}</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                       2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                       011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            {{ __('Dashboard') }}
        </a>

        {{-- Departments --}}
        <a href="{{ route('admin.departments.index') }}"
           class="nav-link {{ request()->routeIs('admin.departments.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14
                       0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1
                       4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            {{ __('Departments') }}
        </a>

        {{-- Employees --}}
        <a href="{{ route('admin.employees.index') }}"
           class="nav-link {{ request()->routeIs('admin.employees.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10
                       0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3
                       0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0
                       0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ __('Employees') }}
        </a>

        {{-- Spaces --}}
        <a href="{{ route('admin.spaces.index') }}"
           class="nav-link {{ request()->routeIs('admin.spaces.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16
                       16m-2-2l1.586-1.586a2 2 0 012.828 0L20
                       14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0
                       00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ __('Spaces') }}
        </a>

        {{-- Groups --}}
        <a href="{{ route('admin.groups.index') }}"
           class="nav-link {{ request()->routeIs('admin.groups.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10
                       0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3
                       0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0
                       0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ __('Groups') }}
        </a>

        {{-- Trainers --}}
        <a href="{{ route('admin.trainers.index') }}"
           class="nav-link {{ request()->routeIs('admin.trainers.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            {{ __('Trainers') }}
        </a>

        {{-- Students --}}
        <a href="{{ route('admin.students.index') }}"
           class="nav-link {{ request()->routeIs('admin.students.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
            </svg>
            {{ __('Students') }}
        </a>

        {{-- Schedules --}}
        <a href="{{ route('admin.schedules.index') }}"
           class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ __('Schedules') }}
        </a>

    </nav>

    {{-- User info + logout --}}
    <div class="p-4 bg-navy-800/50 border-t border-white/5">
        <div class="bg-navy-900 rounded-xl p-3 border border-white/10">
            <div class="flex items-center gap-3 mb-3 pb-3 border-b border-white/5">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-sm font-bold text-white shadow-inner shrink-0 ring-2 ring-navy-800">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? __('Admin User') }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ __('Administrator') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
                           text-slate-300 hover:bg-red-500 hover:text-white transition-colors duration-200 group">
                    <svg class="w-4 h-4 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0
                               01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ --}}
<div class="flex-1 ms-0 md:ms-72 flex flex-col min-h-screen relative">

    {{-- Top bar --}}
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-8 py-5 flex items-center justify-between sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-3 md:gap-4">
            {{-- Mobile Toggle --}}
            <button onclick="toggleMobileMenu()" class="p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100 md:hidden transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-xl md:text-2xl font-bold text-navy-900 tracking-tight truncate">@yield('page-title', __('Dashboard'))</h1>
        </div>
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-3 px-4 py-2 bg-slate-100 rounded-full text-sm text-slate-600 font-medium border border-slate-200 shrink-0">
                <svg class="w-4 h-4 text-brand-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0
                           002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ now()->format('D, M d Y') }}
            </div>
            
            {{-- Topbar Sign Out Button --}}
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 rounded-full transition-colors shadow-sm"
                        title="Sign Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">{{ __('Sign out') }}</span>
                </button>
            </form>
            
            {{-- Language Dropdown --}}
            <div class="relative group" tabindex="0">
                <button class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-full transition-colors shadow-sm focus:outline-none">
                    🌍 {{ strtoupper(app()->getLocale()) }}
                </button>
                <div class="absolute end-0 mt-2 w-32 bg-white rounded-xl shadow-lg border border-slate-100 opacity-0 invisible focus-within:opacity-100 focus-within:visible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-2">
                        <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 {{ app()->getLocale() == 'fr' ? 'font-bold bg-brand-50 text-brand-600' : '' }}">🇫🇷 Français</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 {{ app()->getLocale() == 'en' ? 'font-bold bg-brand-50 text-brand-600' : '' }}">🇬🇧 English</a>
                        <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 {{ app()->getLocale() == 'ar' ? 'font-bold bg-brand-50 text-brand-600' : '' }}">🇲🇦 عربي</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash messages (Toast style) --}}
    <div class="fixed top-24 end-8 z-50 flex flex-col gap-3 max-w-sm w-full">
        @if (session('success'))
            <div class="flex items-start gap-3 bg-white border border-green-100 shadow-xl shadow-green-500/10 rounded-2xl p-4 animate-fade-in-down border-s-4 border-s-green-500">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-sm font-semibold text-slate-800">Success!</p>
                    <p class="text-sm text-slate-500 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 bg-white border border-red-100 shadow-xl shadow-red-500/10 rounded-2xl p-4 animate-fade-in-down border-s-4 border-s-red-500">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-sm font-semibold text-slate-800">Error</p>
                    <p class="text-sm text-slate-500 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Page content --}}
    <main class="flex-1 px-8 py-8 md:px-10 lg:px-12 max-w-7xl w-full mx-auto">
        <div class="animate-fade-in-up">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white px-8 py-5 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <p>&copy; {{ date('Y') }} <span class="font-medium text-slate-700">Virtual Tour System</span>. All rights reserved.</p>
        <p class="text-xs">Version 1.0.0</p>
    </footer>
</div>

<script>
    function toggleMobileMenu() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const icon = document.getElementById('menu-icon');
        
        const isOpen = sidebar.classList.contains('translate-x-0');
        
        
        if (isOpen) {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
        } else {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
        }
    }

    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', () => {
        const flashes = document.querySelectorAll('.animate-fade-in-down');
        flashes.forEach(flash => {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transform = 'translateY(-10px)';
                flash.style.transition = 'all 0.5s ease-in-out';
                setTimeout(() => flash.remove(), 500);
            }, 5000);
        });
    });
</script>

<style>
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
@stack('scripts')


</body>
</html>