<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('group')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.students.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'group_id' => 'required|exists:groups,id',
            'photo' => 'nullable|image|max:2048'
        ]);

        $email = User::generateEmail($validated['first_name'], $validated['last_name']);
        $validated['email'] = $email;

        $group = Group::withCount('students')->findOrFail($validated['group_id']);
        if ($group->students_count >= $group->max_capacity) {
            throw ValidationException::withMessages([
                'group_id' => 'Ce groupe a déjà atteint sa capacité maximale ('.$group->max_capacity.' étudiants).'
            ]);
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        // Create linked user account
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $email,
            'password' => Hash::make('password'), // default password
            'role'     => 'student',
            'must_change_password' => true,
        ]);
        $user->assignRole('student');

        $validated['user_id'] = $user->id;

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        return redirect()->route('admin.students.index');
    }

    public function edit(Student $student)
    {
        $groups = Group::all();
        return view('admin.students.edit', compact('student', 'groups'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'group_id' => 'required|exists:groups,id',
            'photo' => 'nullable|image|max:2048'
        ]);

        $email = User::generateEmail($validated['first_name'], $validated['last_name']);
        $validated['email'] = $email;

        if ($validated['group_id'] != $student->group_id) {
            $group = Group::withCount('students')->findOrFail($validated['group_id']);
            if ($group->students_count >= $group->max_capacity) {
                throw ValidationException::withMessages([
                    'group_id' => 'Ce groupe a déjà atteint sa capacité maximale ('.$group->max_capacity.' étudiants).'
                ]);
            }
        }

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($validated);

        // Update linked user
        if ($student->user) {
            $student->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $email,
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) Storage::disk('public')->delete($student->photo);
        
        // Delete linked user
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
