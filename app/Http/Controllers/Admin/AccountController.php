<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::with(['trainer', 'student', 'employee'])->paginate(15);
        return view('admin.accounts.index', compact('users'));
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password'),
            'must_change_password' => true
        ]);

        return back()->with('success', 'Password reset to "password" and forced change enabled.');
    }

    public function toggleMustChangePassword(User $user)
    {
        $user->update(['must_change_password' => !$user->must_change_password]);
        return back()->with('success', 'Password change requirement toggled.');
    }

    public function destroy(User $user)
    {
        // Careful with deleting users, should probably handle linked entities
        if ($user->trainer) $user->trainer->delete();
        if ($user->student) $user->student->delete();
        if ($user->employee) $user->employee->delete();
        
        $user->delete();
        return back()->with('success', 'User and linked profiles deleted.');
    }
}
