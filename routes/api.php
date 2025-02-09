<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageScanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/packages/{package}/scans', [PackageScanController::class, 'store'])
    ->name('packages.scans.store');;

Route::post('/packages/{package}/update-scans', [PackageScanController::class, 'storeApi'])
    ->name('api.packages.scans.store');
