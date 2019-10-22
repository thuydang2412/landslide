<?php

namespace App\Business;
use App\Models\Admin;

class UserAdminBusiness
{
    function getAllUser() {
        $user = Admin::where([['is_super', '<>', '1']])->get();
        return $user;
    }

    function getUserById($userId) {
        $user = Admin::where(["id" => $userId])->first();
        return $user;
    }

    function createUser($request) {
        $user = new Admin();
        $this->storeUser($request, $user);
    }

    function updateUser($request, $userId) {
        $user = $this->getUserById($userId);
        $this->storeUser($request, $user);
    }

    function storeUser($request, $user) {
        $userName = $request->input("userName");
        $password = $request->input("password");
        $email = $request->input("email");
        $address = $request->input("address");
        $phone = $request->input("phone");
        $permissions = $request->input("permissions");

        $user->username = $userName;
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
        $user->email = $email;
        $user->address = $address;
        $user->phone = $phone;
        $user->permissions = $permissions;
        $user->is_super = 0;
        $user->save();
    }

    function deleteUser($request, $userId) {
        $user = $this->getUserById($userId);
        $user->delete();
    }
}