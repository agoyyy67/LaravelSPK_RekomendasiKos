<?php

use App\Http\Controllers\SawController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SawController::class, 'index'])->name('dashboard');
Route::post('/', [SawController::class, 'index'])->name('dashboard.proses');
