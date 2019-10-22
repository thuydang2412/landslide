<?php

namespace App\Business;

use App\Models\Station;
use App\Models\StationPessl;
use App\Models\SubDistrict;

class StationBusiness
{
    function getAllStation() {
        $user = Station::where(['parent_id' => 0])->orderBy('id')->get();
        return $user;
    }

    function getAllStationPessl() {
        $user = StationPessl::query()
            ->orderBy('id')->get();
        return $user;
    }
}