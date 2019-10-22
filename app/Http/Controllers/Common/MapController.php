<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\MapKML;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class MapController extends Controller
{
    public function getKmlData() {
        $arrMapData = MapKML::where(["enable" => 1])->get();

        foreach ($arrMapData as &$mapData) {
            //$mapData["kml_file_name"] = URL::to("/data/") . "/" . $mapData["kml_file_name"];
            $mapData["kml_file_name"] = "http://quangnam.truotlo.com/data" . "/" . $mapData["kml_file_name"];
        }

        return Response::json($arrMapData, 200);
    }
}