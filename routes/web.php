// routes/web.php
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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
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

        // Hotspot routes (nested under spaces)
        Route::get('/spaces/{space}/hotspots', [HotspotController::class, 'index'])->name('spaces.hotspots.index');
        Route::post('/spaces/{space}/hotspots', [HotspotController::class, 'store'])->name('spaces.hotspots.store');
        Route::put('/spaces/{space}/hotspots/{hotspot}', [HotspotController::class, 'update'])->name('spaces.hotspots.update');
        Route::delete('/spaces/{space}/hotspots/{hotspot}', [HotspotController::class, 'destroy'])->name('spaces.hotspots.destroy');
    });

// Tour routes
Route::middleware(['auth', 'role:employee|admin'])
    ->prefix('tour')
    ->name('tour.')
    ->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'show'])->name('profile');
    });

// Public QR profile — no login required
Route::get('/employee/{matricule}', [EmployeePublicController::class, 'show'])
    ->name('employee.public');



