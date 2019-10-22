<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\IGPLv1;
use App\Models\WorldWeatherLv1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PrecipitationController extends Controller
{
    // Search in world weather table
//    function doSearch(Request $request) {
//        $subDistrictId = $request->input("subDistrictId");
//        $startDate = $request->input("startDate");
//        $endDate = $request->input("endDate");
//
//        $response['message'] = "";
//        $data = WorldWeatherLv1::with([
//                    "lv2" => function($query) use ($startDate, $endDate) {
//                        $query->with("lv3")
//                        ->where([['date', '<=', $endDate], ['date', '>=', $startDate]]);
//                    },
//                    "subDistrict" => function($query) {
//                        $query->select(['id', 'name']);
//                    }])
//                ->where(['sub_district_id' => $subDistrictId])
//                ->get();
//        $response['data'] = $data;
//        return Response::json($response, 200);
//    }


//    function doSearch(Request $request) {
//        $subDistrictId = $request->input("subDistrictId");
//        $startDate = $request->input("startDate");
//        $endDate = $request->input("endDate");
//
//        $response['message'] = "";
//        $data = IGPLv1::with([
//            "lv2" => function($query) use ($startDate, $endDate) {
//                $query->with("lv3")
//                    ->where([['date', '<=', $endDate], ['date', '>=', $startDate]]);
//            },
//            "subDistrict" => function($query) {
//                $query->select(['id', 'name']);
//            }])
//            ->where(['sub_district_id' => $subDistrictId])
//            ->get();
//        $response['data'] = $data;
//        return Response::json($response, 200);
//    }

    function getRecent(Request $request) {
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");

        $stationId = 25;

        $response['message'] = "";
        $data = WorldWeatherLv1::with([
            "lv2" => function($query) use ($startDate, $endDate) {
                $query->with("lv3")
                    ->where([['date', '<=', $endDate], ['date', '>=', $startDate]]) ->orderBy('id', 'asc');
            },
            "station" => function($query) {
                $query->select(['id', 'name']);
            }])
            ->where(['station_id' => $stationId])
            ->get();
        $response['data'] = $data;
        return Response::json($response, 200);
    }
}