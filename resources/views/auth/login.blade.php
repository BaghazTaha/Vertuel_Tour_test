{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — Virtual Tour</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="min-h-screen bg-navy-900 bg-gradient-to-br from-navy-900 via-navy-800 to-brand-900 flex items-center justify-center font-sans antialiased text-slate-800 selection:bg-brand-500 selection:text-white relative overflow-hidden">

    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[30rem] h-[30rem] bg-indigo-500/20 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md px-6 z-10 animate-fade-in">

        {{-- Logo / Title --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 shadow-2xl shadow-brand-500/40 mb-5 relative group">
                <div class="absolute inset-0 rounded-2xl ring-1 ring-white/20"></div>
                {{-- Globe icon --}}
                <svg class="w-10 h-10 text-white transform group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945
                           M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0
                           2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064
                           M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight mb-2">Virtual Tour Dashboard</h1>
            <p class="text-slate-400 text-sm font-medium">Welcome back! Please sign in to continue.</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-black/50 p-8 sm:p-10 relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-brand-400 to-brand-600"></div>

            {{-- Session error --}}
            @if (session('status'))
                <div class="mb-6 flex items-start gap-3 text-sm text-green-700 bg-green-50 rounded-xl px-4 py-3 border border-green-100">
                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm transition-colors duration-200 outline-none
                                   focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500
                                   @error('email') border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 bg-slate-50 hover:bg-slate-100/50 @enderror"
                            placeholder="you@company.com"
                        />
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm transition-colors duration-200 outline-none
                                   focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500
                                   @error('password') border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500/20 @else border-slate-200 bg-slate-50 hover:bg-slate-100/50 @enderror"
                            placeholder="••••••••"
                        />
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 text-sm text-slate-600 cursor-pointer group hover:text-slate-800 transition-colors">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 focus:ring-2 transition duration-200 cursor-pointer"/>
                        <span class="font-medium select-none">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold flex items-center justify-center gap-2
                           py-3.5 rounded-xl transition-all duration-200 text-sm shadow-lg shadow-brand-500/30 hover:shadow-brand-500/40 hover:-translate-y-0.5 mt-2">
                    <span>Sign In</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8 font-medium">
            &copy; {{ date('Y') }} Virtual Tour System. All rights reserved.
        </p>
    </div>

</body>
</html>