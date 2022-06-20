<?php

namespace App\Http\Controllers;

use App\Models\ControlPointCapture;
use App\Models\Game;
use App\Models\Sound;
use App\Models\Team;
use App\Models\ControlPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GameControllerLegacy extends Controller
{


    /**
     * Admin dashboard.
     */
    public function renderOperator()
    {
        $games = Game::all();

        return view('operator', ['games' => $games]);
    }


    /**
     * Výpis sehrávek.
     */
    public function renderGames()
    {
        $games = ControlPointCapture::select(['game_id'])->distinct()->get();

        return view('games', ['games' => $games]);
    }


    /**
     * Výpis sehrávek.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderGame($id)
    {
        $groups     = $this->getRank($id)[0];
        $ranks      = $this->getRank($id)[1];
        $created_at = $this->getGameDate($id);

        return view('game', [
            'game_id'    => $id,
            'groups'     => $groups,
            'ranks'      => $ranks,
            'created_at' => $created_at,
        ]);
    }


    /**
     * Přijme záznam při řezačce.
     */
    public function saveCapture($card_num)
    {
        $game_id = $this->getCurrentGameId();

        try
        {
            $user = User::where('card', $card_num)->firstOrFail();
        } catch (ModelNotFoundException $exception)
        {
            // echo "neexistuje";

            return false;
        }

        $user_id  = $user->id;
        $group_id = $user->group_id;

        try
        {
            $point = Reader::where('address', $_SERVER['REMOTE_ADDR'])->firstOrFail()->id;
        } catch (ModelNotFoundException $e)
        {
            return false;
        }


        //echo $_SERVER['REMOTE_ADDR'];

        $this->lockPendingCapture($point);

        //echo $group_id;
        //echo $this->getCurrentOwner($game_id, $point);

        if ($this->getCurrentOwnerId($game_id, $point) == $group_id)
        {
            // echo "stejná skupinka";

            return false;
        }
        else
        {
            ControlPointCapture::create([
                'user_id'  => $user_id,
                'group_id' => $group_id,
                'game_id'  => $game_id,
                'point'    => $point,
            ]);

            $this->playCaptureSound($group_id, $point);

            return true;
        }


    }


    public function setNewGameId()
    {
        $current_id = $this->getCurrentGameId();

        $state        = State::where('option', 'cp_current')->first();
        $state->value = $current_id + 1;
        $state->save();

        return $state->value;
    }


    public function getCurrentGameId()
    {
//        return $current_id = ControlPointCapture::all()->sortByDesc('id')->first()->game_id;
        return State::where('option', 'cp_current')->first()->value;
    }


    /**
     * Vrátí aktuální nepřehraný sound který je na řadě, nebo null.
     * Vrácený sound označí jako played.
     */
    public function getCurrentSound()
    {
        $sounds = ControlPointSound::all()->where('played', '0')->sortKeys();

        if ( ! $sounds->isEmpty())
        {
            $sound = $sounds->first();

            $sound->played = 1;
            $sound->save();

            return $sound->filename;
        }
        else
        {
            return 0;
        }
    }


    /**
     * Přidá do fronty řezačky novou hlášku.
     *
     * @param $sound
     */
    public function setSound($sound)
    {
        ControlPointSound::create(['filename' => $sound, 'played' => '0']);
    }


    public function callSwat()
    {
        $this->setSound('zasahovka.mp3');
    }


    public function getCurrentPoints($game_id)
    {
        $a = $this->getCurrentOwner($game_id, 1);
        $b = $this->getCurrentOwner($game_id, 2);
        $c = $this->getCurrentOwner($game_id, 3);
        $d = $this->getCurrentOwner($game_id, 4);
        $e = $this->getCurrentOwner($game_id, 5);
        $f = $this->getCurrentOwner($game_id, 6);

        $points = [$a, $b, $c, $d, $e, $f];

        return $points;
    }


    public function getCurrentOwner($game_id, $point)
    {

        $captures = ControlPointCapture::where('point', $point)->where('game_id', $game_id)->orderByDesc('created_at')->get();

        if ( ! $captures->isEmpty())
        {
            return $captures->first()->group->name;
        }
        else
        {
            return 'nikdo';
        }


    }


    public function getCurrentOwnerId($game_id, $point)
    {

        $captures = ControlPointCapture::where('point', $point)->where('game_id', $game_id)->orderByDesc('created_at')->get();

        if ( ! $captures->isEmpty())
        {
            return $captures->first()->group->id;
        }
        else
        {
            return 'nikdo';
        }


    }


    /**
     * Jméno, celkový čas v sec.
     *
     * @param $game
     *
     * @return array
     */
    public function getRank($game)
    {
        $game_id = $game;

        $alfa    = $this->getGroupCaptureSeconds($game_id, 1);
        $beta    = $this->getGroupCaptureSeconds($game_id, 2);
        $gama    = $this->getGroupCaptureSeconds($game_id, 3);
        $delta   = $this->getGroupCaptureSeconds($game_id, 4);
        $epsilon = $this->getGroupCaptureSeconds($game_id, 5);
        $omikron = $this->getGroupCaptureSeconds($game_id, 6);
        $omega   = $this->getGroupCaptureSeconds($game_id, 7);

        $points = [$alfa, $beta, $gama, $delta, $epsilon, $omikron, $omega];
        $groups = ['alfa', 'beta', 'gama', 'delta', 'epsilon', 'omikron', 'omega'];

        array_multisort($points, SORT_ASC, $groups);

        return [$groups, $points];
    }


    private function getGroupCaptureSeconds($game_id, $group_id)
    {
        $captures = ControlPointCapture::where('group_id', $group_id)->where('game_id', $game_id)->get();

        $secs = 0;
        foreach ($captures as $capture)
        {
            $secs = $secs + $capture->getLength();
        }

        return $secs;
    }


    private function getGameDate($game_id)
    {
        return ControlPointCapture::where('game_id', $game_id)->first()->created_at;
    }


    public function stopGame()
    {
        $st = new StateController();
        $st->setCalm();

        // Uzavře neuzavřené captures.
        ControlPointCapture::where('end_at', null)->update(['end_at' => date("Y-m-d H:i:s")]);
    }


    private function lockPendingCapture($point)
    {
        ControlPointCapture::where('point', $point)->where('end_at', null)->update(['end_at' => date("Y-m-d H:i:s")]);
    }


    private function playCaptureSound($group_id, $point)
    {
        $group_name = Group::findOrFail($group_id)->name;
        $group_name = ucfirst($group_name);

        $path = $group_name . $point . '_capture.mp3';

        $this->setSound($path);
    }

}
