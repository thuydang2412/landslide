<?php

namespace App\Http\Controllers\Common;


use anlutro\cURL\cURL;

class SendSMSUtility
{
    public static function sendSMS($bodyMessage, $toAddress) {
        SendSMSUtility::executeSendSMS($bodyMessage, $toAddress);
    }

    private static function executeSendSMS($bodyMessage, $toAddress) {
        $curl = new cURL();

        $url = "https://api.twilio.com/2010-04-01/Accounts/AC352bfeac7bb182af61488361c4d02938/Messages.json";
        $params = ["To" => $toAddress, "From" => "+12243669932", "Body" => $bodyMessage];

        $request = $curl->newRequest('POST', $url, $params)
            ->setHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->setOption(CURLOPT_SSL_VERIFYHOST, 0)
            ->setOption(CURLOPT_SSL_VERIFYPEER, 0)
            ->setOption(CURLOPT_USERPWD, "AC352bfeac7bb182af61488361c4d02938:3595eeca45afb149c1c5cade5c457bec");

        $body = $request->send();
    }

    private static function sendSMSTwilio() {
        $curl = new cURL();

        $url = "https://api.twilio.com/2010-04-01/Accounts/AC352bfeac7bb182af61488361c4d02938/Messages.json";
        $params = ["To" => "+841656099670", "From" => "+12243669932", "Body" => "This is the ship that made the Kessel Run in fourteen parsecs?"];

        $request = $curl->newRequest('POST', $url, $params)
            ->setHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->setOption(CURLOPT_SSL_VERIFYHOST, 0)
            ->setOption(CURLOPT_SSL_VERIFYPEER, 0)
            ->setOption(CURLOPT_USERPWD, "AC352bfeac7bb182af61488361c4d02938:3595eeca45afb149c1c5cade5c457bec");
        $body = $request->send();

        echo "<pre>";
        var_dump($body);
        echo "</pre>";die;
    }
}