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
        $this->createPlayer('0001973286', $g);
        $this->createPlayer('0006052944', $g);
        $this->createPlayer('0006049899', $g);
        $this->createPlayer('0001952907', $g);
        $this->createPlayer('0006007378', $g);
        $this->createPlayer('0006477469', $g);
        $this->createPlayer('0002066075', $g);
        $this->createPlayer('0010566020', $g);

        $g = $this->createGroup('Gamma');
        $this->createPlayer('0002046396', $g);
        $this->createPlayer('0001941154', $g);
        $this->createPlayer('0001986257', $g);
        $this->createPlayer('0002921792', $g);
        $this->createPlayer('0001984435', $g);
        $this->createPlayer('0008783733', $g);
        $this->createPlayer('0006477478', $g);
        $this->createPlayer('0010566306', $g);

        $g = $this->createGroup('Delta');
        $this->createPlayer('0011792758', $g);
        $this->createPlayer('0001898605', $g);
        $this->createPlayer('0001916449', $g);
        $this->createPlayer('0001952947', $g);
        $this->createPlayer('0001986268', $g);
        $this->createPlayer('0001916521', $g);
        $this->createPlayer('0006050560', $g);
        $this->createPlayer('0001921329', $g);

        $g = $this->createGroup('Omega');
        $this->createPlayer('0004244423', $g);
        $this->createPlayer('0001884610', $g);
        $this->createPlayer('0001973490', $g);
        $this->createPlayer('0001994192', $g);
        $this->createPlayer('0001991215', $g);
        $this->createPlayer('0001978882', $g);
        $this->createPlayer('0006007599', $g);
        $this->createPlayer('0006007594', $g);

        $g = $this->createGroup('Omikron');
        $this->createPlayer('0002080893', $g);
        $this->createPlayer('0006507422', $g);
        $this->createPlayer('0006477429', $g);
        $this->createPlayer('0001890948', $g);
        $this->createPlayer('0001959833', $g);
        $this->createPlayer('0006030930', $g);
        $this->createPlayer('0001884615', $g);
        $this->createPlayer('0001986262', $g);
        $this->createPlayer('0006053297', $g);
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
        $p->team_id = $g->id;
        $p->rfid    = $rfid;
        $p->save();
    }
}
