<?php
// app/Http/Controllers/Admin/HotspotController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Hotspot;
use App\Models\Space;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    public function index(Space $space)
    {
        $hotspots  = $space->hotspots()->with(['employee', 'targetScene', 'trainer'])->get();
        $employees = Employee::orderBy('first_name')->get();
        $trainers  = \App\Models\Trainer::orderBy('first_name')->get();
        $scenes    = Space::where('id', '!=', $space->id)->orderBy('name')->get();

        return view('admin.hotspots.index', compact('space', 'hotspots', 'employees', 'trainers', 'scenes'));
    }

    public function store(Request $request, Space $space)
    {
        $data = $request->validate([
            'type'            => 'required|in:employee,scene,trainer,schedule',
            'pitch'           => 'required|numeric|between:-90,90',
            'yaw'             => 'required|numeric|between:-180,180',
            'label'           => 'nullable|string|max:100',
            'employee_id'     => 'required_if:type,employee|nullable|exists:employees,id',
            'trainer_id'      => 'required_if:type,trainer|nullable|exists:trainers,id',
            'target_scene_id' => 'required_if:type,scene|nullable|exists:spaces,id',
        ]);

        $space->hotspots()->create($data);

        return back()->with('success', 'Hotspot added successfully.');
    }

    public function update(Request $request, Space $space, Hotspot $hotspot)
    {
        $data = $request->validate([
            'type'            => 'required|in:employee,scene,trainer,schedule',
            'pitch'           => 'required|numeric|between:-90,90',
            'yaw'             => 'required|numeric|between:-180,180',
            'label'           => 'nullable|string|max:100',
            'employee_id'     => 'required_if:type,employee|nullable|exists:employees,id',
            'trainer_id'      => 'required_if:type,trainer|nullable|exists:trainers,id',
            'target_scene_id' => 'required_if:type,scene|nullable|exists:spaces,id',
        ]);

        $hotspot->update($data);

        return back()->with('success', 'Hotspot updated successfully.');
    }

    public function destroy(Space $space, Hotspot $hotspot)
    {
        $hotspot->delete();

        return back()->with('success', 'Hotspot deleted successfully.');
    }
}