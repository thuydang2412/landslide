<?php

namespace App\Http\Controllers\Admin;

use App\Business\ColorWarningBusiness;
use App\Business\UserAdminBusiness;
use App\Models\VisitedUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function setting(Request $request) {
        $userAdminBusiness = new UserAdminBusiness();
        $users = $userAdminBusiness->getAllUser();

        $totalVisited = VisitedUser::count();
        $colorWarningBusiness = new ColorWarningBusiness();
        $colorSettings = $colorWarningBusiness->getWarningColor();
        return view("admin.setting", ['users' => $users, 'colorSettings' => $colorSettings, 'totalVisited' => $totalVisited]);
    }

    // Color warning level setting
    function saveColor(Request $request) {
        $colorWarningBusiness = new ColorWarningBusiness();
        $colorWarningBusiness->saveWarningColor($request);
        return Response::json("OK", 200);
    }

    // User management
    function createUser(Request $request) {
        if ($request->isMethod("GET")) {
            return view("admin.user-form");
        } else {
            $userAdminBusiness = new UserAdminBusiness();
            $userAdminBusiness->createUser($request);
            return redirect("/admin");
        }
    }

    function editUser(Request $request, $userId) {
        if ($request->isMethod("GET")) {
            $userAdminBusiness = new UserAdminBusiness();
            $user = $userAdminBusiness->getUserById($userId);
            return view("admin.user-form", ["user" => $user]);
        } else {
            $userAdminBusiness = new UserAdminBusiness();
            $userAdminBusiness->updateUser($request, $userId);
            return redirect("/admin");
        }
    }

    function deleteUser(Request $request, $userId) {
        $userAdminBusiness = new UserAdminBusiness();
        $userAdminBusiness->deleteUser($request, $userId);
        return redirect("/admin");
    }
}
