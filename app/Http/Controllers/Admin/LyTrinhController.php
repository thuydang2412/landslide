<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PointLyTrinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class LyTrinhController extends Controller
{
    public function save(Request $request) {
        $lat = $request->input("lat");
        $lon = $request->input("lon");
        $type = $request->input("type");
        $route = $request->input("route");
        $km = $request->input("km");

        $lytrinh = new PointLyTrinh();
        $lytrinh->lat = $lat;
        $lytrinh->lon = $lon;
        $lytrinh->type = $type;
        $lytrinh->route = $route;
        $lytrinh->km = $km;
        $lytrinh->save();

        return Response::json($lytrinh, 200);
    }

    public function delete(Request $request) {
        $id = $request->input("id");
        PointLyTrinh::where(["id" => $id])->delete();
        return Response::json("OK", 200);
    }
}