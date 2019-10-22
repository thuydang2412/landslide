<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doannguyco extends Model
{
    protected $table ='doannguyco';
    public $timestamps = false;

    public function doanNguyco()
    {
        return $this->belongsTo('App\Models\DoannguycoParent', 'id_parent', 'kh_doan');
    }
}
