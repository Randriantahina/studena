<?php

use App\Http\Controllers\MatchmakingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MatchmakingController::class, 'index'])->name('matchmaking.index');
Route::get('/matchmaking/{student}', [MatchmakingController::class, 'show'])->name('matchmaking.show');
Route::get('/api/matchmaking', [MatchmakingController::class, 'apiIndex'])->name('api.matchmaking.index');
