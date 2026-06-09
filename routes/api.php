<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\PelamarController;

Route::get('/lowongan', [LowonganController::class, 'index']);
Route::get('/lowongan/{id}', [LowonganController::class, 'show']);

Route::post('/lowongan/{id}/lamar', [PelamarController::class, 'store']);

Route::get('/tracking-pelamar', [PelamarController::class, 'tracking']);
