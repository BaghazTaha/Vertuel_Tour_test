<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Absences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('trainer.schedule') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour à l'emploi du temps
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b">
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">Appel : {{ $schedule->subject }}</h2>
                            <p class="text-gray-600">
                                Groupe: <strong>{{ $schedule->group->name }}</strong> | 
                                Salle: <strong>{{ $schedule->space->name }}</strong> | 
                                Horaire: <strong>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</strong>
                            </p>
                        </div>
                        
                        <form action="{{ route('trainer.attendances.index', $schedule->id) }}" method="GET" class="flex gap-2 items-end">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Changer la Date</label>
                                <input type="date" name="date" id="date" value="{{ $date }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow-sm text-sm border border-gray-300 font-medium pb-[9px]">
                                Voir
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($isValidated)
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative flex items-center gap-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p><strong>Cadenassé.</strong> Cet appel a été validé par l'administration et ne peut plus être modifié.</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('trainer.attendances.store', $schedule->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Photo</th>
                                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom de l'étudiant</th>
                                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Présent</th>
                                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Absent</th>
                                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">En retard</th>
                                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Justification (Admin)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($students as $student)
                                @php
                                    $status = isset($attendances[$student->id]) ? $attendances[$student->id]->status : 'present';
                                    $justification = isset($attendances[$student->id]) ? $attendances[$student->id]->justification : '';
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" class="w-10 h-10 rounded-full object-cover shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 text-xs font-bold ring-1 ring-inset ring-indigo-700/10">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}][status]" value="present" 
                                               class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300" 
                                               {{ $status == 'present' ? 'checked' : '' }}
                                               {{ $isValidated ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}][status]" value="absent" 
                                               class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300"
                                               {{ $status == 'absent' ? 'checked' : '' }}
                                               {{ $isValidated ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}][status]" value="late" 
                                               class="h-5 w-5 text-orange-500 focus:ring-orange-500 border-gray-300"
                                               {{ $status == 'late' ? 'checked' : '' }}
                                               {{ $isValidated ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 italic text-xs">
                                        {{ $justification ?: '--' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(!$isValidated)
                    <div class="p-6 bg-gray-50 border-t border-gray-100 text-right">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition">
                            Enregistrer et Confirmer l'appel
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>
