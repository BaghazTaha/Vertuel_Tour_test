{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    {{-- Employees --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10
                       0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3
                       0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0
                       0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['employees'] }}</p>
            <p class="text-sm text-gray-500">Employees</p>
        </div>
    </div>

    {{-- Departments --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14
                       0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1
                       4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['departments'] }}</p>
            <p class="text-sm text-gray-500">Departments</p>
        </div>
    </div>

    {{-- Spaces --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16
                       16m-2-2l1.586-1.586a2 2 0 012.828 0L20
                       14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0
                       00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['spaces'] }}</p>
            <p class="text-sm text-gray-500">Spaces / Scenes</p>
        </div>
    </div>

    {{-- Hotspots --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0
                       01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['hotspots'] }}</p>
            <p class="text-sm text-gray-500">Hotspots</p>
        </div>
    </div>

</div>

{{-- Recent Activity --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Recent Employees --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700 text-sm">Recent Employees</h2>
            <a href="{{ route('admin.employees.index') }}"
               class="text-xs text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse ($recentEmployees as $emp)
            <div class="flex items-center gap-4 px-6 py-3">
                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center
                            text-indigo-700 font-bold text-sm shrink-0 overflow-hidden">
                    @if ($emp->photo)
                        <img src="{{ asset('storage/'.$emp->photo) }}"
                             class="w-full h-full object-cover" alt=""/>
                    @else
                        {{ strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $emp->full_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $emp->job_title }} — {{ $emp->department?->name ?? '—' }}</p>
                </div>
                <span class="text-xs text-gray-400">{{ $emp->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-gray-400">No employees yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Spaces --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-700 text-sm">Recent Spaces</h2>
            <a href="{{ route('admin.spaces.index') }}"
               class="text-xs text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse ($recentSpaces as $space)
            <div class="flex items-center gap-4 px-6 py-3">
                {{-- Thumbnail --}}
                <div class="w-12 h-9 rounded-lg bg-gray-100 overflow-hidden shrink-0 flex items-center justify-center">
                    @if ($space->thumbnail_path)
                        <img src="{{ asset('storage/'.$space->thumbnail_path) }}"
                             class="w-full h-full object-cover" alt=""/>
                    @else
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828
                                   0L16 16m-2-2l1.586-1.586a2 2 0
                                   012.828 0L20 14m-6-6h.01M6 20h12a2
                                   2 0 002-2V6a2 2 0 00-2-2H6a2 2 0
                                   00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $space->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $space->department?->name ?? 'No department' }}</p>
                </div>
                <span class="text-xs text-gray-400">{{ $space->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-gray-400">No spaces yet.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection