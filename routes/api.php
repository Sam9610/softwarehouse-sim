<?php

use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games/paused', [GameController::class, 'getPausedGame']); // Recupera partita da DB 
Route::get('/games/{game}', [GameController::class, 'getGameState']);
Route::post('/games/new', [GameController::class, 'startNewGame']); // Inizia nuova partita 
Route::get('/hr/market', [GameController::class, 'getMarketCandidates']); // Mostra candidati 
Route::post('/games/{game}/hr/hire', [GameController::class, 'hireEmployee']); // Assume risorsa 
Route::post('/games/{game}/sales/{sales_man}/generate-project', [GameController::class, 'generateProject']);
Route::post('/games/{game}/projects/{project}/assign/{developer}', [GameController::class, 'assignDeveloper']);
Route::post('/games/{game}/update', [GameController::class, 'updateGameState']);