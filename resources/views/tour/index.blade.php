{{-- resources/views/tour/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360° Virtual Tour</title>
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Pannellum --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    
    {{-- Tailwind (CDN for rapid high-quality UI) --}}
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
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* Pannellum overrides */
        #panorama { width: 100%; height: 100vh; background-color: #0f172a; direction: ltr !important; }
        .pnlm-container { background: transparent !important; }
        .pnlm-load-box { background: rgba(15, 23, 42, 0.8) !important; border-radius: 20px !important; backdrop-filter: blur(10px); }
        
        /* ═══════════════════════════════════
             GLOBAL BULLETPROOF HOTSPOT CSS
        ═══════════════════════════════════ */
        .pnlm-hotspot {
            cursor: pointer !important;
            display: block !important;
            visibility: visible !important;
            z-index: 9999 !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        /* Our Custom Hotspot Internal Content */
        .custom-hs-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 25px rgba(0,0,0,0.5), inset 0 0 10px rgba(255,255,255,0.3);
            width: 32px;
            height: 32px;
            overflow: hidden;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pnlm-hotspot:hover .custom-hs-wrapper {
            transform: scale(1.3) translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        }

        .custom-hs-wrapper.hs-employee {
            background-color: #6366f1;
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.9), 0 0 10px rgba(0,0,0,0.5);
        }

        .custom-hs-wrapper.hs-scene {
            background-color: #f59e0b;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.9), 0 0 10px rgba(0,0,0,0.5);
        }
        
        .custom-hs-wrapper.hs-trainer {
            background-color: #a855f7;
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.9), 0 0 10px rgba(0,0,0,0.5);
        }
        
        .custom-hs-wrapper.hs-schedule {
            background-color: #0ea5e9;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(14, 165, 233, 0.9), 0 0 10px rgba(0,0,0,0.5);
        }

        /* Hover Tooltip */
        .custom-hs-tooltip {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9);
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
        }

        .pnlm-hotspot:hover .custom-hs-tooltip {
            opacity: 1;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animations */
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse-highlight {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7); border-color: white; }
            50% { transform: scale(1.5); box-shadow: 0 0 20px 10px rgba(99, 102, 241, 0); border-color: #818cf8; }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); border-color: white; }
        }
        .hs-highlight {
            animation: pulse-highlight 1.5s ease-in-out 3;
            z-index: 1000 !important;
        }
        .animate-slide-in { animation: slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="h-full bg-slate-900 text-white overflow-hidden selection:bg-brand-500 selection:text-white">

    {{-- ═══════════════════════════════════════════
         PANORAMA CONTAINER
    ═══════════════════════════════════════════ --}}
    <div id="panorama"></div>

    {{-- Top Search Bar & Language Switcher container --}}
    <div class="fixed top-8 start-1/2 ltr:-translate-x-1/2 rtl:translate-x-1/2 w-full max-w-md z-40 px-6">
        <div class="glass rounded-2xl flex items-center px-4 py-2 shadow-2xl transition-all hover:bg-white/10 group focus-within:ring-2 focus-within:ring-brand-500/50">
            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-brand-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   id="employee-search" 
                   autocomplete="off"
                   placeholder="{{ __('Search for a colleague or trainer...') }}" 
                   oninput="handleSearch(this.value)"
                   class="w-full bg-transparent border-none focus:ring-0 text-sm font-medium text-white placeholder-slate-400 px-4 py-2">
            
            {{-- Search results dropdown --}}
            <div id="search-results" class="absolute top-[calc(100%+10px)] left-0 right-0 glass rounded-2xl overflow-hidden shadow-2xl hidden max-h-60 overflow-y-auto custom-scrollbar">
                {{-- Results injected here --}}
            </div>
        </div>
    </div>

    {{-- Language Switcher --}}
    <div class="fixed top-8 end-8 z-50 group">
        <button class="glass flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white rounded-xl shadow-lg border border-white/10 hover:bg-white/10 transition-colors">
            🌍 {{ strtoupper(app()->getLocale()) }}
        </button>
        <div class="absolute end-0 mt-2 w-32 bg-navy-900/90 backdrop-blur border border-white/10 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
            <div class="py-2">
                <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-brand-500 hover:text-white {{ app()->getLocale() == 'fr' ? 'font-bold bg-brand-500 text-white' : '' }}">🇫🇷 Français</a>
                <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-brand-500 hover:text-white {{ app()->getLocale() == 'en' ? 'font-bold bg-brand-500 text-white' : '' }}">🇬🇧 English</a>
                <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-brand-500 hover:text-white {{ app()->getLocale() == 'ar' ? 'font-bold bg-brand-500 text-white' : '' }}">🇲🇦 عربي</a>
            </div>
        </div>
    </div>

    {{-- Live Schedule Ambient Card --}}
    <div id="live-schedule-card" class="fixed top-24 start-1/2 ltr:-translate-x-1/2 rtl:translate-x-1/2 z-40 glass px-6 py-3.5 rounded-2xl shadow-[0_10px_30px_-5px_rgba(14,165,233,0.3)] border border-sky-400/30 backdrop-blur-md -translate-y-4 opacity-0 scale-95 transition-all duration-700 pointer-events-none flex items-center gap-4">
        <div class="relative flex h-3.5 w-3.5 shrink-0">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-sky-500"></span>
        </div>
        <div class="flex-1 min-w-[200px]">
            <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">{{ __('Live Now') }} &bull; <span id="ls-time"></span></p>
            <p id="ls-subject" class="text-base font-bold text-white leading-tight mt-1"></p>
            <p id="ls-trainer" class="text-xs font-medium text-slate-300 mt-1"></p>
        </div>
    </div>

    {{-- Sidebar (Scenes List) --}}
    <div id="sidebar" class="fixed start-6 top-1/2 -translate-y-1/2 w-72 max-h-[80vh] flex flex-col glass rounded-3xl shadow-2xl z-50 transition-all duration-500 overflow-hidden ltr:translate-x-0 rtl:translate-x-0">
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between bg-white/5">
            <h2 class="font-bold text-lg tracking-tight">{{ __('Explore Scenes') }}</h2>
            <button onclick="toggleSidebar()" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-slate-400 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-3 custom-scrollbar">
            @foreach($spaces as $space)
                <button 
                    onclick="loadScene('space_{{ $space->id }}')"
                    id="btn-space_{{ $space->id }}"
                    class="scene-btn w-full group flex flex-col gap-2 p-3 rounded-2xl hover:bg-white/10 transition-all duration-200 border border-transparent hover:border-white/10 text-left">
                    <div class="relative aspect-video w-full rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ $space->thumbnail_url ?: $space->photo_url }}" 
                             alt="{{ $space->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-2 left-3">
                            <span class="text-xs font-semibold text-white/90 uppercase tracking-widest">{{ $space->department?->name ?: 'Public' }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm group-hover:text-brand-500 transition-colors">{{ $space->name }}</h3>
                        <p class="text-[10px] text-slate-400 line-clamp-1 italic">{{ $space->description ?: 'No description available' }}</p>
                    </div>
                </button>
            @endforeach
        </div>

        <div class="p-4 border-t border-white/10 bg-white/5 space-y-2">
            @if(Auth::user()->hasRole('student') || Auth::user()->hasRole('trainer'))
                <button onclick="loadMyScheduleAPI()" class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold bg-sky-500/20 text-sky-400 border border-sky-500/20 hover:bg-sky-500/30 transition-all mb-2">
                    📅 {{ __('My Weekly Schedule') }}
                </button>
            @endif
            @if(Auth::user()->hasRole('employee'))
                <a href="{{ route('tour.profile') }}" class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold glass hover:bg-brand-500/20 hover:text-brand-400 border border-brand-500/20 transition-all">
                    {{ __('My Profile') }}
                </a>
            @endif
            @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold glass hover:bg-amber-500/20 hover:text-amber-400 border border-amber-500/20 transition-all">
                    {{ __('Admin Dashboard') }}
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-red-500/20 hover:text-red-400 border border-white/10 transition-all">
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar Toggle Button (Hidden when sidebar is open) --}}
    <button id="sidebar-toggle" onclick="toggleSidebar()" class="fixed start-6 top-1/2 -translate-y-1/2 glass p-4 rounded-2xl shadow-xl z-40 ltr:translate-x-[-120%] rtl:translate-x-[120%] transition-all duration-500 hover:bg-white/10">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Bottom Control Bar --}}
    <div class="fixed bottom-8 start-1/2 ltr:-translate-x-1/2 rtl:translate-x-1/2 flex items-center gap-4 glass px-6 py-4 rounded-3xl shadow-2xl z-40 animate-slide-in">
        <button onclick="viewer.setHfov(viewer.getHfov() - 10)" class="p-3 hover:bg-white/10 rounded-2xl transition-all" title="Zoom In">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
        </button>
        <button onclick="viewer.setHfov(viewer.getHfov() + 10)" class="p-3 hover:bg-white/10 rounded-2xl transition-all" title="Zoom Out">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
        </button>
        <div class="h-6 w-px bg-white/10 mx-2"></div>
        <button id="rotate-btn" onclick="toggleAutoRotate()" class="p-3 bg-brand-500 text-white rounded-2xl shadow-lg ring-4 ring-brand-500/20 transition-all" title="Auto Rotate">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
        <button id="fullscreen-btn" onclick="toggleFullscreen()" class="p-3 hover:bg-white/10 rounded-2xl transition-all" title="Fullscreen">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
        </button>
    </div>

    {{-- Info Card (Shows when employee or trainer hotspot clicked) --}}
    <div id="info-card" class="fixed top-1/2 start-1/2 ltr:-translate-x-1/2 rtl:translate-x-1/2 -translate-y-1/2 w-[400px] glass rounded-[32px] overflow-hidden shadow-[0_32px_128px_-16px_rgba(0,0,0,0.6)] z-[100] scale-0 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <button onclick="closeInfoCard()" class="absolute top-5 end-5 z-10 p-2 glass hover:bg-white/20 rounded-full transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="p-8">
            <div id="info-content"></div>
        </div>
    </div>
    
    {{-- Schedule Card (Shows when schedule hotspot clicked) --}}
    <div id="schedule-card" class="fixed top-1/2 start-1/2 ltr:-translate-x-1/2 rtl:translate-x-1/2 -translate-y-1/2 w-[600px] max-w-[90vw] glass rounded-[32px] overflow-hidden shadow-[0_32px_128px_-16px_rgba(0,0,0,0.6)] z-[100] scale-0 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <button onclick="closeScheduleCard()" class="absolute top-5 end-5 z-10 p-2 glass hover:bg-white/20 rounded-full transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="p-8">
            <h2 class="text-2xl font-bold tracking-tight text-white mb-6 uppercase flex items-center gap-3">
                <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('Room Schedule') }}
            </h2>
            <div id="schedule-content" class="max-h-[50vh] overflow-y-auto custom-scrollbar pe-2 space-y-3">
            </div>
        </div>
    </div>

    {{-- Mini Map / Floor Plan --}}
    <div id="minimap-container" class="fixed bottom-24 end-6 z-40 flex flex-col items-end gap-3 translate-y-0 transition-transform duration-300">
        {{-- Map Panel --}}
        <div id="minimap-panel" class="glass rounded-3xl p-4 shadow-2xl origin-bottom-right transition-all duration-300 scale-100 opacity-100">
            <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-3">
                <h3 class="font-bold text-sm tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    {{ __('Floor Plan') }}
                </h3>
                {{-- Floor Switcher --}}
                <div id="floor-switcher" class="flex gap-1 bg-white/5 p-1 rounded-lg">
                    <!-- Floor buttons will be injected here -->
                </div>
            </div>
            
            <div class="relative w-64 h-48 bg-navy-900/50 rounded-2xl border border-white/10 overflow-hidden flex items-center justify-center shadow-inner">
                {{-- SVG Map Container --}}
                <svg id="svg-map" class="w-full h-full" viewBox="0 0 200 150">
                    <!-- SVG lines and dots will be injected here -->
                </svg>
                
                {{-- Loading state --}}
                <div id="minimap-loading" class="absolute inset-0 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm z-10 transition-opacity duration-300">
                    <svg class="animate-spin h-6 w-6 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>
        </div>
        
        {{-- Toggle Map Button --}}
        <button onclick="toggleMinimap()" class="glass p-3 rounded-full hover:bg-white/10 transition-colors shadow-lg group" title="Toggle Map">
            <svg class="w-6 h-6 text-white group-hover:text-brand-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </button>
    </div>

    {{-- ═══════════════════════════════════════════
         PANNELLUM DATA & LOGIC
    ═══════════════════════════════════════════ --}}
    <script>
        // ─── Custom DOM Generator for Hotspots ──────────────────────
        function renderCustomHotspot(hotSpotDiv, args) {
            hotSpotDiv.classList.add('custom-tooltip-base');
            
            // Build the inner HTML directly to bypass library quirks
            let styleClass = '';
            let photoHtml = '';

            if (args.type === 'scene') {
                styleClass = 'hs-scene';
                photoHtml = `<svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.1 1.1"/></svg>`;
            } else if (args.type === 'schedule') {
                styleClass = 'hs-schedule';
                photoHtml = `<svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`;
            } else if (args.type === 'employee') {
                styleClass = 'hs-employee';
                if (args.photo) {
                    photoHtml = `<img src="${args.photo}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'"/>`;
                }
            } else if (args.type === 'trainer') {
                styleClass = 'hs-trainer';
                if (args.photo) {
                    photoHtml = `<img src="${args.photo}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'"/>`;
                }
            }

            hotSpotDiv.innerHTML = `
                <div class="custom-hs-wrapper ${styleClass}">
                    ${photoHtml}
                </div>
                <div class="custom-hs-tooltip">${args.text}</div>
            `;

            // ─── Direct DOM Event Binding ───
            if (args.type === 'employee' && args.employeeData) {
                hotSpotDiv.addEventListener('click', function(e) { showEmployeeInfo(args.employeeData); });
                hotSpotDiv.addEventListener('touchstart', function(e) { showEmployeeInfo(args.employeeData); }, {passive: true});
            } else if (args.type === 'scene' && args.targetSceneId) {
                hotSpotDiv.addEventListener('click', function(e) { loadScene(args.targetSceneId); });
                hotSpotDiv.addEventListener('touchstart', function(e) { loadScene(args.targetSceneId); }, {passive: true});
            } else if (args.type === 'trainer' && args.trainerId) {
                hotSpotDiv.addEventListener('click', function(e) { loadTrainerInfoAPI(args.trainerId); });
                hotSpotDiv.addEventListener('touchstart', function(e) { loadTrainerInfoAPI(args.trainerId); }, {passive: true});
            } else if (args.type === 'schedule' && args.spaceId) {
                hotSpotDiv.addEventListener('click', function(e) { loadScheduleInfoAPI(args.spaceId); });
                hotSpotDiv.addEventListener('touchstart', function(e) { loadScheduleInfoAPI(args.spaceId); }, {passive: true});
            }
        }

        const scenesData = {
            @foreach($spaces as $space)
            "space_{{ $space->id }}": {
                "title": "{{ $space->name }}",
                "type": "equirectangular",
                "panorama": "{{ $space->photo_url }}",
                "autoLoad": true,
                "hotSpots": [
                    @foreach($space->hotspots as $hs)
                    {
                        "pitch": {{ $hs->pitch }},
                        "yaw": {{ $hs->yaw }},
                        "type": "info",
                        "createTooltipFunc": renderCustomHotspot,
                        "createTooltipArgs": {
                            "type": "{{ $hs->type }}",
                            "text": {!! json_encode($hs->label ?? ($hs->employee ? $hs->employee->full_name : ($hs->trainer ? $hs->trainer->first_name.' '.$hs->trainer->last_name : ($hs->targetScene ? $hs->targetScene->name : 'Room Schedule')))) !!},
                            "photo": "{{ $hs->employee && $hs->employee->photo_url ? $hs->employee->photo_url : ($hs->type === 'trainer' && $hs->trainer && $hs->trainer->photo ? asset('storage/'.$hs->trainer->photo) : '') }}"
                            @if($hs->type === 'scene' && $hs->target_scene_id)
                            , "targetSceneId": "space_{{ $hs->target_scene_id }}"
                            @elseif($hs->type === 'employee' && $hs->employee)
                            , "employeeData": {
                                "name": "{{ addslashes($hs->employee->full_name) }}",
                                "matricule": "{{ $hs->employee->matricule }}",
                                "role": "{{ addslashes($hs->employee->job_title) }}",
                                "dept": "{{ addslashes($hs->employee->department?->name) }}",
                                "email": "{{ $hs->employee->email }}",
                                "phone": "{{ $hs->employee->phone }}",
                                "qr": "{{ $hs->employee->qr_code_url }}",
                                "photo": "{{ $hs->employee->photo_url ?: 'https://ui-avatars.com/api/?name='.urlencode($hs->employee->full_name).'&background=6366f1&color=fff' }}"
                            }
                            @elseif($hs->type === 'trainer' && $hs->trainer_id)
                            , "trainerId": "{{ $hs->trainer_id }}"
                            @elseif($hs->type === 'schedule')
                            , "spaceId": "{{ $hs->space_id }}"
                            @endif
                        }
                    },
                    @endforeach
                ]
            },
            @endforeach
        };

        const firstSceneId = "{{ $firstSpace ? 'space_'.$firstSpace->id : '' }}";
        
        let viewer = pannellum.viewer('panorama', {
            "default": {
                "firstScene": firstSceneId,
                "autoRotate": -2,
                "sceneFadeDuration": 1000,
                "showControls": false,
                "mouseZoom": "fullscreenonly",
                "autoRotateInactivityDelay": 3000
            },
            "scenes": scenesData
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggle  = document.getElementById('sidebar-toggle');
            
            const isRtl = document.documentElement.dir === 'rtl';
            
            if (sidebar.classList.contains('ltr:translate-x-0') || sidebar.classList.contains('rtl:translate-x-0')) {
                sidebar.classList.replace('ltr:translate-x-0', 'ltr:-translate-x-[120%]');
                sidebar.classList.replace('rtl:translate-x-0', 'rtl:translate-x-[120%]');
                
                toggle.classList.replace('ltr:translate-x-[-120%]', 'ltr:translate-x-0');
                toggle.classList.replace('rtl:translate-x-[120%]', 'rtl:translate-x-0');
            } else {
                sidebar.classList.replace('ltr:-translate-x-[120%]', 'ltr:translate-x-0');
                sidebar.classList.replace('rtl:translate-x-[120%]', 'rtl:translate-x-0');
                
                toggle.classList.replace('ltr:translate-x-0', 'ltr:translate-x-[-120%]');
                toggle.classList.replace('rtl:translate-x-0', 'rtl:translate-x-[120%]');
            }
        }

        // Load Scene
        function loadScene(sceneId) {
            viewer.loadScene(sceneId);
            updateActiveButton(sceneId);
        }

        // Update Sidebar Active State
        function updateActiveButton(sceneId) {
            document.querySelectorAll('.scene-btn').forEach(btn => {
                btn.classList.remove('bg-white/20', 'border-brand-500/50', 'ring-2', 'ring-brand-500/20');
            });
            const active = document.getElementById('btn-' + sceneId);
            if (active) {
                active.classList.add('bg-white/20', 'border-brand-500/50', 'ring-2', 'ring-brand-500/20');
            }
        }

        // Auto Rotate
        function toggleAutoRotate() {
            const btn = document.getElementById('rotate-btn');
            const isRotating = viewer.getConfig().autoRotate;
            
            if (isRotating) {
                viewer.stopAutoRotate();
                btn.classList.replace('bg-brand-500', 'bg-white/5');
            } else {
                viewer.startAutoRotate();
                btn.classList.replace('bg-white/5', 'bg-brand-500');
            }
        }

        // Fullscreen
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Employee Info
        function showEmployeeInfo(data) {
            renderInfoCard(data, 'brand');
        }

        function closeInfoCard() {
            const card = document.getElementById('info-card');
            card.classList.add('scale-0', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
        }
        
        function closeScheduleCard() {
            const card = document.getElementById('schedule-card');
            card.classList.add('scale-0', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
        }
        
        // ─── AJAX FETCH TRAINERS & SCHEDULES ──────────────────────

        function renderInfoCard(data, theme = 'brand') {
            const card = document.getElementById('info-card');
            const content = document.getElementById('info-content');
            
            let pulseColor = theme === 'brand' ? 'brand-500' : 'purple-500';
            let textColor = theme === 'brand' ? 'brand-400' : 'purple-400';
            
            content.innerHTML = `
                <div class="flex flex-col items-center">
                    <div class="relative mb-6 text-center">
                        <div class="absolute inset-x-0 -top-4 -bottom-4 bg-${pulseColor} blur-3xl opacity-10 animate-pulse"></div>
                        <img src="${data.photo}" class="w-32 h-32 rounded-[2.5rem] object-cover border-4 border-white/20 relative shadow-2xl mx-auto" alt="${data.name}"/>
                        <div class="relative mt-5">
                            <h2 class="text-2xl font-bold tracking-tight text-white mb-1 uppercase">${data.name}</h2>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-${pulseColor}/20 border border-${pulseColor}/30">
                                <span class="text-[10px] font-bold text-${textColor} uppercase tracking-widest">${data.role || data.specialty || 'Personnel'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full grid grid-cols-2 gap-3 mb-6">
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">${data.dept ? 'Department' : ''}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-200">${data.dept || ''}</span>
                            </div>
                        </div>
                        ${data.matricule ? `
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Matricule</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-slate-200">${data.matricule}</span>
                            </div>
                        </div>` : ''}
                        
                        <a href="mailto:${data.email}" class="p-3 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all text-left ${!data.matricule ? 'col-span-2' : ''}">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Email</p>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-semibold text-slate-200 truncate">${data.email || '—'}</span>
                            </div>
                        </a>
                        ${data.phone ? `
                        <a href="tel:${data.phone}" class="p-3 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all text-left col-span-2">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Phone</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-200">${data.phone}</span>
                            </div>
                        </a>` : ''}
                    </div>
                </div>
            `;
            
            card.classList.remove('scale-0', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }

        function loadTrainerInfoAPI(trainerId) {
            const content = document.getElementById('info-content');
            content.innerHTML = `<div class="text-center py-10"><svg class="animate-spin h-8 w-8 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="mt-4 text-xs font-medium text-slate-400">Loading trainer profile...</p></div>`;
            
            const card = document.getElementById('info-card');
            card.classList.remove('scale-0', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');

            fetch(`/api/trainer/${trainerId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async res => {
                    if (!res.ok) {
                        throw new Error(await res.text() || 'Network error');
                    }
                    return res.json();
                })
                .then(data => {
                    renderInfoCard(data, 'purple');
                })
                .catch(err => {
                    console.error(err);
                    content.innerHTML = `<p class="text-red-400 text-center text-sm py-10">Failed to load trainer info.</p>`;
                });
        }

        function loadScheduleInfoAPI(spaceId) {
            const content = document.getElementById('schedule-content');
            content.innerHTML = `<div class="text-center py-10"><svg class="animate-spin h-8 w-8 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;
            
            const card = document.getElementById('schedule-card');
            card.classList.remove('scale-0', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');

            fetch(`/api/space/${spaceId}/schedules`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async res => {
                    if (!res.ok) {
                        throw new Error(await res.text() || 'Network error');
                    }
                    return res.json();
                })
                .then(schedules => {
                    if (!schedules || !schedules.length) {
                        content.innerHTML = `<div class="text-center py-8"><p class="text-slate-400 text-sm">Aucun cours planifié dans cette salle.</p></div>`;
                        return;
                    }
                    
                    let html = '';
                    let isTrainerUser = {{ Auth::user() && Auth::user()->hasRole('trainer') ? 'true' : 'false' }};
                    let currentTrainerId = {{ Auth::user() && Auth::user()->hasRole('trainer') && Auth::user()->trainer ? Auth::user()->trainer->id : 'null' }};
                    
                    schedules.forEach(s => {
                        let st = s.start_time ? s.start_time.substring(0, 5) : '--:--';
                        let et = s.end_time ? s.end_time.substring(0, 5) : '--:--';
                        let trainerName = s.trainer ? (s.trainer.first_name + ' ' + s.trainer.last_name) : 'Formateur inconnu';
                        let groupName = s.group ? s.group.name : 'Groupe inconnu';
                        
                        let isMySession = isTrainerUser && s.trainer_id == currentTrainerId;
                        let tag = isMySession ? 'a' : 'div';
                        let href = isMySession ? `href="/trainer/schedule/${s.id}/attendances"` : '';
                        let hoverStyle = isMySession ? 'hover:bg-white/20 cursor-pointer border-sky-500/20 hover:border-sky-500/50 block' : 'hover:bg-white/10 border-white/10 flex';
                        
                        // We use Flex inside since wrapper is block if it's an anchor to maintain styling 
                        html += `
                        <${tag} ${href} class="${hoverStyle} items-center justify-between p-4 rounded-2xl bg-white/5 border transition-colors mb-2">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex flex-col min-w-[70px]">
                                    <span class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-1">${s.day_of_week || '-'}</span>
                                    <span class="text-lg font-bold text-white">${st} - ${et}</span>
                                </div>
                                <div class="flex-1 px-4 sm:px-6 overflow-hidden">
                                    <p class="text-base font-semibold text-white truncate" title="${s.subject || ''}">${s.subject || 'Matière inconnue'}</p>
                                    <p class="text-xs text-slate-400 mt-1 truncate">Formateur: <span class="text-slate-200">${trainerName}</span></p>
                                </div>
                                <div class="text-right shrink-0 flex items-center justify-end gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-white/10 border border-white/20 text-white shadow-sm uppercase">
                                        ${groupName}
                                    </span>
                                    ${isMySession ? `<svg class="w-5 h-5 text-sky-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>` : ''}
                                </div>
                            </div>
                        </${tag}>`;
                    });
                    content.innerHTML = html;
                })
                .catch(err => {
                    console.error("Schedule Fetch Error API: ", err);
                    content.innerHTML = `<p class="text-red-400 text-center text-sm py-10">An error occurred while loading schedule data.<br><span class="text-xs text-white/50">` + err.message.substring(0,60) + `</span></p>`;
                });
        }

        function loadMyScheduleAPI() {
            const content = document.getElementById('schedule-content');
            content.innerHTML = `<div class="text-center py-10"><svg class="animate-spin h-8 w-8 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;
            
            const card = document.getElementById('schedule-card');
            const title = card.querySelector('h2');
            title.innerHTML = `<svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ __('Weekly Schedule') }}`;

            card.classList.remove('scale-0', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');

            fetch(`/api/my-schedule`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async res => {
                    if (!res.ok) {
                        throw new Error(await res.text() || 'Network error');
                    }
                    return res.json();
                })
                .then(schedules => {
                    if (!schedules || !schedules.length) {
                        content.innerHTML = `<div class="text-center py-8"><p class="text-slate-400 text-sm">Aucun cours planifié.</p></div>`;
                        return;
                    }
                    
                    let html = '';
                    let isTrainer = {{ Auth::user() && Auth::user()->hasRole('trainer') ? 'true' : 'false' }};
                    
                    schedules.forEach(s => {
                        let st = s.start_time ? s.start_time.substring(0, 5) : '--:--';
                        let et = s.end_time ? s.end_time.substring(0, 5) : '--:--';
                        let trainerName = s.trainer ? (s.trainer.first_name + ' ' + s.trainer.last_name) : 'Formateur inconnu';
                        let groupName = s.group ? s.group.name : 'Groupe inconnu';
                        let spaceName = s.space ? s.space.name : 'Salle inconnue';
                        
                        let tag = isTrainer ? 'a' : 'div';
                        let href = isTrainer ? `href="/trainer/schedule/${s.id}/attendances"` : '';
                        let hoverStyle = isTrainer ? 'hover:bg-white/20 cursor-pointer border-sky-500/20 hover:border-sky-500/50' : 'hover:bg-white/10 border-white/10';
                        
                        html += `
                        <${tag} ${href} class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border transition-all ${hoverStyle} mb-2 block">
                            <div class="flex flex-col min-w-[70px]">
                                <span class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-1">${s.day_of_week || '-'}</span>
                                <span class="text-lg font-bold text-white">${st} - ${et}</span>
                            </div>
                            <div class="flex-1 px-4 sm:px-6 overflow-hidden">
                                <p class="text-base font-semibold text-white truncate" title="${s.subject || ''}">${s.subject || 'Matière inconnue'}</p>
                                <p class="text-xs text-slate-400 mt-1 truncate">Salle: <span class="text-slate-200">${spaceName}</span> | ${s.trainer ? 'Formateur: ' + trainerName : 'Groupe: ' + groupName}</p>
                            </div>
                            ${isTrainer ? `<div class="shrink-0 ms-2 text-sky-400/50"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></div>` : ''}
                        </${tag}>`;
                    });
                    content.innerHTML = html;
                })
                .catch(err => {
                    console.error("Schedule Fetch Error API: ", err);
                    content.innerHTML = `<p class="text-red-400 text-center text-sm py-10">An error occurred while loading schedule data.</p>`;
                });
        }

        // ─── LIVE SCHEDULE AMBIENT LOGIC ─────────────────────────────
        function checkLiveSchedule(spaceId) {
            const card = document.getElementById('live-schedule-card');
            if(!card) return;
            
            fetch(`/api/space/${spaceId}/live`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.active) {
                    document.getElementById('ls-subject').textContent = data.subject;
                    document.getElementById('ls-trainer').textContent = data.trainer_name + ' • ' + data.group_name;
                    document.getElementById('ls-time').textContent = data.time_remaining;
                    
                    card.classList.remove('-translate-y-4', 'scale-95', 'opacity-0');
                    card.classList.add('translate-y-0', 'scale-100', 'opacity-100');
                } else {
                    card.classList.remove('translate-y-0', 'scale-100', 'opacity-100');
                    card.classList.add('-translate-y-4', 'scale-95', 'opacity-0');
                }
            })
            .catch(err => {
                card.classList.remove('translate-y-0', 'scale-100', 'opacity-100');
                card.classList.add('-translate-y-4', 'scale-95', 'opacity-0');
            });
        }

        // Initialize active button and minimap
        window.addEventListener('load', () => {
            updateActiveButton(firstSceneId);
            
            // Set initial active space ID for minimap
            const initialMatches = firstSceneId.match(/space_(\d+)/);
            if (initialMatches) {
                activeSpaceId = parseInt(initialMatches[1]);
                checkLiveSchedule(activeSpaceId);
            }
            
            loadMinimapData();
        });

        // ─── Minimap Logic ──────────────────────────────────────────
        let mapData = null;
        let currentFloor = 1;
        let activeSpaceId = null;

        function toggleMinimap() {
            const panel = document.getElementById('minimap-panel');
            if (panel.classList.contains('scale-100')) {
                panel.classList.replace('scale-100', 'scale-0');
                panel.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => panel.style.display = 'none', 300);
            } else {
                panel.style.display = 'block';
                void panel.offsetWidth; // Reflow
                panel.classList.replace('scale-0', 'scale-100');
                panel.classList.replace('opacity-0', 'opacity-100');
            }
        }
        
        function loadMinimapData() {
            fetch('/api/spaces/map-data')
                .then(res => res.json())
                .then(data => {
                    mapData = data;
                    const loading = document.getElementById('minimap-loading');
                    loading.style.opacity = '0';
                    setTimeout(() => loading.style.display = 'none', 300);
                    
                    renderFloorSwitcher();
                    
                    // Determine which floor to show initially (based on active space or default to first available)
                    let targetFloor = null;
                    if (activeSpaceId) {
                        targetFloor = Object.keys(mapData).find(f => mapData[f].some(s => s.id === activeSpaceId));
                    }
                    if (!targetFloor && Object.keys(mapData).length > 0) {
                        targetFloor = Object.keys(mapData)[0];
                    }
                    
                    if (targetFloor) {
                        currentFloor = targetFloor;
                        renderFloorMap(currentFloor);
                        renderFloorSwitcher(); // Re-render to highlight active floor
                    }
                })
                .catch(err => console.error("Error loading minimap data:", err));
        }
        
        function renderFloorSwitcher() {
            const switcher = document.getElementById('floor-switcher');
            if (!mapData || Object.keys(mapData).length <= 1) {
                switcher.style.display = 'none';
                return;
            }
            switcher.style.display = 'flex';
            
            let html = '';
            Object.keys(mapData).forEach(floorLevel => {
                const isActive = floorLevel == currentFloor;
                html += `<button onclick="switchFloor(${floorLevel})" class="px-2.5 py-1 text-[10px] font-bold rounded-md transition-colors ${isActive ? 'bg-brand-500 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/10'}">F${floorLevel}</button>`;
            });
            switcher.innerHTML = html;
        }

        function switchFloor(floorLevel) {
            currentFloor = floorLevel;
            renderFloorSwitcher();
            renderFloorMap(floorLevel);
        }
        
        function renderFloorMap(floorLevel) {
            const svg = document.getElementById('svg-map');
            const spaces = mapData[floorLevel] || [];
            
            let html = '';
            const cx = 100, cy = 75; // Canvas center
            const radius = 50;
            const count = spaces.length;
            
            // Draw connecting loop line
            if (count > 1) {
                html += `<circle cx="${cx}" cy="${cy}" r="${radius}" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" stroke-dasharray="4 4" />`;
            }

            spaces.forEach((space, i) => {
                let x = cx, y = cy;
                if (count > 1) {
                    const angle = (i / count) * Math.PI * 2 - Math.PI / 2;
                    x = cx + Math.cos(angle) * radius;
                    y = cy + Math.sin(angle) * radius;
                }
                
                const isActive = activeSpaceId == space.id;
                const dotColor = isActive ? '#6366f1' : '#cbd5e1'; // brand-500 or slate-300
                const dotSize = isActive ? 7 : 4;
                
                if (isActive) {
                    // Pulsing rings
                    html += `<circle cx="${x}" cy="${y}" r="14" fill="none" stroke="${dotColor}" stroke-width="2" class="opacity-50" style="animation: pulse-highlight 2s infinite;" />`;
                    html += `<circle cx="${x}" cy="${y}" r="22" fill="none" stroke="${dotColor}" stroke-width="1" class="opacity-30" style="animation: pulse-highlight 2s infinite; animation-delay: 0.5s;" />`;
                    html += `<circle cx="${x}" cy="${y}" r="9" fill="${dotColor}" fill-opacity="0.3" class="animate-pulse" />`;
                }

                html += `
                <g class="cursor-pointer group" onclick="loadScene('space_${space.id}')" style="cursor: pointer;">
                    <circle cx="${x}" cy="${y}" r="${dotSize}" fill="${dotColor}" class="transition-all duration-300 group-hover:r-[8px] group-hover:fill-[#818cf8]" />
                    <text x="${x}" y="${y + (isActive ? 18 : 14)}" font-size="8" fill="rgba(255,255,255,${isActive ? '0.9' : '0.6'})" text-anchor="middle" class="transition-all group-hover:fill-white font-semibold drop-shadow-lg pointer-events-none">${space.name.length > 20 ? space.name.substring(0, 18) + '...' : space.name}</text>
                </g>`;
            });
            
            svg.innerHTML = html;
        }

        // Override loadScene to sync minimap
        const originalLoadScene = loadScene;
        loadScene = function(sceneId) {
            originalLoadScene(sceneId);
            
            // Update active space in minimap
            const spaceIdMatches = sceneId.match(/space_(\d+)/);
            if (spaceIdMatches) {
                activeSpaceId = parseInt(spaceIdMatches[1]);
                
                // Only update minimap if data is loaded
                if (mapData) {
                    let foundFloor = Object.keys(mapData).find(f => mapData[f].some(s => s.id === activeSpaceId));
                    if(foundFloor && foundFloor != currentFloor) {
                        currentFloor = foundFloor;
                        renderFloorSwitcher();
                    }
                    renderFloorMap(currentFloor);
                }
                
                checkLiveSchedule(activeSpaceId);
            }
        };

        // Pannellum native scene change listener (fires when clicking hotspots that change scene)
        viewer.on('scenechange', function(sceneId) {
            // Already handled in loadScene if triggered programmatically
            // But if triggered inherently by Pannellum info/scene hotspots, we need this
            updateActiveButton(sceneId);
            
            const spaceIdMatches = sceneId.match(/space_(\d+)/);
            if (spaceIdMatches) {
                activeSpaceId = parseInt(spaceIdMatches[1]);
                if (mapData) {
                    let foundFloor = Object.keys(mapData).find(f => mapData[f].some(s => s.id === activeSpaceId));
                    if(foundFloor && foundFloor != currentFloor) {
                        currentFloor = foundFloor;
                        renderFloorSwitcher();
                    }
                    renderFloorMap(currentFloor);
                }
                
                checkLiveSchedule(activeSpaceId);
            }
        });

        // ─── Search Logic ───────────────────────────────────────────
        function handleSearch(query) {
            const resultsBox = document.getElementById('search-results');
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            const results = [];
            Object.entries(scenesData).forEach(([sceneId, scene]) => {
                scene.hotSpots.forEach(hs => {
                    if (hs.createTooltipArgs && hs.createTooltipArgs.employeeData && hs.createTooltipArgs.employeeData.name.toLowerCase().includes(query.toLowerCase())) {
                        results.push({
                            sceneId, sceneTitle: scene.title, p: hs.pitch, y: hs.yaw,
                            name: hs.createTooltipArgs.employeeData.name, role: hs.createTooltipArgs.employeeData.role
                        });
                    }
                });
            });

            if (results.length > 0) {
                resultsBox.innerHTML = results.map((res, i) => `
                    <button onclick="panToHotspot('${res.sceneId}', ${res.p}, ${res.y}, ${i})" 
                            class="w-full flex items-center gap-4 px-5 py-3 hover:bg-white/10 transition-colors border-b border-white/5 last:border-none text-left">
                        <div class="w-8 h-8 rounded-full bg-brand-500/20 flex items-center justify-center text-brand-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">${res.name}</p>
                            <p class="text-[10px] text-slate-400 font-medium">${res.role} • ${res.sceneTitle}</p>
                        </div>
                    </button>
                `).join('');
                resultsBox.classList.remove('hidden');
            } else {
                resultsBox.classList.add('hidden');
            }
        }

        function panToHotspot(sceneId, pitch, yaw, index) {
            document.getElementById('search-results').classList.add('hidden');
            document.getElementById('employee-search').value = '';

            const performPan = () => {
                viewer.lookAt(pitch, yaw, viewer.getHfov(), 2000);
            };

            if (viewer.getScene() !== sceneId) {
                viewer.loadScene(sceneId, pitch, yaw, viewer.getHfov());
                setTimeout(performPan, 1200);
            } else {
                performPan();
            }
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#employee-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    </script>
</body>
</html>