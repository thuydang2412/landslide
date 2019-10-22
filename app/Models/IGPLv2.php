<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IGPLv2 extends Model
{
    protected $table = 'igp_lv2';

    public function lv3() {
        return $this->hasMany('App\Models\IGPLv3', 'parent_id', 'id');
    }

    public function lv1()
    {
        return $this->belongsTo('App\Models\IGPLv1', 'parent_id');
    }
}
