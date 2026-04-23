<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\SpaceController;
use App\Http\Controllers\Admin\HotspotController;
use App\Http\Controllers\Tour\TourController;
use App\Http\Controllers\EmployeePublicController;

// Root redirect — check auth first to avoid loop
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tour.index');
    }
    return redirect()->route('login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr', 'ar'])) {
        session()->put('locale', $locale);
        session()->save();
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/debug-lang', function () {
    return [
        'locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'translation' => __('Dashboard'),
        'config_locale' => config('app.locale'),
    ];
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Password change (forced)
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [App\Http\Controllers\Auth\PasswordController::class, 'edit'])->name('password.change');
    Route::put('/change-password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('departments', DepartmentController::class);
        Route::resource('employees',   EmployeeController::class);
        Route::resource('spaces',      SpaceController::class);

        Route::resource('groups',      \App\Http\Controllers\Admin\GroupController::class);
        Route::resource('students',    \App\Http\Controllers\Admin\StudentController::class);
        Route::resource('trainers',    \App\Http\Controllers\Admin\TrainerController::class);
        Route::resource('schedules',   \App\Http\Controllers\Admin\ScheduleController::class);

        // Account Management
        Route::get('/accounts', [\App\Http\Controllers\Admin\AccountController::class, 'index'])->name('accounts.index');
        Route::post('/accounts/{user}/reset-password', [\App\Http\Controllers\Admin\AccountController::class, 'resetPassword'])->name('accounts.reset-password');
        Route::post('/accounts/{user}/toggle-must-change', [\App\Http\Controllers\Admin\AccountController::class, 'toggleMustChangePassword'])->name('accounts.toggle-must-change');
        Route::delete('/accounts/{user}', [\App\Http\Controllers\Admin\AccountController::class, 'destroy'])->name('accounts.destroy');

        // Hotspot routes (nested under spaces)
        Route::get('/spaces/{space}/hotspots', [HotspotController::class, 'index'])->name('spaces.hotspots.index');
        Route::post('/spaces/{space}/hotspots', [HotspotController::class, 'store'])->name('spaces.hotspots.store');
        Route::put('/spaces/{space}/hotspots/{hotspot}', [HotspotController::class, 'update'])->name('spaces.hotspots.update');
        Route::delete('/spaces/{space}/hotspots/{hotspot}', [HotspotController::class, 'destroy'])->name('spaces.hotspots.destroy');
    });

// API routes for hotspots
Route::middleware(['auth', 'role:employee|admin|trainer|student'])
    ->prefix('api')
    ->group(function () {
        Route::get('/trainer/{trainer}', [\App\Http\Controllers\Api\HotspotDataController::class, 'getTrainerInfo']);
        Route::get('/space/{space}/schedules', [\App\Http\Controllers\Api\HotspotDataController::class, 'getSpaceSchedules']);
        Route::get('/spaces/map-data', [\App\Http\Controllers\Api\HotspotDataController::class, 'mapData']);
        Route::get('/space/{space}/live', [\App\Http\Controllers\Api\HotspotDataController::class, 'getLiveSchedule']);
        Route::get('/my-schedule', [\App\Http\Controllers\Api\HotspotDataController::class, 'getMySchedule']);
    });

// Tour routes
Route::middleware(['auth', 'role:employee|admin|trainer|student'])
    ->prefix('tour')
    ->name('tour.')
    ->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'show'])->name('profile');
    });

// Public QR profile — no login required
Route::get('/employee/{matricule}', [EmployeePublicController::class, 'show'])
    ->name('employee.public');



