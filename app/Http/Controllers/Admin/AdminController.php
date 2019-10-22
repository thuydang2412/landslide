<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request) {
        if($request->isMethod("post")) {
            $userName = $request->input("username");
            $password = $request->input("password");

            $user = Admin::where(['username' => $userName])->first();

            if (!empty($user)) {
                $hashPass = $user->password;
                if (Hash::check($password, $hashPass)) {
                    session(['user_id' => $user->id]);
                    return redirect('admin/');
                } else {
                    return view('admin.login', ['error' => "Password incorrect"]);
                }
            } else {
                return view('admin.login', ['error' => "User doesn't exist"]);
            }

        } else {
            return view("admin.login");
        }
    }

    public function logout(Request $request) {
        $request->session()->forget('user_id');
        $request->session()->flush();
        return redirect("admin/login");
    }
}