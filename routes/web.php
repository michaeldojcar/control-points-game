<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Game admin GUI
Route::get('/', [GameController::class, 'renderOperator'])->name('controlPoints.operator');
Route::get('/games', [GameController::class, 'renderGames'])->name('controlPoints.games');
Route::get('/game/{id}', [GameController::class, 'renderGame'])->name('controlPoints.game');

// API routes
// TODO: move to api.php
Route::get('/api/control-points/audio/current', 'ControlPointsController@getCurrentSound')->name('controlPoints.current.sound');
Route::get('/api/control-points/callSwat', 'ControlPointsController@callSwat')->name('controlPoints.current.sound');
Route::get('/api/control-points/game/{game_id}/points', 'ControlPointsController@getCurrentPoints')
    ->name('controlPoints.current.sound');
Route::get('/api/control-points/game/{game_id}/rank', 'ControlPointsController@getRank')->name('controlPoints.current.sound');
Route::get('/api/control-points/stopGame', 'ControlPointsController@stopGame')->name('controlPoints.current.sound');
Route::get('/api/control-points/setNewGame', 'ControlPointsController@setNewGameId')->name('controlPoints.current.sound');

