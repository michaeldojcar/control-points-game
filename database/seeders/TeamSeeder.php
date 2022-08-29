<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SebastianBergmann\Template\Template;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    }


    private function createTeam(string $name)
    {
        $team       = new Team();
        $team->name = $name;
        $team->save();

        return $team;
    }


    private function createPlayer(string $name, Team $team)
    {
        $player          = new Player();
        $player->name    = $name;
        $player->team_id = $team->id;
        $player->rfid    = $team->id;
        $player->save();
    }
}
