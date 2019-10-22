<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Support\Facades\Response;

class PlaceController extends Controller
{
    public function getSubDistrict() {
        $response['message'] = "";
        $data = SubDistrict::where(['type' => '0'])->select(["id", "name"])->get();
        $response['data'] = $data;
        return Response::json($response, 200);
    }

    public function getDistrictInfo() {
        $districtId = 1;
        $data = District::where(["id" => $districtId])->first();
        $response['data'] = $data;
        return Response::json($response, 200);
    }
}