<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Jika user sudah login, redirect ke dashboard berdasarkan role
    if (Auth::check()) {
        $user = Auth::user();
        
        // Redirect berdasarkan role user
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index');
        } elseif ($user->role === 'leader_teknisi') {
            return redirect()->route('maintenance.dashboard');
        } elseif ($user->role === 'leader_operator') {
            return redirect()->route('operator.dashboard');
        }
        
        // Untuk role lainnya, redirect ke maintenance dashboard
        return redirect()->route('maintenance.dashboard');
    }
    
    // Jika belum login, redirect ke login page
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin user management routes - only accessible by admin role
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/breakdown.php';
