@extends('layouts.admin')

@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $groups->total() }} group(s) found</p>
    <a href="{{ route('admin.groups.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
              text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Group
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Students</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($groups as $group)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $group->name }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                        {{ $group->description ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500">
                        {{ $group->max_capacity }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                           $isFull = $group->students_count >= $group->max_capacity;
                        @endphp
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold
                                     {{ $isFull ? 'bg-red-50 text-red-700' : 'bg-indigo-50 text-indigo-700' }}">
                            {{ $group->students_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.groups.edit', $group) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                      font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                      hover:text-indigo-700 transition">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.groups.destroy', $group) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($group->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                           font-medium bg-gray-100 text-gray-700 hover:bg-red-50
                                           hover:text-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                        No groups yet.
                        <a href="{{ route('admin.groups.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($groups->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $groups->links() }}
    </div>
    @endif
</div>

@endsection
