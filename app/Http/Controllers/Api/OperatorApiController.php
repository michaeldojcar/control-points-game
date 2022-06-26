<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ControlPoint;
use App\Models\Game;
use App\Models\Sound;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperatorApiController extends Controller
{
    public function show($id)
    {
        $game = Game::findOrFail($id);

        return $game;
    }


    public function teams($game_id)
    {
        $game  = Game::findOrFail($game_id);
        $teams = Team::all();

        $team_collection = collect($teams);

        foreach ($team_collection as $team)
        {
            $team->seconds = (int)$team->getCapturedSeconds($game);
        }

        return $team_collection->sortByDesc('seconds')->values();
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
        $game             = Game::findOrFail($id);
        $game->status     = Game::STATUS_PLAYING;
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
        $game              = Game::findOrFail($id);
        $game->status      = Game::STATUS_FINISHED;
        $game->finished_at = Carbon::now();
        $game->save();

        // TODO: close pending captures

        return $game;
    }


    public function forceEnd($id)
    {
        $game              = Game::findOrFail($id);
        $game->status      = Game::STATUS_FORCE_EXITED;
        $game->finished_at = Carbon::now();
        $game->save();

        // TODO: close pending captures

        return $game;
    }


    public function getCurrentSound($id)
    {
        $sound = Sound::where('game_id', Game::findOrFail($id)->id)
            ->where('played', false)
            ->oldest()
            ->first();

        if ( ! $sound)
        {
            return null;
        }

        $sound->played = true;
        $sound->save();

        return $sound;
    }
}
