@extends('layouts.admin')

@section('title', 'Trainers')
@section('page-title', 'Trainers')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $trainers->total() }} trainer(s) found</p>
    <a href="{{ route('admin.trainers.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
              text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Trainer
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Photo</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name & Specialty</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($trainers as $trainer)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" class="w-10 h-10 rounded-full object-cover shadow-sm">
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 text-xs font-bold ring-1 ring-inset ring-indigo-700/10">
                                {{ substr($trainer->first_name, 0, 1) }}{{ substr($trainer->last_name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $trainer->first_name }} {{ $trainer->last_name }}
                        <br>
                        <span class="text-xs text-gray-500 font-normal py-0.5 px-2 bg-gray-100 rounded-full mt-1 inline-block">
                            {{ $trainer->specialty }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $trainer->email }}<br>
                        <span class="text-xs text-gray-400">{{ $trainer->phone ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700">
                            {{ $trainer->department->name ?? 'None' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.trainers.edit', $trainer) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                      font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                      hover:text-indigo-700 transition">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.trainers.destroy', $trainer) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($trainer->first_name . " " . $trainer->last_name) }}? This cannot be undone.')">
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
                        No trainers yet.
                        <a href="{{ route('admin.trainers.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($trainers->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $trainers->links() }}
    </div>
    @endif
</div>

@endsection
