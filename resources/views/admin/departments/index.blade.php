{{-- resources/views/admin/departments/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $departments->total() }} department(s) found</p>
    <a href="{{ route('admin.departments.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
              text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Department
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Employees</th>
                <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Spaces</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($departments as $dept)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $dept->name }}</td>
                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                    {{ $dept->description ?? '—' }}
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                                 bg-indigo-50 text-indigo-700 text-xs font-semibold">
                        {{ $dept->employees_count }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                                 bg-amber-50 text-amber-700 text-xs font-semibold">
                        {{ $dept->spaces_count }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-2">
                        <a href="{{ route('admin.departments.edit', $dept) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                  font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                  hover:text-indigo-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002
                                       2h11a2 2 0 002-2v-5m-1.414-9.414a2
                                       2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('admin.departments.destroy', $dept) }}"
                              onsubmit="return confirm('Delete {{ addslashes($dept->name) }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
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
                    No departments yet.
                    <a href="{{ route('admin.departments.create') }}"
                       class="text-indigo-600 hover:underline ml-1">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($departments->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $departments->links() }}
    </div>
    @endif
</div>

@endsection