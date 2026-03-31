{{-- resources/views/admin/employees/show.blade.php --}}
@extends('layouts.admin')

@section('title', $employee->full_name)
@section('page-title', $employee->full_name)

@section('content')

<div class="max-w-2xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header band --}}
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 h-24"></div>

    <div class="px-8 pb-8">
        {{-- Avatar --}}
        <div class="flex items-end justify-between -mt-12 mb-6">
            <div class="w-24 h-24 rounded-2xl border-4 border-white shadow-md overflow-hidden
                        bg-indigo-100 text-indigo-700 text-3xl font-bold flex items-center
                        justify-center">
                @if ($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}"
                         class="w-full h-full object-cover" alt=""/>
                @else
                    {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                @endif
            </div>
            <div class="flex items-center gap-2 mt-14">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm
                          font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                    Edit
                </a>
                <a href="{{ route('admin.employees.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm
                          font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                    Back
                </a>
            </div>
        </div>

        {{-- Info --}}
        <h2 class="text-2xl font-bold text-gray-800">{{ $employee->full_name }}</h2>
        <p class="text-indigo-600 font-medium text-sm mt-0.5">{{ $employee->job_title }}</p>

        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0
                           002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4
                           0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0
                           0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0
                           00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                <div>
                    <p class="text-xs text-gray-400">Matricule</p>
                    <p class="font-mono font-medium text-gray-700">{{ $employee->matricule }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2
                           0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5
                           10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <div>
                    <p class="text-xs text-gray-400">Department</p>
                    <p class="font-medium text-gray-700">{{ $employee->department?->name ?? '—' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0
                           002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div>
                    <p class="text-xs text-gray-400">Email</p>
                    <a href="mailto:{{ $employee->email }}"
                       class="font-medium text-indigo-600 hover:underline">{{ $employee->email }}</a>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498
                           4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042
                           11.042 0 005.516 5.516l1.13-2.257a1 1 0
                           011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2
                           2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <div>
                    <p class="text-xs text-gray-400">Phone</p>
                    <a href="tel:{{ $employee->phone }}"
                       class="font-medium text-gray-700">{{ $employee->phone ?? '—' }}</a>
                </div>
            </div>
        </div>

        {{-- QR Code --}}
        @if ($employee->qr_code_path)
        <div class="mt-6 flex items-center gap-5 p-4 border border-gray-100 rounded-xl">
            <img src="{{ asset('storage/'.$employee->qr_code_path) }}"
                 class="w-20 h-20" alt="QR Code"/>
            <div>
                <p class="text-sm font-semibold text-gray-700">QR Code</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    Scans to:
                    <span class="font-mono text-indigo-600">
                        /employee/{{ $employee->matricule }}
                    </span>
                </p>
                <a href="{{ asset('storage/'.$employee->qr_code_path) }}" download
                   class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium
                          text-indigo-600 hover:underline">
                    Download QR
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
</div>

@endsection