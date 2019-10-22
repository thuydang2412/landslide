<?php

namespace App\Http\Controllers\Common;


use App\Models\Admin;
use Illuminate\Support\Facades\Response;

class NotificationManager
{
    public function sendNotification($request) {
        $listEmail = [];
        $listPhone = [];

        $users = Admin::all();

        foreach($users as $user) {
            $permissionList = [];

            if (!empty($user)) {
                $permissionList = explode(",", $user->permissions);
            }

            if (in_array("0", $permissionList)) {
                if (!empty($user->email)) {
                    $listEmail[] = $user->email;
                }
            }

            if (in_array("1", $permissionList)) {
                if (!empty($user->phone)) {
                    $listPhone[] = $user->phone;
                }
            }
        }

        $emailSubject = "Cảnh báo trượt lở";
        $emailSubjectUnicode = $updated_subject = "=?UTF-8?B?" . base64_encode($emailSubject) . "?=";
        $bodyMessage = "";
        $level = $request->input("level");
        if ($level == 1) {
            $bodyMessage = "Chú ý nguy cơ trượt lở";
        } else if ($level == 2) {
            $bodyMessage = "Đề phòng nguy cơ trượt lở cao";
        } else if ($level == 3) {
            $bodyMessage = "Cảnh báo nguy cơ trượt lở rất cao";
        }

        // Send email
        if (count($listEmail) > 0) {
            SendMailUtility::sendMail($emailSubjectUnicode, $bodyMessage, $listEmail);
        }

        // Send sms
        foreach ($listPhone as $phone) {
            SendSMSUtility::sendSMS($bodyMessage, $phone);
        }


        $data = ["listEmail" => $listEmail, "listPhone" => $listPhone];
        return Response::json(['status' => 0, 'message' => "", 'data' => $data], 200);
    }
}