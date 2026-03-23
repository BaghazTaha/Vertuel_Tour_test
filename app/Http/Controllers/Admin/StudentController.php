<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:255',
            'group_id' => 'required|exists:groups,id',
            'photo' => 'nullable|image|max:2048'
        ]);

        $group = Group::withCount('students')->findOrFail($validated['group_id']);
        if ($group->students_count >= $group->max_capacity) {
            throw ValidationException::withMessages([
                'group_id' => 'Ce groupe a déjà atteint sa capacité maximale ('.$group->max_capacity.' étudiants).'
            ]);
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

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
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:255',
            'group_id' => 'required|exists:groups,id',
            'photo' => 'nullable|image|max:2048'
        ]);

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

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) Storage::disk('public')->delete($student->photo);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
