{{-- resources/views/employee/profile.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->full_name }} — My Profile</title>
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
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
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full bg-[#0f172a] text-white flex items-center justify-center p-6 selection:bg-brand-500 selection:text-white overflow-hidden">

    {{-- Background decoration --}}
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-500/20 blur-[120px] rounded-full"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-indigo-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10rem] left-1/2 -translate-x-1/2 w-[40rem] h-96 bg-brand-600/10 blur-[150px] rounded-full"></div>
    </div>

    <div class="w-full max-w-2xl animate-in fade-in zoom-in duration-700">
        
        {{-- Navigation Header --}}
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('tour.index') }}" class="group flex items-center gap-3 px-5 py-2.5 rounded-2xl glass hover:bg-white/10 transition-all">
                <svg class="w-5 h-5 text-brand-400 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-sm font-semibold tracking-wide">Back to Tour</span>
            </a>
            <div class="px-5 py-2.5 rounded-2xl bg-brand-500/10 border border-brand-500/20 text-brand-400 text-xs font-bold uppercase tracking-[0.2em]">
                My Employee Card
            </div>
        </div>

        <div class="glass rounded-[3rem] overflow-hidden shadow-[0_32px_128px_-16px_rgba(0,0,0,0.6)]">
            <div class="grid grid-cols-1 md:grid-cols-5 h-full">
                
                {{-- Left: Identiy Side --}}
                <div class="md:col-span-2 bg-white/5 border-r border-white/5 p-10 flex flex-col items-center justify-center text-center">
                    <div class="relative mb-8 group">
                        <div class="absolute inset-0 bg-brand-500 blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                        <img src="{{ $employee->photo_url ?: 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=6366f1&color=fff&size=512' }}" 
                             alt="{{ $employee->full_name }}" 
                             class="w-40 h-40 rounded-[3rem] object-cover border-4 border-white/20 relative shadow-2xl transition-transform group-hover:scale-105 duration-500">
                    </div>
                    
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">{{ $employee->full_name }}</h1>
                    <p class="text-brand-400 font-bold uppercase text-[10px] tracking-[0.2em] mb-4">{{ $employee->job_title }}</p>
                    
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-mono font-bold text-slate-400">
                        {{ $employee->matricule }}
                    </div>
                </div>

                {{-- Right: Content Side --}}
                <div class="md:col-span-3 p-10 flex flex-col">
                    <div class="space-y-6 flex-1">
                        
                        {{-- Data Grid --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-5 rounded-3xl bg-white/5 border border-white/5 group hover:bg-white/10 hover:border-white/10 transition-all">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-2">Department</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="text-base font-semibold">{{ $employee->department?->name ?: 'Public' }}</span>
                                </div>
                            </div>
                            
                            <a href="mailto:{{ $employee->email }}" class="p-5 rounded-3xl bg-white/5 border border-white/5 group hover:bg-brand-500/10 hover:border-brand-500/30 transition-all text-left">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-2">Email Contact</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-base font-semibold">{{ $employee->email }}</span>
                                </div>
                            </a>

                            <a href="tel:{{ $employee->phone }}" class="p-5 rounded-3xl bg-white/5 border border-white/5 group hover:bg-brand-500/10 hover:border-brand-500/30 transition-all text-left">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-2">Phone Number</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1c-8.284 0-15-6.716-15-15V5z"/></svg>
                                    </div>
                                    <span class="text-base font-semibold">{{ $employee->phone ?: 'Not provided' }}</span>
                                </div>
                            </a>
                        </div>

                        {{-- QR Preview Card --}}
                        @if($employee->qr_code_url)
                        <div class="mt-4 p-6 rounded-[2rem] bg-indigo-50/5 border border-white/5 flex items-center gap-6 group hover:bg-brand-500/5 transition-all">
                            <div class="p-3 bg-white rounded-2xl shrink-0 group-hover:scale-105 transition-transform duration-500">
                                <img src="{{ $employee->qr_code_url }}" class="w-20 h-20" alt="My QR Code">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm mb-1">My Public ID</h3>
                                <p class="text-[10px] text-slate-500 leading-relaxed mb-3">Scan this code to show your profile to visitors without internal tour access.</p>
                                <a href="{{ $employee->qr_code_url }}" download class="text-[10px] font-bold text-brand-400 hover:text-white transition-colors uppercase tracking-widest flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download Card
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Footer details --}}
        <div class="mt-8 flex items-center justify-between px-6 opacity-40">
            <p class="text-[10px] font-bold uppercase tracking-[0.3em]">Corporate Virtual Tour System</p>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em]">Proprietary Profile Card</p>
        </div>
    </div>
</body>
</html>
