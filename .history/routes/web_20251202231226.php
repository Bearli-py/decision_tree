<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecisionTreeController;

Route::get('/', [DecisionTreeController::class, 'index'])->name('home');
Route::post('/upload', [DecisionTreeController::class, 'upload'])->name('upload');
Route::get('/results', [DecisionTreeController::class, 'results'])->name('results');
