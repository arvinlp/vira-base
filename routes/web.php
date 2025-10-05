<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\ClientController;
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

Route::group(['prefix' => 'panel', 'middleware' => 'auth:web', 'as' => 'panel.'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('staffs')->name('staffs.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::post('/', [StaffController::class, 'store'])->name('store');
        Route::patch('/{staff}', [StaffController::class, 'update'])->name('update');
        Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
        Route::patch('/{staff}/password', [StaffController::class, 'updatePassword'])->name('update.password');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::patch('/{staff}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{staff}', [ClientController::class, 'destroy'])->name('destroy');
        Route::patch('/{staff}/password', [ClientController::class, 'updatePassword'])->name('update.password');
    });

    Route::resource('tenants', ProfileController::class);
    Route::resource('plans', ProfileController::class);
});
require __DIR__ . '/auth.php';
