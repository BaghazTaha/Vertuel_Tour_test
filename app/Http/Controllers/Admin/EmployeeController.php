<?php
// app/Http/Controllers/Admin/EmployeeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with('department')
            ->latest()
            ->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.employees.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'matricule'     => 'required|string|max:100|unique:employees,matricule',
            'job_title'     => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email'         => 'required|email|unique:employees,email|unique:users,email',
            'phone'         => 'nullable|string|max:50',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
        }

        // Create linked user account
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make('password'),
            'role'     => 'employee',
        ]);
        $user->assignRole('employee');

        // Create employee
        $employee = Employee::create([
            'user_id'       => $user->id,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'matricule'     => $request->matricule,
            'job_title'     => $request->job_title,
            'department_id' => $request->department_id,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'photo'         => $photoPath,
        ]);

        // Generate QR code
        $qrPath = $this->generateQr($employee);
        $employee->update(['qr_code_path' => $qrPath]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load('department');
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'matricule'     => 'required|string|max:100|unique:employees,matricule,' . $employee->id,
            'job_title'     => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email'         => 'required|email|unique:employees,email,' . $employee->id
                               . '|unique:users,email,' . $employee->user_id,
            'phone'         => 'nullable|string|max:50',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'matricule',
            'job_title', 'department_id', 'email', 'phone',
        ]);

        // Photo upload
        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($data);

        // Regenerate QR if matricule or name changed
        $qrPath = $this->generateQr($employee->fresh());
        $employee->update(['qr_code_path' => $qrPath]);

        // Sync linked user
        if ($employee->user) {
            $employee->user->update([
                'name'  => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        // Delete photo
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Delete QR
        if ($employee->qr_code_path) {
            Storage::disk('public')->delete($employee->qr_code_path);
        }

        // Delete linked user
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /* -------------------------------------------------- */

    private function generateQr(Employee $employee): string
    {
        $url = route('employee.public', $employee->matricule);

        $folder = 'employees/qrcodes';
        Storage::disk('public')->makeDirectory($folder);

        $filename = $folder . '/qr-' . $employee->matricule . '.svg';

        $svg = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($url);

        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }
}