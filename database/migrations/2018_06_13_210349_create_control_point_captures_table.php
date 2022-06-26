<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlPointCapturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_point_captures', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('control_point_id');
            $table->dateTime('date_from');
            $table->dateTime('date_to')->nullable();
            $table->unsignedInteger('seconds')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_point_captures');
    }
}
