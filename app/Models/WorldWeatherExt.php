<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldWeatherExt extends Model
{
    protected $table = 'world_weather_ext';

    public function station()
    {
        return $this->belongsTo('App\Models\Station', 'station_id');
    }
}
