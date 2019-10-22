<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'sub_district';

    public function boundary() {
        return $this->hasMany('App\Models\SubDistrictBoundary', 'sub_district_id', 'id');
    }
}
