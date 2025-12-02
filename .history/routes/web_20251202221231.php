<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecisionTreeController;

Route::get('/', [DecisionTreeController::class, 'index']);
Route::post('/upload', [DecisionTreeController::class, 'upload']);
Route::get('/results', [DecisionTreeController::class, 'results']);
