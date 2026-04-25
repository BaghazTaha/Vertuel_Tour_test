@extends('layouts.admin')

@section('title', 'Statistiques des Absences')
@section('page-title', 'Statistiques des Absences')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.attendances.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-2 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Retour à la gestion
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Taux d'absences par séance</h3>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Séance / Matière</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Groupe</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Total des Enregistrements</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Total des Absences</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Taux d'Absence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($absenceRateBySchedule as $stat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-4 font-medium text-gray-800">
                            {{ $stat->schedule->subject }}
                        </td>
                        <td class="py-4 px-4 text-gray-600">
                            {{ $stat->schedule->group->name }}
                        </td>
                        <td class="py-4 px-4 text-center text-gray-600">
                            {{ $stat->total_records }}
                        </td>
                        <td class="py-4 px-4 text-center text-red-600 font-medium">
                            {{ $stat->total_absences }}
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-{{ $stat->rate > 30 ? 'red' : ($stat->rate > 15 ? 'orange' : 'emerald') }}-500 h-2.5 rounded-full" style="width: {{ min(100, $stat->rate) }}%"></div>
                                </div>
                                <span class="font-bold text-gray-700 w-12">{{ number_format($stat->rate, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Aucune donnée disponible pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
