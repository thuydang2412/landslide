<?php

namespace App\Business;

use App\Models\SubDistrict;
use App\Models\SubDistrictLv1;

class DistrictBusiness
{
    function getAllDistrict() {
        $user = SubDistrict::where(["type" => 0])->select(["id", "name", "lat", "lon"])->get();
        return $user;
    }

    function getAllDistrictLv1() {
        $user = SubDistrictLv1::query()->select(["id", "name", "lat", "lon"])->get();
        return $user;
    }
}