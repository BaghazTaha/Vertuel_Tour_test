{{-- resources/views/admin/employees/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $employees->total() }} employee(s) found</p>
    <a href="{{ route('admin.employees.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Employee
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Matricule</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Job Title</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($employees as $emp)
            <tr class="hover:bg-gray-50 transition">

                {{-- Avatar + Name --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold
                                    text-sm flex items-center justify-center shrink-0 overflow-hidden">
                            @if ($emp->photo)
                                <img src="{{ asset('storage/'.$emp->photo) }}"
                                     class="w-full h-full object-cover" alt=""/>
                            @else
                                {{ strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $emp->full_name }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $emp->matricule }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $emp->job_title }}</td>
                <td class="px-6 py-4">
                    @if ($emp->department)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs
                                     font-medium bg-emerald-50 text-emerald-700">
                            {{ $emp->department->name }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500 text-xs">
                    <div>{{ $emp->email }}</div>
                    <div class="text-gray-400">{{ $emp->phone ?? '—' }}</div>
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-2">
                        <a href="{{ route('admin.employees.show', $emp) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                  font-medium bg-gray-100 text-gray-700 hover:bg-blue-50
                                  hover:text-blue-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478
                                       0 8.268 2.943 9.542 7-1.274 4.057-5.064
                                       7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                        <a href="{{ route('admin.employees.edit', $emp) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                  font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                  hover:text-indigo-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2
                                       2 0 002-2v-5m-1.414-9.414a2 2 0 112.828
                                       2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('admin.employees.destroy', $emp) }}"
                              onsubmit="return confirm('Delete {{ addslashes($emp->full_name) }}? This will also delete their user account.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                       font-medium bg-gray-100 text-gray-700 hover:bg-red-50
                                       hover:text-red-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138
                                           21H7.862a2 2 0 01-1.995-1.858L5
                                           7m5 4v6m4-6v6m1-10V4a1 1 0
                                           00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                    No employees yet.
                    <a href="{{ route('admin.employees.create') }}"
                       class="text-indigo-600 hover:underline ml-1">Add one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($employees->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $employees->links() }}
        </div>
    @endif
</div>

@endsection