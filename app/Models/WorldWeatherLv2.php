<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldWeatherLv2 extends Model
{
    protected $table = 'world_weather_lv2';

    public function lv3() {
        return $this->hasMany('App\Models\WorldWeatherLv3', 'parent_id', 'id');
    }

    public function lv1()
    {
        return $this->belongsTo('App\Models\WorldWeatherLv1', 'parent_id');
    }
}
