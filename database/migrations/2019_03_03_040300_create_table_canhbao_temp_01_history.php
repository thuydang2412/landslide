<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCanhbaoTemp01History extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canhbao_temp_01_pessl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_id');
            $table->dateTime('time');
            $table->double('precipMM');
            $table->timestamps();
        });

        Schema::create('canhbao_temp_01_wwo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_id');
            $table->dateTime('time');
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
        //
    }
}
