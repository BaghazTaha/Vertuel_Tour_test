<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // List sessions and their attendances
    public function index(Request $request)
    {
        // Simple list showing all schedules grouped by date that have attendances recorded
        $attendancesData = Attendance::with(['schedule.trainer', 'schedule.group'])
            ->select('schedule_id', 'date', 
                DB::raw('count(case when status = "present" then 1 end) as present_count'),
                DB::raw('count(case when status = "absent" then 1 end) as absent_count'),
                DB::raw('count(case when status = "late" then 1 end) as late_count'),
                DB::raw('MAX(is_validated) as is_validated')
            )
            ->groupBy('schedule_id', 'date')
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Required data to allow admin to view/edit individual attendances in a modal/view
        $allAttendances = null;
        $selectedSchedule = null;
        $selectedDate = null;

        if ($request->has('schedule_id') && $request->has('date')) {
            $selectedSchedule = Schedule::findOrFail($request->schedule_id);
            $selectedDate = $request->date;
            $allAttendances = Attendance::with('student')->where('schedule_id', $selectedSchedule->id)
                ->where('date', $selectedDate)
                ->get();
        }

        return view('admin.attendances.index', compact('attendancesData', 'allAttendances', 'selectedSchedule', 'selectedDate'));
    }

    // Validate a session's attendances
    public function validateSession(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date'
        ]);

        Attendance::where('schedule_id', $request->schedule_id)
            ->where('date', $request->date)
            ->update([
                'is_validated' => true,
                'validated_at' => Carbon::now(),
                'validated_by' => Auth::id()
            ]);

        return back()->with('success', 'Présences validées avec succès.');
    }

    // Admin updates a single attendance (for justification/status change)
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late',
            'justification' => 'nullable|string'
        ]);

        $attendance->update([
            'status' => $request->status,
            'justification' => $request->justification
        ]);

        return back()->with('success', 'Présence mise à jour avec justification.');
    }

    // Statistics about absences
    public function stats(Request $request)
    {
        // Simple aggregate for absence rates
        // Taux par séance (par schedule)
        $absenceRateBySchedule = Attendance::select('schedule_id', 
                DB::raw('count(case when status = "absent" then 1 end) as total_absences'),
                DB::raw('count(*) as total_records')
            )
            ->with(['schedule', 'schedule.group', 'schedule.trainer'])
            ->groupBy('schedule_id')
            ->get()->map(function($item) {
                $item->rate = $item->total_records > 0 ? ($item->total_absences / $item->total_records) * 100 : 0;
                return $item;
            });

        return view('admin.attendances.stats', compact('absenceRateBySchedule'));
    }
}
