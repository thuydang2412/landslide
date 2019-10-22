<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCanhbaoTemp01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canhbao_temp_01', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_name');
            $table->integer('day_01')->nullable();
            $table->integer('day_02')->nullable();
            $table->integer('day_03')->nullable();
            $table->integer('day_04')->nullable();
            $table->integer('day_05')->nullable();
            $table->integer('day_06')->nullable();
            $table->integer('day_07')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('canhbao_temp_01');
    }
}
