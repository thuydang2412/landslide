<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRouteWarning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_warning', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->text('code')->nullable();
            $table->text('name')->nullable();
            $table->integer('warning_level')->nullable();
        });

        Schema::create('route_warning_detail', function (Blueprint $table) {
            $table->increments('id'); // Default is NOT_NULL
            $table->integer('route_id');
            $table->double("latitude");
            $table->double("longitude");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('route_warning');
        Schema::drop('route_warning_detail');
    }
}
