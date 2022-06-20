<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperatorApiController extends Controller
{
    public function show($id)
    {
        $game = Game::findOrFail($id);

        return $game;
    }


    public function startPreparingGame($id)
    {
        $game         = Game::findOrFail($id);
        $game->status = Game::STATUS_PREPARING;
        $game->save();

        return $game;
    }

    public function gameStarted($id)
    {
        $game         = Game::findOrFail($id);
        $game->status = Game::STATUS_PLAYING;
        $game->started_at = Carbon::now();
        $game->save();

        return $game;
    }


    public function statusCountdown($id)
    {
        $game         = Game::findOrFail($id);
        $game->status = Game::STATUS_FINISH_COUNTDOWN;
        $game->save();

        return $game;
    }

    public function statusFinished($id)
    {
        $game         = Game::findOrFail($id);
        $game->status = Game::STATUS_FINISHED;
        $game->finished_at = Carbon::now();
        $game->save();

        return $game;
    }


    public function forceEnd($id)
    {
        $game         = Game::findOrFail($id);
        $game->status = Game::STATUS_FORCE_EXITED;
        $game->finished_at = Carbon::now();
        $game->save();

        return $game;
    }
}
