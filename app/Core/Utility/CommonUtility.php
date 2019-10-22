<?php
/**
 * Created by PhpStorm.
 * User: Hoc Nguyen
 * Date: 9/28/2018
 * Time: 3:45 PM
 */

namespace App\Core\Utility;


use Illuminate\Support\Facades\Response;

class CommonUtility
{
    public static function getErrorResponse($message) {
        return Response::json(["status" => 0, "data" => null, "message" => $message]);
    }

    public static function getSuccessResponse($data, $message) {
        return Response::json(["status" => 1, "data" => $data, "message" => $message]);
    }

    /**
     * Random number of character
     * @param $num
     */
    public static function randomCharacter($num) {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            //.'0123456789!@#$%^&*()'
        ); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, $num) as $k) {
            $rand .= $seed[$k];
        }

        return $rand;
    }
}