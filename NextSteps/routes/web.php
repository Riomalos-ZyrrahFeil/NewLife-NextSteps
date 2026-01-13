<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\VisitorController;
use App\Models\User;
use App\Http\Controllers\Admin\GuestTrackerController;
use App\Http\Controllers\SettingsController;

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

  Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);

    // Visitor Tracker
    Route::get('/visitors', [VisitorController::class, 'index'])
      ->name('visitors.index');
    Route::post('/visitors/import', [VisitorController::class, 'import'])
      ->name('visitors.import');

    // Handle Assignment & Unassignment
    Route::post('/visitors/assign', [VisitorController::class, 'assign'])
      ->name('visitors.assign');

    // Volunteer Search for Modal
    Route::get('/volunteers/search', [UserController::class, 'search'])
      ->name('volunteers.search');

    // Guest Tracker
    Route::get('/guest-tracker', [GuestTrackerController::class, 'index'])
      ->name('guest_tracker.index');
    Route::post('/guest-tracker/status', [GuestTrackerController::class, 'updateStatus'])
        ->name('guest_tracker.status');

    // BACKUP & RESTORE
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/restore', [SettingsController::class, 'restore'])->name('settings.restore');
    
    // General Config
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.deleteAccount');
  });

  Route::get('/assigned-guests', [VisitorController::class, 'assignedList'])
    ->name('assigned.guests');
});