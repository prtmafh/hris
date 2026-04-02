<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin/dashboard');
});
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
