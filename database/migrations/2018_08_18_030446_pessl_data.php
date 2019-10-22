<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PesslData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessl_data', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->string('station_id');
            $table->date('date_value')->nullable();
            $table->string('hour_value')->nullable();
            $table->double('data_1_X_X_497_avg')->nullable();
            $table->double('data_1_X_X_497_max')->nullable();
            $table->double('data_1_X_X_497_min')->nullable();
            $table->double('data_4_X_X_30_last')->nullable();
            $table->double('data_5_X_X_6_sum')->nullable();
            $table->double('data_7_X_X_7_last')->nullable();
            $table->double('data_21_X_X_650_last')->nullable();
            $table->double('data_22_X_X_651_last')->nullable();
            $table->double('data_23_X_X_652_last')->nullable();
            $table->double('data_24_X_X_659_last')->nullable();
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
        Schema::drop('pessl_data');
    }
}
