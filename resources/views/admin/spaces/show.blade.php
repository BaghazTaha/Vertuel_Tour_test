{{-- resources/views/admin/spaces/show.blade.php --}}
@extends('layouts.admin')

@section('title', $space->name)
@section('page-title', $space->name)

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Left: Info card --}}
    <div class="xl:col-span-1 space-y-5">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Thumbnail --}}
            <div class="h-48 bg-gray-100 overflow-hidden">
                @if ($space->thumbnail_path)
                    <img src="{{ asset('storage/'.$space->thumbnail_path) }}"
                         class="w-full h-full object-cover" alt="{{ $space->name }}"/>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586
                                   a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2
                                   2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="p-5 space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Space Name</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $space->name }}</p>
                </div>
                @if ($space->description)
                <div>
                    <p class="text-xs text-gray-400">Description</p>
                    <p class="text-gray-600 mt-0.5">{{ $space->description }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-gray-400">Department</p>
                    <p class="font-medium text-gray-700 mt-0.5">
                        {{ $space->department?->name ?? '— None —' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Created</p>
                    <p class="text-gray-600 mt-0.5">{{ $space->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="px-5 pb-5 flex gap-2">
                <a href="{{ route('admin.spaces.edit', $space) }}"
                   class="flex-1 text-center py-2 rounded-xl text-xs font-medium
                          bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                    Edit Space
                </a>
                <a href="{{ route('admin.spaces.hotspots.index', ['space' => $space->id]) }}"
                   class="flex-1 text-center py-2 rounded-xl text-xs font-medium
                          bg-amber-50 text-amber-700 hover:bg-amber-100 transition">
                    Manage Hotspots
                </a>
            </div>
        </div>

    </div>

    {{-- Right: Hotspots list --}}
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700 text-sm">
                    Hotspots
                    <span class="ml-1.5 text-xs font-normal text-gray-400">
                        ({{ $space->hotspots->count() }})
                    </span>
                </h2>
                <a href="{{ route('admin.spaces.hotspots.index', ['space' => $space->id]) }}"
                   class="text-xs text-indigo-600 hover:underline">Manage</a>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse ($space->hotspots as $hotspot)
                <div class="flex items-center gap-4 px-6 py-3">
                    {{-- Type badge --}}
                    @if ($hotspot->type === 'employee')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                     text-xs font-medium bg-indigo-50 text-indigo-700 shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Employee
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                     text-xs font-medium bg-amber-50 text-amber-700 shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0
                                       105.656 5.656l1.102-1.101m-.758-4.899a4 4 0
                                       005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1
                                       1.1"/>
                            </svg>
                            Scene Link
                        </span>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $hotspot->label ?: '—' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            pitch: {{ $hotspot->pitch }} / yaw: {{ $hotspot->yaw }}
                        </p>
                    </div>

                    <div class="text-xs text-gray-400 shrink-0">
                        @if ($hotspot->type === 'employee' && $hotspot->employee)
                            {{ $hotspot->employee->full_name }}
                        @elseif ($hotspot->type === 'scene' && $hotspot->targetScene)
                            → {{ $hotspot->targetScene->name }}
                        @endif
                    </div>
                </div>
                @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">
                    No hotspots yet.
                    <a href="{{ route('admin.spaces.hotspots.index', ['space' => $space->id]) }}"
                       class="text-indigo-600 hover:underline ml-1">Add one</a>
                </p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection