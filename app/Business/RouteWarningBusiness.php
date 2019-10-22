<?php

namespace App\Business;

use App\Core\Utility\CommonUtility;
use App\Models\RouteWarning;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RouteWarningBusiness
{
    public function getRouteWarning($request) {
        $data = RouteWarning::query()
            ->with([
                "points"
            ])
            //->where([])
            ->where([["nguy_co", ">=", 4]])
            ->get();

        foreach ($data as &$item) {
            $nguyCo = $item["nguy_co"];

            $color = "#F00000";
            if ($nguyCo == 1) {
                $color = "#F10000";
            } else if ($nguyCo == 2) {
                $color = "#F20000";
            } if ($nguyCo == 3) {
                $color = "#F30000";
            } if ($nguyCo == 4) {
                $color = "#000000";
            } if ($nguyCo == 5) {
                $color = "#FF0000";
            }

            $item["warning_color"] = $color;
        }

        return Response::json($data);
    }

    public function getAllRouteWarningMobile($request) {
        $data = RouteWarning::query()
            ->with([
                "points"
            ])
            ->where([["nguy_co", ">=", "1"]])
            ->get();

        foreach ($data as &$item) {
            $arrPoints = [];

            $points = $item["points"];
            if (count($points) >= 2) {
                $arrPoints[] = $points[0];
                $arrPoints[] = $points[count($points) - 1];
            }

            unset($item["points"]);
            $item["points"] = $arrPoints;
        }

        return CommonUtility::getSuccessResponse($data, "");
    }
}