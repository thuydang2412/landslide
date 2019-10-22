<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\SubDistrict;
use App\Models\WarningLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class BoundaryController extends Controller
{
    public function getSubDistrictBoundary() {
        $response['message'] = "";

        // Lấy thông tin mảng boundary của các xã
        $data = $this->getBoundary()->toArray();

        // Lấy thông tin lượng mưa của các xã
        // Build thông tin mữa các xã vào 1 map có key là id xã để merge với thông tin boundary
        // Tiếp đó lấy thông tin màu sắc dựa trên mức độ mưa của các xã
        $weatherInfo = $this->buildWeatherInfoMap($this->getPrecipInfo());

        // Merge weatherInfo to data
        foreach ($data as &$item) {
            $itemId = $item["id"];
            $itemWeather = [];
            if(isset($weatherInfo["id_" . $itemId])) {
                $itemWeather = $weatherInfo["id_" . $itemId];
            } else {
                $itemWeather["avg_precipMM"] = 0;
                $itemWeather["warning_level"] = "#FFFFFF";
            }
            $item["weather_info"] = $itemWeather;
        }

        $response['data'] = $data;
        return Response::json($response, 200);
    }

    public function getBoundary() {
        $result = SubDistrict::with("boundary")->get();
        return $result;
    }

    public function getPrecipInfo() {
        $today = date('Y-m-d', strtotime("now"));
        $sql = "select lv1.sub_district_id , avg(precipMM) as avg_precipMM " .
                " from igp_lv1 lv1 join " .
                " (igp_lv2 as lv2 join igp_lv3 as lv3 on lv2.id = lv3.parent_id) on lv1.id = lv2.parent_id " .
                " where date = '$today' " .
                " group by lv2.id, lv1.sub_district_id";
        $result = DB::select($sql);
        return $result;
    }

    private function buildWeatherInfoMap($weatherData) {
        $colorWarningSetting = WarningLevel::all();

        $map = [];
        foreach ($weatherData as $data) {
            $item = [];
            $item["avg_precipMM"] = $data->avg_precipMM;
            $item["warning_level"] = $this->getWarningLevel($data->avg_precipMM, $colorWarningSetting);
            $map["id_" . $data->sub_district_id] = $item;
        }
        return $map;
    }

    private function getWarningLevel($info, $colorWarningSetting) {
        if (count($colorWarningSetting) == 0) {
            return "#FFFFFF";
        }

        if ($info <= $colorWarningSetting[0]->from_number) {
            return "#" . $colorWarningSetting[0]->color;
        }

        if ($info >= $colorWarningSetting[count($colorWarningSetting) -1]->to_number) {
            return "#" . $colorWarningSetting[count($colorWarningSetting) -1]->color;
        }

        foreach ($colorWarningSetting as $setting) {
            if ($info >= $setting->from_number && $info < $setting->to_number) {
                return "#" . $setting->color;
            }
        }

        return "#FFFFFF";
    }
}