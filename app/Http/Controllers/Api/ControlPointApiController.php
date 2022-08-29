<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ControlPointApiException;
use App\Http\Controllers\Controller;
use App\Models\ControlPoint;
use App\Models\ControlPointCapture;
use App\Models\Game;
use App\Models\Player;
use App\Models\Sound;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'rfid' => 'required',
        ]);

        Log::debug($request->input('rfid'));
        Log::debug($id);


        // Load data
        $game  = Game::getCurrentGame();
        $point = ControlPoint::findOrFail($id);

        // RFID
        $rfid = $request->input('rfid');
        $rfid = $this->convertAsciiToRfid($rfid);

        Log::debug($request->input('rfid'));

        $player = Player::where('rfid', $rfid)->firstOrFail();

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



        return new Response($capture, 200);
    }


    private function convertAsciiToRfid($code): string
    {
        $code = substr($code, 1, -1);
        $code = chunk_split($code, 2, ' ');

        $ascii  = $this->parseAsciiToHex($code);
        $substr = substr($ascii, 2, -2);

        return sprintf('%010d', hexdec($substr));
    }

    /**
     * @param $str
     *
     * @return string
     */
    private function parseAsciiToHex($str): string
    {
        $output = ''; // What we will return
        $token  = strtok($str, ' '); // Initialize the tokenizer

        // Loop until there are no more tokens left
        while ($token !== false)
        {
            $output .= chr($token); // Add the token to the output
            $token  = strtok(' '); // Advance the tokenizer, getting the next token
        }

        // All the tokens have been consumed, return the result!
        return $output;
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
        if ($game->status != Game::STATUS_PLAYING  && $game->status != Game::STATUS_FINISH_COUNTDOWN)
        {
            throw new ControlPointApiException('Current game is not in playing state.');
        }
    }
}
