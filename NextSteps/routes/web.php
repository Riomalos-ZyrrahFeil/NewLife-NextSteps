<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
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

    Route::resource('admin/users', UserController::class)
        ->except(['show'])
        ->names('admin.users'); 
});