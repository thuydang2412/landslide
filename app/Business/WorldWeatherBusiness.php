<?php

namespace App\Business;

use App\Models\PesslData;
use App\Models\RouteWarning;
use App\Models\Station;
use App\Models\SubDistrict;
use App\Models\WorldWeatherExt;
use App\Models\WorldWeatherLv1;
use Illuminate\Support\Facades\DB;

class WorldWeatherBusiness
{
    function getWeatherData($filter) {
        $stationId = $filter['stationId'];
        $startDate = $filter['startDate'];
        $endDate = $filter['endDate'];

        $data = WorldWeatherLv1::with([
            "lv2" => function($query) use ($startDate, $endDate) {
                $query->with("lv3")
                    ->where([['date', '<=', $endDate], ['date', '>=', $startDate]]) ->orderBy('id', 'asc');
            },
            "station" => function($query) {
                $query->select(['id', 'name']);
            }])
            ->where(['station_id' => $stationId])
            ->orderBy('id', 'asc')
            ->get();

        return $data;
    }

    function getWeatherDataPessl($filter) {
        $stationId = $filter['stationId'];
        $startDate = $filter['startDate'];
        $endDate = $filter['endDate'];

        $query = PesslData::query()
            ->where(['station_id' => $stationId])
            ->where([['date_value', '<=', $endDate], ['date_value', '>=', $startDate]])
            ->orderBy('id', 'asc');

        $data = $query->get();

        return $data;
    }

    /**
     * @return Thong tin canh bao cho cac tram
     */
    function calculateCanhBao() {
        DB::enableQueryLog();

        $arrWarning = [];

        $arrStation = Station::all();
        $now = date('Y-m-d', strtotime("now"));

        foreach ($arrStation as $station) {
            $stationId = $station->id;

            // Find precipMM of today
            $todayWorldWeatherExt = WorldWeatherExt::where(['date_data' => $now, 'station_id' => $stationId])->first();
            if (isset($todayWorldWeatherExt)) {
                $todayPrecipMM = $todayWorldWeatherExt->precipMM;
            } else {
                continue;
            }


            if ($todayPrecipMM >= 120 && $todayPrecipMM <= 180) {
                $previous = date('Y-m-d', strtotime("- 11 days"));
                $sumPrecipMM = $this->getSumPreviousPrecipMM($stationId, $now, $previous);

                if ($sumPrecipMM >= 550) {
                    $warning = [];
                    $warning["station"] = $station;
                    $warning["level"] = 1;
                    $arrWarning[] = $warning;
                }

            } else if ($todayPrecipMM > 180) {
                $previous = date('Y-m-d', strtotime("- 5 days"));
                $sumPrecipMM = $this->getSumPreviousPrecipMM($stationId, $now, $previous);

                if ($sumPrecipMM >= 350) {
                    $warning = [];
                    $warning["station"] = $station;
                    $warning["level"] = 2;
                    $arrWarning[] = $warning;
                }
            } else {
                continue;
            }

        }

        // Generate warning text
        $arrStrWarning = [];

        foreach ($arrWarning as $warning) {
            $station = $warning["station"];
            $level = $warning["level"];

            $textLevel = "";
            if ($level == 1) {
                $textLevel = "Cảnh báo mức độ 1";
            } else if ($level == 2) {
                $textLevel = "Cảnh báo mức độ 2";
            }

            $strWarning = $station->name . ": " . $textLevel;
            $arrStrWarning[] = $strWarning;
        }


        return $arrStrWarning;

    }

    /**
     * @return Thong tin canh bao cho cac doan duong
     */
    function calculateCanhBaoRoute() {
        $arrStation = Station::all();
        $now = date('Y-m-d', strtotime("now"));

        // Step 1: Calculate Kich ban ==============================================================
        $arrKichBan = []; // Map station_id => R

        foreach ($arrStation as $station) {
            $stationId = $station->id;
            $kichBan = 0;

            // Find precipMM of today
            $todayWorldWeatherExt = WorldWeatherExt::where(['date_data' => $now, 'station_id' => $stationId])->first();

            if (isset($todayWorldWeatherExt)) {
                $todayPrecipMM = $todayWorldWeatherExt->precipMM;
            } else {
                continue;
            }

            if ($todayPrecipMM >= 70 && $todayPrecipMM <= 90) {
                $previous = date('Y-m-d', strtotime("- 12 days"));
                $sumPrecipMM = $this->getSumPreviousPrecipMM($stationId, $now, $previous);

                if ($sumPrecipMM >= 400 && $sumPrecipMM <= 450) {
                    $kichBan = 1;
                }

            } else if ($todayPrecipMM >= 90 && $todayPrecipMM <= 105) {

            }

            $arrKichBan["$stationId"] = $kichBan;
        }

        //dd($arrKichBan);

        // Step 2: Calculate Nguy co ===============================================================================
        $routes = RouteWarning::query()->where([])->get();
        foreach ($routes as $route) {
            $stationId = $route->station_id;
            $warningLevel = $route->warning_level;

            if (isset($arrKichBan["$stationId"])) {
                $kichBan = $arrKichBan["$stationId"];
                $nguyCo = $this->getNguyCo($kichBan, $warningLevel);

                $route->nguy_co = $nguyCo;
                $route->save();
            }
        }
    }

    function getSumPreviousPrecipMM($stationId, $now, $previous) {
        $arrWorldWeatherExt = WorldWeatherExt::where([
            ['date_data', '>=', $previous],
            ['date_data', '<=', $now],
            ['station_id', $stationId],
        ])->get();

        $sumPrecipMM = 0;
        foreach ($arrWorldWeatherExt as $wwoExt) {
            $sumPrecipMM += $wwoExt->precipMM;
        }

        return $sumPrecipMM;
    }

    function getNguyCo($kichBan, $warningLevel) {
        if ($kichBan == 0) {
            return 0;
        }

        if ($kichBan == 1) {
            if ($warningLevel == 1) {
                return 0;
            }

            if ($warningLevel == 2) {
                return 1;
            }

            if ($warningLevel == 3) {
                return 2;
            }

            if ($warningLevel == 4) {
                return 3;
            }
        }

        if ($kichBan == 2) {
            if ($warningLevel == 1) {
                return 1;
            }

            if ($warningLevel == 2) {
                return 2;
            }

            if ($warningLevel == 3) {
                return 3;
            }

            if ($warningLevel == 4) {
                return 4;
            }
        }

        if ($kichBan == 3) {
            if ($warningLevel == 1) {
                return 2;
            }

            if ($warningLevel == 2) {
                return 3;
            }

            if ($warningLevel == 3) {
                return 4;
            }

            if ($warningLevel == 4) {
                return 5;
            }
        }

        return 0;
    }
}