<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CanhbaoNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canhbao_note', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->dateTime('date_canh_bao');
            $table->string('text_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('canhbao_note');
    }
}
