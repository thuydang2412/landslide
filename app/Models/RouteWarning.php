<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteWarning extends Model
{
    protected $table = 'route_warning';
    public $timestamps = false;

    public function points() {
        return $this->hasMany('App\Models\RouteWarningDetail', 'route_id', 'id');
    }
}
