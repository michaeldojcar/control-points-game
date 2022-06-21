<?php

namespace App\Http\Controllers;

use App\Exceptions\ControlPointApiException;
use App\Models\ControlPoint;
use App\Models\ControlPointCapture;
use App\Models\Game;
use App\Models\Player;
use App\Models\Sound;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ControlPointApiController extends Controller
{
    public function controlPoints()
    {
        return ControlPoint::all()
            ->append([
                'lastCapture',
                'ownerName',
            ]);
    }


    /**
     * @throws \Exception
     */
    public function capture($id, Request $request)
    {
        // Load data
        $game  = Game::getCurrentGame();
        $point = ControlPoint::findOrFail($id);
        $rfid  = $request->input('rfid');
        //$player  = Player::where('rfid', $rfid)->firstOrFail();

        // TODO: debug
        $player = Player::first();

        if ( ! $game)
        {
            throw new ControlPointApiException('There is no running game.');
        }
        $this->validateGameState($game);

        /** @var ControlPointCapture $last */
        $last = $point->getLastCaptureAttribute();

        if ($last)
        {

            $this->validateTeamIsNotSame($last, $player->team);

            $last->setEndOfCapture();
        }

        // Create new capture
        $capture                   = new ControlPointCapture();
        $capture->user_id          = $player->id;
        $capture->team_id          = $player->team->id;
        $capture->game_id          = $game->id;
        $capture->control_point_id = $point->id;
        $capture->date_from        = Carbon::now();
        $capture->save();

        $this->playCaptureSound($player->team, $point, $game);

        return $capture;
    }


    /**
     * @throws \Exception
     */
    private function validateTeamIsNotSame(ControlPointCapture $last, Team $team)
    {
        if ($last->team->id == $team->id)
        {
            throw new ControlPointApiException('Team is same.');
        }
    }


    private function playCaptureSound(Team $team, ControlPoint $point, Game $game)
    {
        $group_name = ucfirst($team->name);

        $path = $group_name . $point->id . '_capture.mp3';

        $sound           = new Sound();
        $sound->game_id  = $game->id;
        $sound->filename = $path;
        $sound->save();
    }


    /**
     * @throws ControlPointApiException
     */
    private function validateGameState(Game $game)
    {
        if ($game->status != Game::STATUS_PLAYING)
        {
            throw new ControlPointApiException('There is no game in playing state.');
        }
    }
}
