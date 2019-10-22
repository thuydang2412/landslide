<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWindspeedKmphTableWeatherLv3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('world_weather_lv3', function (Blueprint $table) {
            $table->text('windspeedKmph')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('world_weather_lv3', function (Blueprint $table) {
            $table->dropColumn('windspeedKmph');
        });
    }
}
