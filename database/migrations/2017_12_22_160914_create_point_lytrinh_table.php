<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointLytrinhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_ly_trinh', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->double('lat');
            $table->double('lon');
            $table->char('type', 25);
            $table->char('route', 128)->nullable();
            $table->char('km', 128)->nullable();
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
        Schema::drop('point_ly_trinh');
    }
}
