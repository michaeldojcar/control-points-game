<?php

namespace Database\Seeders;

use App\Models\ControlPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        $this->factoryControlPoint('Bod F');
        $this->factoryControlPoint('Bod G');
    }


    private function factoryControlPoint(string $string)
    {
        $cp       = new ControlPoint();
        $cp->name = $string;
        $cp->save();
    }
}
