@extends('layouts.admin')

@section('title', 'Gestion des Emplois du Temps')
@section('page-title', 'Emplois du Temps')

@section('content')

<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Planning Hebdomadaire</h1>
            <p class="text-sm text-gray-500">Gérez les créneaux horaires, les salles et les affectations.</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white
                  text-sm font-medium px-5 py-2.5 rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Créneau
        </a>
    </div>

    {{-- Filters Card --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.schedules.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Jour</label>
                <select name="day" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tous les jours</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Groupe</label>
                <select name="group_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tous les groupes</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Salle / Espace</label>
                <select name="space_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Toutes les salles</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}" {{ request('space_id') == $space->id ? 'selected' : '' }}>{{ $space->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Formateur</label>
                <select name="trainer_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tous les formateurs</option>
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}" {{ request('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->first_name }} {{ $trainer->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Matière</label>
                <input type="text" name="subject" value="{{ request('subject') }}" placeholder="Rechercher..." 
                       class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-xl transition text-sm">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    {{-- Grid View --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-indigo-900 text-white">
                    <th class="border border-indigo-800 p-3 text-left w-32">Jour \ Heure</th>
                    @foreach($time_slots as $slot)
                        <th class="border border-indigo-800 p-3 text-center text-xs whitespace-nowrap">{{ $slot }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                <tr>
                    <td class="border border-gray-100 bg-indigo-50 p-3 font-bold text-indigo-900 text-sm">{{ $day }}</td>
                    @foreach($time_slots as $slot)
                    <td class="border border-gray-100 p-2 align-top h-32 w-48 relative group">
                        @if(isset($grid[$day][$slot]) && $grid[$day][$slot]->count() > 0)
                            @foreach($grid[$day][$slot] as $session)
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-2 rounded mb-2 shadow-sm text-xs relative overflow-hidden group/session">
                                <div class="font-bold text-indigo-900 truncate mb-1" title="{{ $session->subject }}">{{ $session->subject }}</div>
                                <div class="text-[10px] text-indigo-600 font-bold mb-1">
                                    {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}
                                </div>
                                <div class="text-gray-600 truncate">{{ $session->group->name ?? '—' }}</div>
                                <div class="text-gray-500 italic truncate">{{ $session->trainer->first_name ?? '' }} {{ $session->trainer->last_name ?? '' }}</div>
                                <div class="mt-1 text-indigo-600 font-semibold">{{ $session->space->name ?? '—' }}</div>
                                
                                {{-- Quick Actions Overlay --}}
                                <div class="absolute inset-0 bg-white/95 opacity-0 group-hover/session:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.schedules.edit', $session) }}" class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $session) }}" method="POST" onsubmit="return confirm('Supprimer ce créneau ?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="h-full w-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.schedules.create', ['day_of_week' => $day, 'start_time' => explode('-', $slot)[0]]) }}" class="text-indigo-400 hover:text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </a>
                            </div>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
