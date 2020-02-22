<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotStarshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_starship', function (Blueprint $table) {
            $table->bigInteger('pilot_id')->unsigned();
            $table->foreign('pilot_id')->references('id')->on('pilots');
            $table->bigInteger('starship_id')->unsigned();
            $table->foreign('starship_id')->references('id')->on('starships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('starship_pilot');
    }
}
