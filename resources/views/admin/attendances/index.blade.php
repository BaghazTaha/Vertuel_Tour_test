@extends('layouts.admin')

@section('title', 'Gestion des Absences')
@section('page-title', 'Absences & Présences')

@section('content')

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Left side: List of sessions -->
    <div class="xl:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[700px]">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Séances enregistrées</h3>
            <a href="{{ route('admin.attendances.stats') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Voir les Stats</a>
        </div>
        <div class="overflow-y-auto flex-1 p-2 space-y-2">
            @forelse($attendancesData as $data)
            <a href="{{ route('admin.attendances.index', ['schedule_id' => $data->schedule_id, 'date' => \Carbon\Carbon::parse($data->date)->format('Y-m-d')]) }}" 
               class="block p-4 rounded-xl border {{ ($selectedSchedule && $selectedSchedule->id == $data->schedule_id && $selectedDate == \Carbon\Carbon::parse($data->date)->format('Y-m-d')) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-100 hover:border-indigo-300 hover:bg-gray-50' }} transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded-md shadow-sm border border-gray-100">
                        {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}
                    </span>
                    @if($data->is_validated)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Validé
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> En attente
                        </span>
                    @endif
                </div>
                <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $data->schedule?->subject ?? 'Séance supprimée' }}</h4>
                <p class="text-xs text-gray-500 mb-2">Trainer: {{ $data->schedule?->trainer?->first_name ?? 'Inconnu' }} {{ $data->schedule?->trainer?->last_name ?? '' }}</p>
                <div class="flex gap-3 text-xs">
                    <span class="text-emerald-600 font-medium">{{ $data->present_count }} Présents</span>
                    <span class="text-red-600 font-medium">{{ $data->absent_count }} Absents</span>
                    <span class="text-orange-500 font-medium">{{ $data->late_count }} Retards</span>
                </div>
            </a>
            @empty
            <div class="text-center p-6 text-gray-500 text-sm">
                Aucune présence enregistrée.
            </div>
            @endforelse
        </div>
        @if ($attendancesData->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $attendancesData->links() }}
        </div>
        @endif
    </div>

    <!-- Right side: Details and Administration -->
    <div class="xl:col-span-2">
        @if($selectedSchedule && $allAttendances && $allAttendances->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $selectedSchedule->subject }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Groupe: {{ $selectedSchedule->group->name }} | Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                    </p>
                </div>
                <div>
                    @if(!$allAttendances->first()->is_validated)
                    <form action="{{ route('admin.attendances.validate-session') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Valider cette séance
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 text-emerald-700 font-medium border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Séance validée
                    </span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">L'étudiant</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut Formateur</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration / Justification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($allAttendances as $attendance)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($attendance->status == 'present')
                                    <span class="text-emerald-600 font-bold">Présent</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="text-red-500 font-bold">Absent</span>
                                @else
                                    <span class="text-orange-500 font-bold">En retard</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST" class="flex items-start gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex-1 space-y-2">
                                        <div class="flex items-center gap-3">
                                            <select name="status" class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs">
                                                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Présent</option>
                                                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Retard</option>
                                            </select>
                                        </div>
                                        <input type="text" name="justification" value="{{ $attendance->justification }}" placeholder="Justification (optionnel)" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs placeholder-gray-400">
                                    </div>
                                    <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-md shadow-sm text-xs font-medium transition h-fit">
                                        Mettre à jour
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-gray-50 rounded-2xl border border-gray-200 border-dashed p-12 text-center h-full flex flex-col items-center justify-center">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <h3 class="text-xl font-bold text-gray-500">Sélectionnez une séance</h3>
            <p class="text-gray-400 mt-2 max-w-md mx-auto">Choisissez une séance dans la liste de gauche pour visualiser les détails, modifier les statuts ou valider l'appel.</p>
        </div>
        @endif
    </div>
</div>

@endsection
