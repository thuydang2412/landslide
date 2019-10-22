<?php
namespace App\Http\Controllers\Site;

use App\Business\CanhBaoBusiness;
use App\Business\DistrictBusiness;
use App\Business\StationBusiness;
use App\Http\Controllers\Controller;
use App\Models\CanhBaoTemp01;
use App\Models\PointLyTrinh;
use App\Models\VisitedUser;
use App\Models\WarningLandslide;
use App\Models\WarningInfo;
use App\Models\Doannguyco;
use App\Models\DoannguycoParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index(Request $request) {
        $isAdmin = !empty(session('user_id'));

        // Save user log
        $ipAddress = $request->ip();
        $visitedUser = new VisitedUser();
        $visitedUser->ip_address = $ipAddress;
        $visitedUser->save();

        // Get data canh bao
        $canhBaoTemp = CanhBaoTemp01::query()
            ->get();

        // Check has canh bao
        $hasCanhBao = false;
        foreach ($canhBaoTemp as $canhBaoItem) {
            if($canhBaoItem["day_01"] != 0 ||
                $canhBaoItem["day_02"] != 0 ||
                $canhBaoItem["day_03"] != 0 ||
                $canhBaoItem["day_04"] != 0 ||
                $canhBaoItem["day_05"] != 0 ||
                $canhBaoItem["day_06"] != 0 ||
                $canhBaoItem["day_07"] != 0) {
                $hasCanhBao = true;
            }
        }

        if ($hasCanhBao) {
            foreach ($canhBaoTemp as &$canhBaoItem) {
                $canhBaoItem["color_day_01"] = $this->getColor($canhBaoItem, "day_01");
                $canhBaoItem["color_day_02"] = $this->getColor($canhBaoItem, "day_02");
                $canhBaoItem["color_day_03"] = $this->getColor($canhBaoItem, "day_03");
                $canhBaoItem["color_day_04"] = $this->getColor($canhBaoItem, "day_04");
                $canhBaoItem["color_day_05"] = $this->getColor($canhBaoItem, "day_05");
                $canhBaoItem["color_day_06"] = $this->getColor($canhBaoItem, "day_06");
                $canhBaoItem["color_day_07"] = $this->getColor($canhBaoItem, "day_07");
            }
        } else {
            $canhBaoTemp = [];
        }


        $warningLandsiles=WarningLandslide::All();
        $warningInfos=WarningInfo::All();
        $doannguyco=Doannguyco::All();
        return view("site.home", ["isAdmin" => $isAdmin, "arrCanhBao" => $canhBaoTemp, "doannguyco" => $doannguyco, 
        "warningLandsiles"=>$warningLandsiles, "warningInfos"=>$warningInfos]);
    }

    function getColor($canhBaoData, $columnName) {
        $levelWarning = $canhBaoData[$columnName];
        $color = "#FFFFFF";
        if ($levelWarning == 0) {
            $color = "#FFFFFF";
        } else if ($levelWarning == 1) {
            $color = "#22b14c";
        } else if ($levelWarning == 2) {
            $color = "#FFFF00";
        } else if ($levelWarning == 3) {
            $color = "#FF0000";
        } else if ($levelWarning == 4) {
            $color = "#800080";
        }
        return $color;
    }
    public function kml() {
        return view("site.kml");
    }

    public function precipitation() {
        return view("site.precipitation");
    }

    public function showMap() {
        return view("site.map");
    }

    public function contact() {
        return view("site.contact");
    }

    public function getAllDistrict() {
        $business = new DistrictBusiness();
        $response = $business->getAllDistrict();
        return Response::json($response, 200);
    }

    public function getAllDistrictLv1() {
        $business = new DistrictBusiness();
        $response = $business->getAllDistrictLv1();
        return Response::json($response, 200);
    }

    public function getAllStation() {
        $business = new StationBusiness();
        $response = $business->getAllStation();
        return Response::json($response, 200);
    }

    public function getAllPointLyTrinh() {
        $response = PointLyTrinh::all();
        return Response::json($response, 200);
    }

    public function warningCanhBao() {
        $canhBao = new CanhBaoBusiness();
        return $canhBao->warningCanhBao();
    }
}