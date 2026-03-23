<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Space;
use App\Models\Trainer;
use App\Models\Group;
use App\Models\Hotspot;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index()
    {
        // On sécurise et on trie l'emploi du temps
        $schedules = Schedule::with(['space', 'trainer', 'group'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(15);
            
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $spaces = Space::all();
        $trainers = Trainer::all();
        $groups = Group::all();
        
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
        return view('admin.schedules.create', compact('spaces', 'trainers', 'groups', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'trainer_id' => 'required|exists:trainers,id',
            'group_id' => 'required|exists:groups,id',
            'day_of_week' => 'required|string',
            'subject' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $this->checkConflict($validated);

        $schedule = Schedule::create($validated);

        // Auto-create a schedule hotspot in this space if one doesn't exist
        $hasHotspot = Hotspot::where('space_id', $validated['space_id'])
            ->where('type', 'schedule')
            ->exists();

        if (!$hasHotspot) {
            Hotspot::create([
                'space_id' => $validated['space_id'],
                'type'     => 'schedule',
                'label'    => 'Planning de la salle',
                'pitch'    => rand(-1500, 500) / 100, // entre -15 et 5 degrees
                'yaw'      => rand(-18000, 18000) / 100,
            ]);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule session created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $spaces = Space::all();
        $trainers = Trainer::all();
        $groups = Group::all();
        
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
        // H:i format pour input type="time"
        $schedule->start_time = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
        $schedule->end_time = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
        
        return view('admin.schedules.edit', compact('schedule', 'spaces', 'trainers', 'groups', 'days'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'trainer_id' => 'required|exists:trainers,id',
            'group_id' => 'required|exists:groups,id',
            'day_of_week' => 'required|string',
            'subject' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $this->checkConflict($validated, $schedule->id);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule session updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    /**
     * Empêche l'enregistrement si une salle ou un formateur est déjà occupé au même horaire.
     */
    private function checkConflict($validated, $ignoreId = null)
    {
        // 1. Vérification Salle Occupée
        $roomOverlap = Schedule::where('space_id', $validated['space_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                // S'il y a un quelconque chevauchement d'horaire
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            });

        if ($ignoreId) {
            $roomOverlap->where('id', '!=', $ignoreId);
        }

        if ($roomOverlap->exists()) {
            throw ValidationException::withMessages([
                'start_time' => 'CONFLIT D\'HORAIRE : Cette salle est déjà occupée sur ce créneau.'
            ]);
        }
        
        // 2. Vérification Formateur Occupé
        $trainerOverlap = Schedule::where('trainer_id', $validated['trainer_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            });
            
        if ($ignoreId) {
            $trainerOverlap->where('id', '!=', $ignoreId);
        }
        
        if ($trainerOverlap->exists()) {
            throw ValidationException::withMessages([
                'trainer_id' => 'CONFLIT D\'HORAIRE : Ce formateur donne déjà un cours ailleurs sur ce créneau.'
            ]);
        }
        
        // 3. Vérification Groupe Occupé
        $groupOverlap = Schedule::where('group_id', $validated['group_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            });
            
        if ($ignoreId) {
            $groupOverlap->where('id', '!=', $ignoreId);
        }
        
        if ($groupOverlap->exists()) {
            throw ValidationException::withMessages([
                'group_id' => 'CONFLIT D\'HORAIRE : Ce groupe assiste déjà à un cours sur ce créneau.'
            ]);
        }
    }
}
