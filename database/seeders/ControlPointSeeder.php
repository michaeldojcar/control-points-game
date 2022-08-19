<?php

namespace Database\Seeders;

use App\Models\ControlPoint;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PHPUnit\TextUI\XmlConfiguration\Group;

class ControlPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->factoryControlPoint('Bod A');
        $this->factoryControlPoint('Bod B');
        $this->factoryControlPoint('Bod C');
        $this->factoryControlPoint('Bod D');
        $this->factoryControlPoint('Bod E');

        $g = $this->createGroup('Alfa');
        $this->createPlayer('0006064904', $g);
        $this->createPlayer('0001938169', $g);
        $this->createPlayer('0004251924', $g);
        $this->createPlayer('0012919251', $g);
        $this->createPlayer('0006521618', $g);
        $this->createPlayer('0001845136', $g);
        $this->createPlayer('0001973485', $g);
        $this->createPlayer('0001916439', $g);


        $g = $this->createGroup('Beta');
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);

        $g = $this->createGroup('Gamma');
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);

        $g = $this->createGroup('Delta');
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);

        $g = $this->createGroup('Omega');
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
        $this->createPlayer('', $g);
    }


    private function factoryControlPoint(string $string)
    {
        $cp       = new ControlPoint();
        $cp->name = $string;
        $cp->save();
    }


    private function createGroup($name)
    {
        $g       = new Team();
        $g->name = $name;
        $g->save();

        return $g;
    }


    private function createPlayer(string $rfid, Team $g)
    {
        $p          = new Player();
        $p->name    = 'Mates';
        $p->team_id = $g;
        $p->rfid    = $rfid;
        $p->save();
    }
}
