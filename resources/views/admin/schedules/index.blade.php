@extends('layouts.admin')

@section('title', 'Schedules')
@section('page-title', 'Schedules')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $schedules->total() }} scheduled session(s)</p>
    <a href="{{ route('admin.schedules.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
              text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Schedule
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Day & Time</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject & Trainer</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Group</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Space / Room</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($schedules as $schedule)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        <span class="inline-block mb-1">{{ $schedule->day_of_week }}</span><br>
                        <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            - 
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $schedule->subject }}<br>
                        <span class="text-xs text-gray-500 font-normal">
                            By: {{ $schedule->trainer->first_name ?? '—' }} {{ $schedule->trainer->last_name ?? '' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $schedule->group->name ?? 'None' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700">
                            {{ $schedule->space->name ?? 'Unassigned' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs
                                      font-medium bg-gray-100 text-gray-700 hover:bg-indigo-50
                                      hover:text-indigo-700 transition">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.schedules.destroy', $schedule) }}"
                                  onsubmit="return confirm('Delete this schedule session? This cannot be undone.')">
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
                        No scheduled sessions yet.
                        <a href="{{ route('admin.schedules.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($schedules->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $schedules->links() }}
    </div>
    @endif
</div>

@endsection
