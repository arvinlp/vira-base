<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\StaffController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['prefix' => 'panel', 'middelware' => 'auth:web', 'as' => 'panel.'], function () {

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::prefix('/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::resource('staffs', StaffController::class);
    Route::resource('clients', ProfileController::class);
    Route::resource('tenants', ProfileController::class);
    Route::resource('plans', ProfileController::class);
});
require __DIR__ . '/auth.php';
