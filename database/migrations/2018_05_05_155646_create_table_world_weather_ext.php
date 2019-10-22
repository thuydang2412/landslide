<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWorldWeatherExt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('world_weather_ext', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->dateTime('date_call_api');
            $table->dateTime('date_data');
            $table->integer('station_id');
            $table->double('precipMM');
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
        Schema::drop('world_weather_ext');
    }
}
