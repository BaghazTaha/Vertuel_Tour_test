{{-- resources/views/employee/public.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $employee->full_name }} — Professional Profile</title>
    
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
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .profile-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(79, 70, 229, 0.1) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full profile-mesh text-white flex items-center justify-center p-4 selection:bg-brand-500 selection:text-white">

    <div class="w-full max-w-md animate-in fade-in slide-in-from-bottom-8 duration-700">
        
        {{-- Branding Header --}}
        <div class="mb-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl glass mb-4 border-brand-500/20">
                <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-brand-400">Verified Professional ID</span>
            </div>
        </div>

        <div class="glass rounded-[3rem] overflow-hidden shadow-[0_32px_128px_-16px_rgba(0,0,0,0.8)] border border-white/5 relative">
            
            {{-- Background decorative element --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 blur-3xl rounded-full -mr-16 -mt-16"></div>

            {{-- Main Content --}}
            <div class="p-8 pt-10 flex flex-col items-center">
                
                {{-- Profile Photo --}}
                <div class="relative mb-6 group">
                    <div class="absolute inset-0 bg-brand-500/20 blur-2xl rounded-full scale-110"></div>
                    <img src="{{ $employee->photo_url ?: 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=6366f1&color=fff&size=512' }}" 
                         alt="{{ $employee->full_name }}" 
                         class="w-36 h-36 rounded-[2.5rem] object-cover border-4 border-white/10 relative shadow-2xl">
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">{{ $employee->full_name }}</h1>
                    <div class="inline-block px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ $employee->job_title }}</span>
                    </div>
                </div>

                {{-- Fields Grid --}}
                <div class="w-full space-y-3">
                    
                    {{-- Department --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Department</p>
                            <p class="text-sm font-semibold">{{ $employee->department?->name ?: 'Corporate' }}</p>
                        </div>
                    </div>

                    {{-- Matricule --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Registration ID</p>
                            <p class="text-sm font-mono font-bold">{{ $employee->matricule }}</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <a href="mailto:{{ $employee->email }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-brand-500/10 hover:border-brand-500/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Email Address</p>
                            <p class="text-sm font-semibold truncate group-hover:text-brand-400 transition-colors">{{ $employee->email }}</p>
                        </div>
                    </a>

                    {{-- Phone --}}
                    <a href="tel:{{ $employee->phone }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-brand-500/10 hover:border-brand-500/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-400 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1c-8.284 0-15-6.716-15-15V5z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Office Phone</p>
                            <p class="text-sm font-semibold truncate group-hover:text-brand-400 transition-colors">{{ $employee->phone ?: 'Not provided' }}</p>
                        </div>
                    </a>
                </div>

                {{-- Footer Branding --}}
                <div class="mt-8 pt-6 border-t border-white/5 w-full text-center">
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.4em]">Corporate Virtual Tour</p>
                </div>

            </div>
        </div>

        {{-- Help text --}}
        <div class="mt-8 text-center text-slate-500 text-[11px] leading-relaxed">
            This card is an official professional identification generated by the Corporate Virtual Tour platform. It serves both as a virtual profile and a digital directory contact.
        </div>
    </div>
</body>
</html>