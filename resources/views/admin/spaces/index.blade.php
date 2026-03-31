{{-- resources/views/admin/spaces/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Spaces')
@section('page-title', 'Spaces / Scenes')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $spaces->total() }} space(s) found</p>
    <a href="{{ route('admin.spaces.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Space
    </a>
</div>

{{-- Grid view --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse ($spaces as $space)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden
                flex flex-col hover:shadow-md transition">

        {{-- Thumbnail --}}
        <div class="relative h-44 bg-gray-100 overflow-hidden">
            @if ($space->thumbnail_path)
                <img src="{{ asset('storage/'.$space->thumbnail_path) }}"
                     class="w-full h-full object-cover" alt="{{ $space->name }}"/>
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586
                               a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2
                               2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- Hotspot badge --}}
            <div class="absolute top-2 right-2">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs
                             font-medium bg-black/50 text-white backdrop-blur-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827
                               0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $space->hotspots_count }}
                </span>
            </div>
        </div>

        {{-- Info --}}
        <div class="p-4 flex-1 flex flex-col">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800 text-sm">{{ $space->name }}</h3>
                @if ($space->department)
                    <span class="inline-block mt-1 text-xs px-2 py-0.5 bg-emerald-50
                                 text-emerald-700 rounded-full">
                        {{ $space->department->name }}
                    </span>
                @endif
                @if ($space->description)
                    <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ $space->description }}</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-50">
                <a href="{{ route('admin.spaces.show', $space) }}"
                   class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium
                          bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                    View
                </a>
                <a href="{{ route('admin.spaces.hotspots.index', $space) }}"
                   class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium
                          bg-gray-100 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                    Hotspots
                </a>
                <a href="{{ route('admin.spaces.edit', $space) }}"
                   class="flex-1 text-center py-1.5 rounded-lg text-xs font-medium
                          bg-gray-100 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.spaces.destroy', $space) }}"
                      onsubmit="return confirm('Delete {{ addslashes($space->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100
                               text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                   01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                   00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-400 text-sm bg-white rounded-2xl
                border border-gray-100">
        No spaces yet.
        <a href="{{ route('admin.spaces.create') }}"
           class="text-indigo-600 hover:underline ml-1">Create one</a>
    </div>
    @endforelse
</div>

@if ($spaces->hasPages())
    <div class="mt-6">{{ $spaces->links() }}</div>
@endif

@endsection