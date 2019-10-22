<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IGPLv1 extends Model
{
    protected $table = 'igp_lv1';

    public function lv2() {
        return $this->hasMany('App\Models\IGPLv2', 'parent_id', 'id');
    }

    public function subDistrict()
    {
        return $this->belongsTo('App\Models\SubDistrict', 'sub_district_id');
    }
}
