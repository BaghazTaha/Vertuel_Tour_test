{{-- resources/views/admin/hotspots/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Hotspot Manager')
@section('page-title', 'Hotspot Manager — ' . $space->name)

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a href="{{ route('admin.spaces.index') }}" class="hover:text-brand-500 transition-colors">Spaces</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-medium">{{ $space->name }}</span>
        </div>
        <p class="text-slate-500 text-sm">Click anywhere on the 360° viewer to place a hotspot</p>
    </div>
    <a href="{{ route('admin.spaces.index') }}"
       class="flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Spaces
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    {{-- ═══════════════════════════════════
         LEFT : Pannellum Viewer
    ═══════════════════════════════════ --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Viewer card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    <span class="text-sm font-semibold text-slate-700">360° Preview — Click to place hotspot</span>
                </div>
                <span id="click-hint" class="text-xs text-brand-500 font-medium bg-brand-50 px-3 py-1 rounded-full">
                    Crosshair mode active
                </span>
            </div>

            {{-- Pannellum container --}}
            <div class="relative">
                <div id="panorama"
                     style="width:100%; height:500px; cursor:crosshair;"
                     data-photo="{{ $space->photo_url }}">
                </div>

                {{-- Coordinates badge --}}
                <div id="coords-badge"
                     class="absolute bottom-4 left-4 bg-navy-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-lg font-mono opacity-0 transition-opacity duration-300">
                    pitch: <span id="badge-pitch">—</span> &nbsp; yaw: <span id="badge-yaw">—</span>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             Hotspots Table
        ═══════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-slate-700">Hotspots in this scene</span>
                </div>
                <span class="text-xs font-medium bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                    {{ $hotspots->count() }} total
                </span>
            </div>

            @if($hotspots->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium">No hotspots yet</p>
                    <p class="text-sm text-slate-400 mt-1">Click on the 360° viewer above to place your first hotspot</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Label / Target</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($hotspots as $hotspot)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    @if($hotspot->type === 'employee')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700">
                                            Employee
                                        </span>
                                    @elseif($hotspot->type === 'trainer')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                            Trainer
                                        </span>
                                    @elseif($hotspot->type === 'schedule')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-700">
                                            Schedule
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            Scene link
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($hotspot->type === 'employee' && $hotspot->employee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center shrink-0 text-brand-600 font-bold text-xs">
                                                {{ strtoupper(substr($hotspot->employee->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-700">{{ $hotspot->employee->full_name }}</p>
                                                <p class="text-xs text-slate-400">{{ $hotspot->employee->job_title }}</p>
                                            </div>
                                        </div>
                                    @elseif($hotspot->type === 'trainer' && $hotspot->trainer)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center shrink-0 text-purple-600 font-bold text-xs">
                                                {{ strtoupper(substr($hotspot->trainer->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-700">{{ $hotspot->trainer->first_name }} {{ $hotspot->trainer->last_name }}</p>
                                                <p class="text-xs text-slate-400">Trainer</p>
                                            </div>
                                        </div>
                                    @elseif($hotspot->type === 'scene' && $hotspot->targetScene)
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-slate-700">→ {{ $hotspot->targetScene->name }}</p>
                                        </div>
                                    @elseif($hotspot->type === 'schedule')
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-slate-700">Room Schedule</p>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">—</span>
                                    @endif
                                    
                                    @if($hotspot->label)
                                        <p class="text-xs text-slate-400 mt-0.5 ml-9">{{ $hotspot->label }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded">p: {{ number_format($hotspot->pitch, 4) }}</span>
                                    <span class="bg-slate-100 px-2 py-0.5 rounded ml-1">y: {{ number_format($hotspot->yaw, 4) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{-- Edit button --}}
                                        <button
                                            onclick="openEditModal({{ $hotspot->id }}, '{{ $hotspot->type }}', {{ $hotspot->pitch }}, {{ $hotspot->yaw }}, '{{ addslashes($hotspot->label ?? '') }}', '{{ $hotspot->employee_id ?? '' }}', '{{ $hotspot->trainer_id ?? '' }}', '{{ $hotspot->target_scene_id ?? '' }}')"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route('admin.spaces.hotspots.destroy', [$space, $hotspot]) }}"
                                              onsubmit="return confirm('Delete this hotspot?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════
         RIGHT : Add Hotspot Form
    ═══════════════════════════════════ --}}
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm sticky top-28">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-slate-700">Add Hotspot</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.spaces.hotspots.store', $space) }}" class="p-6 space-y-5">
                @csrf

                {{-- Coordinates (auto-filled) --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Position (auto-filled on click)</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Pitch</label>
                            <input type="number" name="pitch" id="pitch-input" step="any"
                                   value="{{ old('pitch') }}" required
                                   placeholder="Click viewer"
                                   class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg font-mono text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('pitch') border-red-400 @enderror"/>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Yaw</label>
                            <input type="number" name="yaw" id="yaw-input" step="any"
                                   value="{{ old('yaw') }}" required
                                   placeholder="Click viewer"
                                   class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg font-mono text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('yaw') border-red-400 @enderror"/>
                        </div>
                    </div>
                    @error('pitch') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('yaw')   <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Hotspot type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="type-btn cursor-pointer">
                            <input type="radio" name="type" value="employee" class="sr-only"
                                   {{ old('type', 'employee') === 'employee' ? 'checked' : '' }}
                                   onchange="switchType('employee')"/>
                            <div class="type-label flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                                        {{ old('type', 'employee') === 'employee' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                Employee
                            </div>
                        </label>
                        <label class="type-btn cursor-pointer">
                            <input type="radio" name="type" value="scene" class="sr-only"
                                   {{ old('type') === 'scene' ? 'checked' : '' }}
                                   onchange="switchType('scene')"/>
                            <div class="type-label flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                                        {{ old('type') === 'scene' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                Scene link
                            </div>
                        </label>
                        <label class="type-btn cursor-pointer">
                            <input type="radio" name="type" value="trainer" class="sr-only"
                                   {{ old('type') === 'trainer' ? 'checked' : '' }}
                                   onchange="switchType('trainer')"/>
                            <div class="type-label flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                                        {{ old('type') === 'trainer' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                Trainer
                            </div>
                        </label>
                        <label class="type-btn cursor-pointer">
                            <input type="radio" name="type" value="schedule" class="sr-only"
                                   {{ old('type') === 'schedule' ? 'checked' : '' }}
                                   onchange="switchType('schedule')"/>
                            <div class="type-label flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                                        {{ old('type') === 'schedule' ? 'border-sky-500 bg-sky-50 text-sky-700' : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                Schedule
                            </div>
                        </label>
                    </div>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Employee select --}}
                <div id="employee-field" class="{{ old('type', 'employee') !== 'employee' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Employee</label>
                    <select name="employee_id"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('employee_id') border-red-400 @enderror">
                        <option value="">— Select employee —</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} — {{ $emp->job_title }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                {{-- Trainer select --}}
                <div id="trainer-field" class="{{ old('type') !== 'trainer' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Trainer</label>
                    <select name="trainer_id"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('trainer_id') border-red-400 @enderror">
                        <option value="">— Select trainer —</option>
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                {{ $trainer->first_name }} {{ $trainer->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('trainer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Scene select --}}
                <div id="scene-field" class="{{ old('type') !== 'scene' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Target scene</label>
                    <select name="target_scene_id"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('target_scene_id') border-red-400 @enderror">
                        <option value="">— Select scene —</option>
                        @foreach($scenes as $scene)
                            <option value="{{ $scene->id }}" {{ old('target_scene_id') == $scene->id ? 'selected' : '' }}>
                                {{ $scene->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('target_scene_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Label --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Label <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" name="label" value="{{ old('label') }}"
                           placeholder="e.g. Schedule for Room"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition-colors @error('label') border-red-400 @enderror"/>
                    @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand-500 hover:bg-brand-600 active:scale-95 text-white text-sm font-semibold rounded-xl shadow-md shadow-brand-500/20 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Hotspot
                </button>
            </form>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════
     EDIT MODAL
═══════════════════════════════════ --}}
<div id="edit-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     onclick="closeEditModal(event)">
    <div class="absolute inset-0 bg-navy-900/60 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">Edit Hotspot</h3>
            <button onclick="document.getElementById('edit-modal').classList.add('hidden')"
                    class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-slate-500 font-medium block mb-1">Pitch</label>
                    <input type="number" name="pitch" id="edit-pitch" step="any" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg font-mono focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400"/>
                </div>
                <div>
                    <label class="text-xs text-slate-500 font-medium block mb-1">Yaw</label>
                    <input type="number" name="yaw" id="edit-yaw" step="any" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg font-mono focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400"/>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Type</label>
                <select name="type" id="edit-type" onchange="switchEditType(this.value)"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                    <option value="employee">Employee</option>
                    <option value="scene">Scene link</option>
                    <option value="trainer">Trainer</option>
                    <option value="schedule">Schedule</option>
                </select>
            </div>

            <div id="edit-employee-field" class="hidden">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Employee</label>
                <select name="employee_id" id="edit-employee-id"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                    <option value="">— Select employee —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="edit-trainer-field" class="hidden">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Trainer</label>
                <select name="trainer_id" id="edit-trainer-id"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                    <option value="">— Select trainer —</option>
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}">{{ $trainer->first_name }} {{ $trainer->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="edit-scene-field" class="hidden">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Target scene</label>
                <select name="target_scene_id" id="edit-target-scene-id"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                    <option value="">— Select scene —</option>
                    @foreach($scenes as $scene)
                        <option value="{{ $scene->id }}">{{ $scene->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Label</label>
                <input type="text" name="label" id="edit-label"
                       class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400"/>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('edit-modal').classList.add('hidden')"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold shadow-md shadow-brand-500/20 transition-colors active:scale-95">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
{{-- Pannellum --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

<script>
// ─── Init Pannellum ───────────────────────────────────────────
const photoUrl = document.getElementById('panorama').dataset.photo;

const viewer = pannellum.viewer('panorama', {
    type: 'equirectangular',
    panorama: photoUrl,
    autoLoad: true,
    showControls: false,
    mouseZoom: true,
    hfov: 100,
    hotSpots: [
        @foreach($hotspots as $hs)
        {
            pitch: {{ $hs->pitch }},
            yaw: {{ $hs->yaw }},
            type: 'info',
            text: '{{ addslashes($hs->label ?? ($hs->type === "employee" && $hs->employee ? $hs->employee->full_name : ($hs->type === "trainer" && $hs->trainer ? $hs->trainer->first_name." ".$hs->trainer->last_name : ($hs->targetScene ? $hs->targetScene->name : "")))) }}',
            cssClass: 'hs-{{ $hs->type }}'
        },
        @endforeach
    ]
});

// ─── Click to capture pitch/yaw ──────────────────────────────
document.getElementById('panorama').addEventListener('click', function(e) {
    const coords = viewer.mouseEventToCoords(e);
    if (!coords) return;

    const pitch = parseFloat(coords[0].toFixed(4));
    const yaw   = parseFloat(coords[1].toFixed(4));

    // Fill form
    document.getElementById('pitch-input').value = pitch;
    document.getElementById('yaw-input').value   = yaw;

    // Update badge
    document.getElementById('badge-pitch').textContent = pitch;
    document.getElementById('badge-yaw').textContent   = yaw;
    document.getElementById('coords-badge').style.opacity = '1';

    // Flash the inputs
    ['pitch-input','yaw-input'].forEach(id => {
        const el = document.getElementById(id);
        el.classList.add('ring-2','ring-brand-400','border-brand-400');
        setTimeout(() => el.classList.remove('ring-2','ring-brand-400','border-brand-400'), 1200);
    });
});

// ─── Type switcher (add form) ─────────────────────────────────
function switchType(type) {
    const empField     = document.getElementById('employee-field');
    const sceneField   = document.getElementById('scene-field');
    const trainerField = document.getElementById('trainer-field');

    empField.classList.add('hidden');
    sceneField.classList.add('hidden');
    trainerField.classList.add('hidden');

    if (type === 'employee') {
        empField.classList.remove('hidden');
    } else if (type === 'scene') {
        sceneField.classList.remove('hidden');
    } else if (type === 'trainer') {
        trainerField.classList.remove('hidden');
    }

    // Update radio button styling
    document.querySelectorAll('.type-btn .type-label').forEach(el => {
        el.className = el.className
            .replace('border-brand-500 bg-brand-50 text-brand-700','')
            .replace('border-amber-500 bg-amber-50 text-amber-700','')
            .replace('border-purple-500 bg-purple-50 text-purple-700','')
            .replace('border-sky-500 bg-sky-50 text-sky-700','')
            .trim();
        el.classList.add('border-slate-200','text-slate-500');
    });

    const active = document.querySelector(`.type-btn input[value="${type}"] + .type-label`);
    if (active) {
        active.classList.remove('border-slate-200','text-slate-500');
        if (type === 'employee') active.classList.add('border-brand-500','bg-brand-50','text-brand-700');
        else if(type === 'scene') active.classList.add('border-amber-500','bg-amber-50','text-amber-700');
        else if(type === 'trainer') active.classList.add('border-purple-500','bg-purple-50','text-purple-700');
        else if(type === 'schedule') active.classList.add('border-sky-500','bg-sky-50','text-sky-700');
    }
}

// ─── Edit modal ───────────────────────────────────────────────
function openEditModal(id, type, pitch, yaw, label, employeeId, trainerId, targetSceneId) {
    const form = document.getElementById('edit-form');
    form.action = `/admin/spaces/{{ $space->id }}/hotspots/${id}`;

    document.getElementById('edit-pitch').value = pitch;
    document.getElementById('edit-yaw').value   = yaw;
    document.getElementById('edit-label').value = label;
    document.getElementById('edit-type').value  = type;

    if (employeeId) document.getElementById('edit-employee-id').value   = employeeId;
    if (trainerId) document.getElementById('edit-trainer-id').value   = trainerId;
    if (targetSceneId) document.getElementById('edit-target-scene-id').value = targetSceneId;

    switchEditType(type);
    document.getElementById('edit-modal').classList.remove('hidden');
}

function switchEditType(type) {
    document.getElementById('edit-employee-field').classList.toggle('hidden', type !== 'employee');
    document.getElementById('edit-scene-field').classList.toggle('hidden', type !== 'scene');
    document.getElementById('edit-trainer-field').classList.toggle('hidden', type !== 'trainer');
}

function closeEditModal(e) {
    if (e.target === document.getElementById('edit-modal')) {
        document.getElementById('edit-modal').classList.add('hidden');
    }
}
</script>

<style>
/* ═══════════════════════════════════
     GLOBAL BULLETPROOF HOTSPOT CSS
═══════════════════════════════════ */
.pnlm-hotspot {
    cursor: pointer !important;
    display: block !important;
    visibility: visible !important;
    z-index: 999 !important;
    opacity: 1 !important;
}

/* Employee Hotspot */
.pnlm-hotspot.hs-employee { 
    background-color: #6366f1 !important; 
    background-image: none !important;
    border-radius: 100% !important; 
    width: 28px !important; 
    height: 28px !important; 
    border: 4px solid #ffffff !important; 
    box-shadow: 0 0 20px rgba(99,102,241,0.8), 0 0 10px rgba(0,0,0,0.5) !important; 
}

/* Scene link Hotspot */
.pnlm-hotspot.hs-scene { 
    background-color: #f59e0b !important; 
    background-image: none !important;
    border-radius: 10px !important; 
    width: 28px !important; 
    height: 28px !important; 
    border: 4px solid #ffffff !important; 
    box-shadow: 0 0 20px rgba(245,158,11,0.8), 0 0 10px rgba(0,0,0,0.5) !important; 
}

/* Trainer Hotspot */
.pnlm-hotspot.hs-trainer { 
    background-color: #a855f7 !important; 
    background-image: none !important;
    border-radius: 100% !important; 
    width: 28px !important; 
    height: 28px !important; 
    border: 4px solid #ffffff !important; 
    box-shadow: 0 0 20px rgba(168,85,247,0.8), 0 0 10px rgba(0,0,0,0.5) !important; 
}

/* Schedule Hotspot */
.pnlm-hotspot.hs-schedule { 
    background-color: #0ea5e9 !important; 
    background-image: none !important;
    border-radius: 4px !important; 
    width: 28px !important; 
    height: 28px !important; 
    border: 4px solid #ffffff !important; 
    box-shadow: 0 0 20px rgba(14,165,233,0.8), 0 0 10px rgba(0,0,0,0.5) !important; 
}
</style>
@endpush