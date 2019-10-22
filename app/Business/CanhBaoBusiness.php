<?php

namespace App\Business;

use App\Models\CanhBaoNote;
use App\Models\PointLyTrinh;
use App\Models\Station;
use App\Models\SubDistrict;
use App\Models\WorldWeatherExt;
use App\Models\WorldWeatherLv1;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CanhBaoBusiness
{
    function warningCanhBao() {
        $obj = [];

        // ==================================================
        $arrPointLyTrinh = PointLyTrinh::all();

        foreach ($arrPointLyTrinh as $pointLyTrinh) {

            // Type text
            $type = $pointLyTrinh->type;
            $typeText= "";

            if ($type == "truotlo") {
                $typeText = "Trượt lở";
            } else if ($type == "ngap") {
                $typeText = "Ngập";
            } else if ($type == "duongxau") {
                $typeText = "Đường xấu";
            } else if ($type == "suaduong") {
                $typeText = "Sửa đường";
            } else if ($type == "camduong") {
                $typeText = "Cấm đường";
            }

            // Route
            $route = $pointLyTrinh->route;
            $routeText = "";

            if ($route == "quoclo_40b") {
                $routeText = "Quốc lộ 40B";
            } else if ($route == "quoclo_14g") {
                $routeText = "Quốc lộ 14G";
            } else if ($route == "quoclo_14E") {
                $routeText = "Quốc lộ 14E";
            } else if ($route == "quoclo_14D") {
                $routeText = "Quốc lộ 14D";
            } else if ($route == "quoclo_14B") {
                $routeText = "Quốc lộ 14B";
            } else if ($route == "hcm") {
                $routeText = "Hồ Chí Minh";
            } else if ($route == "hcm_nhanh_tay") {
                $routeText = "Hồ Chí Minh nhánh Tây";
            }

            // Km
            $kmValue = $pointLyTrinh->km;
            $distanceText = "";

            if ($kmValue != "null") {
                $kmText = floor($kmValue / 1000);
                $mText = $kmValue % 1000;
                $distanceText = "Km " . $kmText . "+" . $mText . ", " . $routeText;
            }

            $infoContent = $typeText . ": " . $distanceText;
            $obj['hien_nay'][] = $infoContent;
        }

        // ========================================================================
        $today = date('Y-m-d', strtotime('now'));
        $canhbaoNote = CanhBaoNote::where(['date_canh_bao' => $today])->first();

        if (!isset($canhbaoNote)) {
            $canhbao = new WorldWeatherBusiness();
            $arrStrWarning = $canhbao->calculateCanhBao();
            
            $canhbaoNote = new CanhBaoNote();
            $canhbaoNote->date_canh_bao = $today;
            $canhbaoNote->text_note = json_encode($arrStrWarning);
            $canhbaoNote->save();

            $obj["canh_bao"] = $arrStrWarning;
        } else {
            $obj["canh_bao"] = json_decode($canhbaoNote->text_note);
        }


        // ============================================================================
        $html = view("site.partial.warningCanhbao", ["obj" => $obj]);
        $response['html'] = $html->render();

        return Response::json($response, 200);
    }
}