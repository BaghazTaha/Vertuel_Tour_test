<?php
// app/Http/Controllers/Tour/TourController.php

namespace App\Http\Controllers\Tour;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): View
    {
        $spaces = Space::with(['hotspots.targetScene', 'hotspots.employee.department', 'hotspots.trainer'])->get();
        if ($spaces->isEmpty()) {
            return view('tour.index', ['spaces' => $spaces, 'firstSpace' => null]);
        }
        
        return view('tour.index', [
            'spaces' => $spaces,
            'firstSpace' => $spaces->first(),
        ]);
    }
}