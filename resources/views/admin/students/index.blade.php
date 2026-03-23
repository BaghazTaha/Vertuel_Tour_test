@extends('layouts.admin')

@section('title', 'Students')
@section('page-title', 'Students')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $students->total() }} student(s) found</p>
    <a href="{{ route('admin.students.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
              text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Student
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Photo</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Group</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($students as $student)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $student->email }}<br>
                        <span class="text-xs text-gray-400">{{ $student->phone }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $student->group->name ?? 'No Group' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                      font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                      hover:text-indigo-700 transition">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.students.destroy', $student) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($student->first_name . " " . $student->last_name) }}? This cannot be undone.')">
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
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                        No students yet.
                        <a href="{{ route('admin.students.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($students->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $students->links() }}
    </div>
    @endif
</div>

@endsection
