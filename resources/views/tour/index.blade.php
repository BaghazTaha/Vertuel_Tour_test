{{-- resources/views/tour/index.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full">
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
        #panorama { width: 100%; height: 100vh; background-color: #0f172a; }
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
            /* No transition here! This prevents the 'gliding' effect when panning. */
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

    {{-- Top Search Bar --}}
    <div class="fixed top-8 left-1/2 -translate-x-1/2 w-full max-w-md z-40 px-6">
        <div class="glass rounded-2xl flex items-center px-4 py-2 shadow-2xl transition-all hover:bg-white/10 group focus-within:ring-2 focus-within:ring-brand-500/50">
            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-brand-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   id="employee-search" 
                   autocomplete="off"
                   placeholder="Search for a colleague..." 
                   oninput="handleSearch(this.value)"
                   class="w-full bg-transparent border-none focus:ring-0 text-sm font-medium text-white placeholder-slate-400 px-4 py-2">
            
            {{-- Search results dropdown --}}
            <div id="search-results" class="absolute top-[calc(100%+10px)] left-0 right-0 glass rounded-2xl overflow-hidden shadow-2xl hidden max-h-60 overflow-y-auto custom-scrollbar">
                {{-- Results injected here --}}
            </div>
        </div>
    </div>

    {{-- Sidebar (Scenes List) --}}
    <div id="sidebar" class="fixed left-6 top-1/2 -translate-y-1/2 w-72 max-h-[80vh] flex flex-col glass rounded-3xl shadow-2xl z-50 transition-all duration-500 overflow-hidden translate-x-0">
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between bg-white/5">
            <h2 class="font-bold text-lg tracking-tight">Explore Scenes</h2>
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
            @if(Auth::user()->hasRole('employee'))
                <a href="{{ route('tour.profile') }}" class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold glass hover:bg-brand-500/20 hover:text-brand-400 border border-brand-500/20 transition-all">
                    My Profile
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-red-500/20 hover:text-red-400 border border-white/10 transition-all">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar Toggle Button (Hidden when sidebar is open) --}}
    <button id="sidebar-toggle" onclick="toggleSidebar()" class="fixed left-6 top-1/2 -translate-y-1/2 glass p-4 rounded-2xl shadow-xl z-40 translate-x-[-120%] transition-all duration-500 hover:bg-white/10">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Bottom Control Bar --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-4 glass px-6 py-4 rounded-3xl shadow-2xl z-40 animate-slide-in">
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

    {{-- Info Card (Shows when employee hotspot clicked) --}}
    <div id="info-card" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] glass rounded-[32px] overflow-hidden shadow-[0_32px_128px_-16px_rgba(0,0,0,0.6)] z-[100] scale-0 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <button onclick="closeInfoCard()" class="absolute top-5 right-5 z-10 p-2 glass hover:bg-white/20 rounded-full transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="p-8">
            <div id="info-content"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         PANNELLUM DATA & LOGIC
    ═══════════════════════════════════════════ --}}
    <script>
        // ─── Custom DOM Generator for Hotspots ──────────────────────
        function renderCustomHotspot(hotSpotDiv, args) {
            hotSpotDiv.classList.add('custom-tooltip-base');
            
            // Build the inner HTML directly to bypass library quirks
            const styleClass = args.type === 'scene' ? 'hs-scene' : 'hs-employee';
            
            let photoHtml = '';
            if (args.type === 'employee' && args.photo) {
                photoHtml = `<img src="${args.photo}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'"/>`;
            } else if (args.type === 'scene') {
                photoHtml = `<svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.1 1.1"/></svg>`;
            }

            hotSpotDiv.innerHTML = `
                <div class="custom-hs-wrapper ${styleClass}">
                    ${photoHtml}
                </div>
                <div class="custom-hs-tooltip">${args.text}</div>
            `;

            // ─── Direct DOM Event Binding ───
            // Guaranteed to fire, bypassing any Pannellum internal event propagation issues.
            if (args.type === 'employee' && args.employeeData) {
                hotSpotDiv.addEventListener('click', function(e) {
                    showEmployeeInfo(args.employeeData);
                });
                // Fallback for mobile touch issues
                hotSpotDiv.addEventListener('touchstart', function(e) {
                    showEmployeeInfo(args.employeeData);
                }, {passive: true});
            } else if (args.type === 'scene' && args.targetSceneId) {
                hotSpotDiv.addEventListener('click', function(e) {
                    loadScene(args.targetSceneId);
                });
                hotSpotDiv.addEventListener('touchstart', function(e) {
                    loadScene(args.targetSceneId);
                }, {passive: true});
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
                        "type": "info", // Force type info so we intercept rendering fully
                        "createTooltipFunc": renderCustomHotspot,
                        "createTooltipArgs": {
                            "type": "{{ $hs->type }}",
                            "text": "{{ addslashes($hs->label ?? ($hs->employee ? $hs->employee->full_name : ($hs->targetScene ? $hs->targetScene->name : ''))) }}",
                            "photo": "{{ $hs->employee && $hs->employee->photo_url ? $hs->employee->photo_url : '' }}"
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

        // Debug: Log hotspots after load
        console.log("Viewer Initialized. Scenes Data:", scenesData);
        viewer.on('load', () => {
            const currentScene = viewer.getScene();
            console.log("Current Scene:", currentScene, "Hotspots:", scenesData[currentScene]?.hotSpots);
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggle  = document.getElementById('sidebar-toggle');
            
            if (sidebar.classList.contains('translate-x-0')) {
                sidebar.classList.replace('translate-x-0', '-translate-x-[120%]');
                toggle.classList.replace('translate-x-[-120%]', 'translate-x-0');
            } else {
                sidebar.classList.replace('-translate-x-[120%]', 'translate-x-0');
                toggle.classList.replace('translate-x-0', 'translate-x-[-120%]');
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
            const card = document.getElementById('info-card');
            const content = document.getElementById('info-content');
            
            content.innerHTML = `
                <div class="flex flex-col items-center">
                    {{-- Header with Photo and Name --}}
                    <div class="relative mb-6 text-center">
                        <div class="absolute inset-x-0 -top-4 -bottom-4 bg-brand-500 blur-3xl opacity-10 animate-pulse"></div>
                        <img src="${data.photo}" class="w-32 h-32 rounded-[2.5rem] object-cover border-4 border-white/20 relative shadow-2xl mx-auto" alt="${data.name}"/>
                        <div class="relative mt-5">
                            <h2 class="text-2xl font-bold tracking-tight text-white mb-1 uppercase">${data.name}</h2>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/20 border border-brand-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">${data.role}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Data Grid --}}
                    <div class="w-full grid grid-cols-2 gap-3 mb-6">
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10 group hover:bg-white/10 transition-all">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Department</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="text-xs font-semibold text-slate-200">${data.dept || 'Corporate'}</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10 group hover:bg-white/10 transition-all">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Matricule</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span class="text-xs font-mono font-bold text-slate-200">${data.matricule}</span>
                            </div>
                        </div>
                        <a href="mailto:${data.email}" class="p-3 rounded-2xl bg-white/5 border border-white/10 group hover:bg-brand-500/10 hover:border-brand-500/30 transition-all text-left">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Email</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[11px] font-semibold text-slate-200 truncate">${data.email}</span>
                            </div>
                        </a>
                        <a href="tel:${data.phone}" class="p-3 rounded-2xl bg-white/5 border border-white/10 group hover:bg-brand-500/10 hover:border-brand-500/30 transition-all text-left">
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5">Phone</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1c-8.284 0-15-6.716-15-15V5z"/></svg>
                                <span class="text-xs font-semibold text-slate-200">${data.phone || '—'}</span>
                            </div>
                        </a>
                    </div>

                    {{-- QR Code Section --}}
                    ${data.qr ? `
                    <div class="w-full p-6 rounded-[2rem] bg-indigo-50/5 border border-white/5 flex flex-col items-center gap-4 group">
                        <div class="relative p-3 bg-white rounded-2xl">
                            <img src="${data.qr}" class="w-24 h-24" alt="QR Code"/>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mb-3">Scan to View Public Profile</p>
                            <a href="${data.qr}" download="qr_${data.matricule}.png" class="inline-flex items-center gap-2 text-[10px] font-bold text-brand-400 hover:text-white transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                DOWNLOAD QR CODE
                            </a>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            card.classList.remove('scale-0', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }

        function closeInfoCard() {
            const card = document.getElementById('info-card');
            card.classList.add('scale-0', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
        }

        // Initialize active button
        window.addEventListener('load', () => {
            updateActiveButton(firstSceneId);
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
                            sceneId,
                            sceneTitle: scene.title,
                            p: hs.pitch,
                            y: hs.yaw,
                            name: hs.createTooltipArgs.employeeData.name,
                            role: hs.createTooltipArgs.employeeData.role,
                            hs: hs
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
            // Hide results
            document.getElementById('search-results').classList.add('hidden');
            document.getElementById('employee-search').value = '';

            const performPan = () => {
                viewer.lookAt(pitch, yaw, viewer.getHfov(), 2000);
                
                // Highlight the hotspot visually by searching in DOM after a bit
                setTimeout(() => {
                    // This is a bit hacky as Pannellum doesn't give us the ID, 
                    // but we can find the one closest to the target pitch/yaw if needed.
                    // Or just highlight all ones that match the type to be sure.
                    const hsElements = document.querySelectorAll('.pnlm-hotspot.hs-employee');
                    hsElements.forEach(el => {
                        // Small heuristic to find the right one
                        el.classList.add('hs-highlight');
                        setTimeout(() => el.classList.remove('hs-highlight'), 5000);
                    });
                }, 1000);
            };

            // Switch scene if needed
            if (viewer.getScene() !== sceneId) {
                viewer.loadScene(sceneId, pitch, yaw, viewer.getHfov());
                // After scene change, lookAt is often reset or handled by loadScene parameters
                setTimeout(performPan, 1200);
            } else {
                performPan();
            }
        }

        // Close search on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#employee-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    </script>
</body>
</html>