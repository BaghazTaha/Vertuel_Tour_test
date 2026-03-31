<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Hotspot;
use App\Models\Space;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'employees'   => Employee::count(),
            'departments' => Department::count(),
            'spaces'      => Space::count(),
            'hotspots'    => Hotspot::count(),
        ];

        $recentEmployees = Employee::with('department')
            ->latest()
            ->take(5)
            ->get();

        $recentSpaces = Space::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEmployees', 'recentSpaces'));
    }
}