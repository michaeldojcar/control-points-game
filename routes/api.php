<?php

use App\Http\Controllers\Api\OperatorApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Note: there is no security layer on any routes.
 * Everything is unauthenticated.
 */

// Game admin API
Route::get('/games/{id}', [OperatorApiController::class, 'show']);
Route::get('/games/{id}/preparing', [OperatorApiController::class, 'startPreparingGame']);
Route::get('/games/{id}/playing', [OperatorApiController::class, 'gameStarted']);
Route::get('/games/{id}/countdown', [OperatorApiController::class, 'statusCountdown']);
Route::get('/games/{id}/finished', [OperatorApiController::class, 'statusFinished']);
Route::get('/games/{id}/force_ended', [OperatorApiController::class, 'forceEnd']);
// TODO:
Route::get('/games/{id}/audio', 'ControlPointsController@getCurrentSound')->name('controlPoints.current.sound');

//
Route::get('/api/control-points/stopGame', 'ControlPointsController@stopGame');
Route::get('/api/control-points/callSwat', 'ControlPointsController@callSwat');
Route::get('/api/game/{game_id}/ranking', 'ControlPointsController@getRank');
Route::get('/api/game/{game_id}/points', 'ControlPointsController@getCurrentPoints');


