<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;

Route::get('/', [TreeController::class, 'home'])->name('home');
Route::get('/dataset', [TreeController::class, 'dataset'])->name('dataset');
Route::get('/tree', [TreeController::class, 'tree'])->name('tree');
Route::get('/predict', [TreeController::class, 'predict'])->name('predict');
Route::post('/predict', [TreeController::class, 'doPrediction'])->name('doPrediction');
Route::get('/comparison', [TreeController::class, 'comparison'])->name('comparison');