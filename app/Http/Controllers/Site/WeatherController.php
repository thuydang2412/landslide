<?php
namespace App\Http\Controllers\Site;

use App\Business\DistrictBusiness;
use App\Business\StationBusiness;
use App\Business\WorldWeatherBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WeatherController extends Controller
{
    public function index(Request $request) {
        $stationId = $request->input("stationId");
        $isAdmin = !empty(session('user_id'));
        return view("site.weather",["stationId" => $stationId, "isAdmin" => $isAdmin]);
    }

    public function indexMobile(Request $request) {
        $stationId = $request->input("stationId");
        return view("site.weatherMobile",["stationId" => $stationId]);
    }

    public function getStation(){
        $response = [];

        $business = new StationBusiness();
        $stations = $business->getAllStation();
        $response['data'] = $stations;
        return Response::json($response, 200);
    }

    function doSearch(Request $request) {
        $filter = [];
        $filter['stationId'] = $request->input("stationId");
        $filter['startDate'] = $request->input("startDate");
        $filter['endDate'] = $request->input("endDate");

        $response['message'] = "";

        $business = new WorldWeatherBusiness();
        $data = $business->getWeatherData($filter);

        $response['data'] = $data;
        return Response::json($response, 200);
    }

    public function getStationPessl(){
        $response = [];

        $business = new StationBusiness();
        $stations = $business->getAllStationPessl();
        $response['data'] = $stations;
        return Response::json($response, 200);
    }

    function doSearchPessl(Request $request) {
        $filter = [];
        $filter['stationId'] = $request->input("stationId");
        $filter['startDate'] = $request->input("startDate");
        $filter['endDate'] = $request->input("endDate");

        $response['message'] = "";

        $business = new WorldWeatherBusiness();
        $data = $business->getWeatherDataPessl($filter);

        $response['data'] = $data;
        return Response::json($response, 200);
    }
}