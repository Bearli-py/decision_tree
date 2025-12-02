<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecisionTreeController;

Route::get('/', [DecisionTreeController::class, 'index'])->name('home'); // UBAH 'index' jadi 'home'
Route::post('/upload', [DecisionTreeController::class, 'upload'])->name('upload');
Route::get('/results/{dataset}', [DecisionTreeController::class, 'results'])->name('results');
Route::get('/api/tree/{dataset}', [DecisionTreeController::class, 'getTreeJson'])->name('api.tree');