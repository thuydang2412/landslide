<?php
namespace App\Http\Controllers\Api;

use App\Business\RouteWarningBusiness;
use App\Core\Utility\CommonUtility;
use App\Http\Controllers\Common\NotificationManager;
use App\Http\Controllers\Controller;
use App\Models\PointLyTrinh;
use App\Models\RouteWarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    public function test(Request $request) {
        $url = "http://canhbao.local/data/hagiang.txt";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return Response::json($data, 200);
    }

    public function sendNotification(Request $request) {
        $notificationManager = new NotificationManager();
        return $notificationManager->sendNotification($request);
    }

    public function getAllPointLyTrinh() {
        $response = PointLyTrinh::all();
        return CommonUtility::getSuccessResponse($response, "");
    }

    public function getAllRouteWarningMobile(Request $request) {
        $business = new RouteWarningBusiness();
        return $business->getAllRouteWarningMobile($request);
    }

    public function getRouteWarning(Request $request) {
        $business = new RouteWarningBusiness();
        return $business->getRouteWarning($request);
    }
}