<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Afficher l'emploi de la semaine du formateur
    public function schedule()
    {
        $trainer = Auth::user()->trainer;
        $schedules = $trainer->schedules()->with(['group', 'space'])->get();
        // Pour cet affichage, on peut grouper par jour
        
        return view('trainer.attendances.schedule', compact('schedules', 'trainer'));
    }

    // Afficher la liste des étudiants pour une séance (et la date choisie)
    public function index(Request $request, Schedule $schedule)
    {
        // Vérifier que le schedule appartient au formateur
        if ($schedule->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $students = $schedule->group->students;

        // Récupérer les présences existantes pour ce schedule et cette date
        $attendances = Attendance::where('schedule_id', $schedule->id)
                                 ->where('date', $date)
                                 ->get()
                                 ->keyBy('student_id');

        // Check if validated
        $isValidated = $attendances->where('is_validated', true)->count() > 0;

        return view('trainer.attendances.index', compact('schedule', 'students', 'date', 'attendances', 'isValidated'));
    }

    // Enregistrer l'absence / présence
    public function store(Request $request, Schedule $schedule)
    {
        if ($schedule->trainer_id !== Auth::user()->trainer->id) {
            abort(403, 'Unauthorized action.');
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        
        // Check if already validated
        $validatedCount = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->where('is_validated', true)
            ->count();

        if ($validatedCount > 0) {
            return back()->with('error', 'Ces présences ont déjà été validées par l\'administration et ne peuvent plus être modifiées.');
        }

        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:present,absent,late'
        ]);

        foreach ($validated['attendances'] as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $studentId,
                    'date' => $date
                ],
                [
                    'status' => $data['status']
                ]
            );
        }

        return redirect()->route('trainer.attendances.index', ['schedule' => $schedule->id, 'date' => $date])->with('success', 'Présences enregistrées avec succès.');
    }
}
