<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\GuestTrackerController;
use App\Http\Controllers\Volunteer\AssignedGuestController;
use App\Models\User;

// --- AUTHENTICATION ROUTES ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        $users = User::all();
        return view('dashboard', compact('users'));
    })->name('dashboard');

    // --- ADMIN ROUTES ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        // Visitor Tracker
        Route::get('/visitors', [VisitorController::class, 'index'])
            ->name('visitors.index');
        Route::post('/visitors/import', [VisitorController::class, 'import'])
            ->name('visitors.import');

        // Assignment Logic
        Route::post('/visitors/assign', [VisitorController::class, 'assign'])
            ->name('visitors.assign');

        // Search for Modal
        Route::get('/volunteers/search', [UserController::class, 'search'])
            ->name('volunteers.search');

        // Guest Tracker
        Route::get('/guest-tracker', [GuestTrackerController::class, 'index'])
            ->name('guest_tracker.index');
        Route::post('/guest-tracker/status', [GuestTrackerController::class, 'updateStatus'])
            ->name('guest_tracker.status');
    });

    // --- VOLUNTEER ROUTES ---
    Route::middleware(['role:volunteer'])
        ->prefix('volunteer')
        ->name('volunteer.')
        ->group(function () {
            
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            // Assigned Guests Logic
            Route::get('/assigned-guests', [AssignedGuestController::class, 'index'])
                ->name('assigned_guests.index');
            
            Route::post('/assigned-guests/status', [AssignedGuestController::class, 'updateStatus'])
                ->name('assigned_guests.status');
        });
});