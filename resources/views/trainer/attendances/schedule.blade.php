<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Emploi du Temps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b">
                    <h2 class="text-2xl font-bold mb-2">Mon Emploi du Temps</h2>
                    <p class="text-gray-600">Bienvenue {{ $trainer->first_name }}, voici vos séances régulières. Cliquez sur une séance pour gérer les absences.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($schedules as $schedule)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 uppercase tracking-wide">
                                    {{ $schedule->day_of_week }}
                                </span>
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $schedule->subject }}</h3>
                        
                        <div class="space-y-2 mt-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Groupe: {{ $schedule->group->name }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 8h8"></path></svg>
                                Salle: {{ $schedule->space->name }}
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between gap-3">
                            <form action="{{ route('trainer.attendances.index', $schedule->id) }}" method="GET" class="w-full">
                                <div class="flex gap-2">
                                    <input type="date" name="date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Gérer Absence
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-xl shadow-sm text-center">
                        <p class="text-gray-500">Aucune séance n'est associée à votre profil pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
