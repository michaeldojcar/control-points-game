<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {


        return view('games.index', [
            'games'           => Game::latest()->get(),
            'current_game' => Game::getCurrentGame(),
        ]);
    }


    public function create()
    {
        //$this->validateThereIsNoPlayingGame();

        $game         = new Game();
        $game->status = Game::STATUS_INIT;
        $game->save();

        return redirect()->route('games.show', $game);
    }


    /**
     * Display the specified resource.
     *
     * @param Game $game
     *
     * @return Application|Factory|View
     */
    public function show(Game $game): View|Factory|Application
    {
        return view('games.show', [
            'game' => $game,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
