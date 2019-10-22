<?php

namespace App\Business;


use App\Models\WarningLevel;

class ColorWarningBusiness
{
    public function getWarningColor() {
        $setting = WarningLevel::all();
        return $setting;
    }

    public function saveWarningColor($request) {
        $dataJson = $request->input('data');
        $data = json_decode($dataJson);
        foreach ($data as $setting) {
            $id = $setting->warningId;
            $color = $setting->color;
            $fromNumber = $setting->fromNumber;
            $toNumber = $setting->toNumber;

            $warningLevel = WarningLevel::where(["id" => $id])->first();
            $warningLevel->color = $color;
            $warningLevel->from_number = $fromNumber;
            $warningLevel->to_number = $toNumber;
            $warningLevel->save();
        }
    }

}