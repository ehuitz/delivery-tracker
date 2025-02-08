<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\TerminalController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/terminals', [TerminalController::class, 'index'])->name('terminals.index');

    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');

    Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');

});
