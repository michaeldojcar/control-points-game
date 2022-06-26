<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\TestController;
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
Route::get('/', function ()
{
    return redirect()->route('games.index');
});

Route::resource('/games', GameController::class);

Route::get('/test', [TestController::class, 'dashboard']);
