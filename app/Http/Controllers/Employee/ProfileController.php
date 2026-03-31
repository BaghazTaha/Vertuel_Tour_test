<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function show()
    {
        // Try to find the employee record linked to the authenticated user
        $employee = Employee::where('user_id', Auth::id())
            ->with('department')
            ->first();

        // If not found (e.g., admin), we can either show a placeholder or handle it
        if (!$employee) {
            return redirect()->route('tour.index')->with('error', 'Employee record not found for your account.');
        }

        return view('employee.profile', compact('employee'));
    }
}
