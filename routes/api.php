<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\PelamarController;
use App\Http\Controllers\Api\AuthController;

Route::get('/lowongan', [LowonganController::class, 'index']);
Route::get('/lowongan/{id}', [LowonganController::class, 'show']);

Route::post('/lowongan/{id}/lamar', [PelamarController::class, 'store']);

Route::get('/tracking-pelamar', [PelamarController::class, 'tracking']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
