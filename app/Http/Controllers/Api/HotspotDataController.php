<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\Space;
use App\Models\Schedule;

class HotspotDataController extends Controller
{
    public function getTrainerInfo($trainerId)
    {
        $trainer = Trainer::find($trainerId);
        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }

        $trainer->load('department');
        return response()->json([
            'id' => $trainer->id,
            'name' => $trainer->first_name . ' ' . $trainer->last_name,
            'specialty' => $trainer->specialty,
            'email' => $trainer->email,
            'phone' => $trainer->phone,
            'dept' => $trainer->department ? $trainer->department->name : 'Non assigné',
            'photo' => $trainer->photo ? asset('storage/' . $trainer->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($trainer->first_name . ' ' . $trainer->last_name) . '&background=a855f7&color=fff',
        ]);
    }

    public function getSpaceSchedules($spaceId)
    {
        $space = Space::find($spaceId);
        if (!$space) {
            return response()->json(['error' => 'Space not found'], 404);
        }

        $schedules = Schedule::with(['trainer', 'group'])
            ->where('space_id', $space->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        return response()->json($schedules);
    }

    public function mapData()
    {
        $spaces = Space::with('department')->get();
        
        // Group spaces by floor
        $floors = $spaces->groupBy('floor')->map(function ($floorSpaces) {
            return $floorSpaces->map(function ($space) {
                return [
                    'id' => $space->id,
                    'name' => $space->name,
                    'department' => $space->department ? $space->department->name : 'Public',
                ];
            })->values();
        });

        return response()->json($floors);
    }

    public function getLiveSchedule($spaceId)
    {
        $space = Space::find($spaceId);
        if (!$space) {
            return response()->json(['error' => 'Space not found'], 404);
        }

        $now = \Carbon\Carbon::now();
        $englishDay = strtolower($now->englishDayOfWeek);
        $frenchDay = strtolower($now->locale('fr')->dayName);
        $arabicDay = strtolower($now->locale('ar')->dayName);
        $currentTime = $now->format('H:i:s');

        $activeSchedule = Schedule::with(['trainer', 'group'])
            ->where('space_id', $space->id)
            ->where(function ($query) use ($englishDay, $frenchDay, $arabicDay) {
                $query->where('day_of_week', 'like', "%$englishDay%")
                      ->orWhere('day_of_week', 'like', "%$frenchDay%")
                      ->orWhere('day_of_week', 'like', "%$arabicDay%");
            })
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->first();

        if ($activeSchedule) {
            // Determine time remaining safely
            $endTime = \Carbon\Carbon::parse($activeSchedule->end_time);
            $mins = $now->diffInMinutes($endTime, false);
            
            return response()->json([
                'active' => true,
                'subject' => $activeSchedule->subject,
                'trainer_name' => $activeSchedule->trainer ? $activeSchedule->trainer->first_name . ' ' . $activeSchedule->trainer->last_name : 'No Trainer',
                'group_name' => $activeSchedule->group ? $activeSchedule->group->name : 'General',
                'time_remaining' => $mins > 0 ? $mins . ' ' . __('mins remaining') : __('Ending soon')
            ]);
        }

        return response()->json(['active' => false]);
    }
}
