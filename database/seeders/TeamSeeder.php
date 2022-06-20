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
        $team = $this->createTeam('Alfa');
        $this->createPlayer('name', $team);

        $this->createTeam('Beta');
        $this->createTeam('Gamma');
        $this->createTeam('Delta');
        $this->createTeam('Epsilon');
        $this->createTeam('Omikron');
        $this->createTeam('Omega');
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
        $player->rfid    = '1';
        $player->save();
    }
}
