<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class TrainerController extends Controller
{
    public function index()
    {
        // On récupère les formateurs avec leur département
        $trainers = Trainer::with('department')->paginate(10);
        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.trainers.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|max:2048',
            'sex' => 'nullable|in:male,female',
        ]);

        $email = User::generateEmail($validated['first_name'], $validated['last_name']);
        $validated['email'] = $email;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        // Create linked user account
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $email,
            'password' => Hash::make('password'), // default password
            'role'     => 'trainer',
            'sex'      => $request->sex,
            'must_change_password' => true,
        ]);
        $user->assignRole('trainer');

        $validated['user_id'] = $user->id;

        Trainer::create($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function show(Trainer $trainer)
    {
        return redirect()->route('admin.trainers.index');
    }

    public function edit(Trainer $trainer)
    {
        $departments = Department::all();
        return view('admin.trainers.edit', compact('trainer', 'departments'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|max:2048',
            'sex' => 'nullable|in:male,female',
        ]);

        $email = User::generateEmail($validated['first_name'], $validated['last_name']);
        $validated['email'] = $email;

        if ($request->hasFile('photo')) {
            if ($trainer->photo) Storage::disk('public')->delete($trainer->photo);
            $validated['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        $trainer->update($validated);

        // Update linked user
        if ($trainer->user) {
            $trainer->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $email,
                'sex' => $request->sex,
            ]);
        }

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Trainer $trainer)
    {
        if ($trainer->photo) Storage::disk('public')->delete($trainer->photo);
        
        // Delete linked user
        if ($trainer->user) {
            $trainer->user->delete();
        }

        $trainer->delete();

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}
