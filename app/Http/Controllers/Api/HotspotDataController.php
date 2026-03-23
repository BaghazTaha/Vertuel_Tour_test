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
}
