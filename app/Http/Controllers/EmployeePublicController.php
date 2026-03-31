<?php
// app/Http/Controllers/EmployeePublicController.php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;

class EmployeePublicController extends Controller
{
    public function show(string $matricule): View
    {
        $employee = Employee::where('matricule', $matricule)
            ->with('department')
            ->firstOrFail();

        return view('employee.public', compact('employee'));
    }
}