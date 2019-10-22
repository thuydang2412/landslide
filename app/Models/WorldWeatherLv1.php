<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldWeatherLv1 extends Model
{
    protected $table = 'world_weather_lv1';

    public function lv2() {
        return $this->hasMany('App\Models\WorldWeatherLv2', 'parent_id', 'id');
    }

    public function subDistrict()
    {
        return $this->belongsTo('App\Models\SubDistrict', 'sub_district_id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\Station', 'station_id');
    }
}
