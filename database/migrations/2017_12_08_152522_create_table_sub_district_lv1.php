<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubDistrictLv1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_district_lv1', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->text('name');
            $table->integer('parent_id');
            $table->double('lat');
            $table->double('lon');
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
        Schema::drop('sub_district_lv1');
    }
}
