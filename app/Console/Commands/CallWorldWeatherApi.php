<?php

namespace App\Console\Commands;

use anlutro\cURL\cURL;
use App\Business\WorldWeatherBusiness;
use App\Http\Controllers\Common\SendMailUtility;
use App\Http\Controllers\Common\SendSMSUtility;
use App\Models\CanhBaoNote;
use App\Models\Station;
use App\Models\SubDistrict;
use App\Models\WorldWeatherExt;
use App\Models\WorldWeatherHistory;
use App\Models\WorldWeatherLv1;
use App\Models\WorldWeatherLv2;
use App\Models\WorldWeatherLv3;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Insert vào bảng world_weather_lv1: ID xã, ngày lấy dữ liệu
 * Bảng world_weather_lv2: Dữ liệu theo ngày (Lấy 10 ngày thì insert 10 bản ghi)
 * Bảng world_weather_lv3: Dữ liệu lấy theo giờ
 * Lấy chung cả huyện Xí Mần: php artisan CallWorldWeatherApi getInfo 20
 */

// php artisan CallWorldWeatherApi
// php artisan CallWorldWeatherApi getInfo 1 // Khuôn Lùng
// php artisan CallWorldWeatherApi getInfo 2 // Nản Xỉn

// php artisan CallWorldWeatherApi getInfoHistory 1

// php artisan CallWorldWeatherApi getInfoExt 1: Lấy data theo từng ngày để đưa ra cảnh báo

// php artisan CallWorldWeatherApi calculateCanhBao

// php artisan CallWorldWeatherApi calculateCanhBaoRoute 0

class CallWorldWeatherApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CallWorldWeatherApi {action} {station-id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'getInfo') {
            $this->getInfo();
        }

        else if ($action === 'getInfoHistory') {
            $this->getInfoHistory();
        }

        else if ($action === 'getInfoExt') {
            $this->getInfoExt();
        }

        else if ($action === 'calculateCanhBao') {
            $this->calculateCanhBao();
        }

        else if ($action === 'calculateCanhBaoRoute') {
            $this->calculateCanhBaoRoute();
        }

        else if ($action === 'test') {
            $this->test();
        }
    }

    private function getInfo() {
        while(true) {
            $curl = new cURL();

            // Get param
            $key = env('WORLD_WEATHER_API_KEY', "");
            $timeStamp = 3;
            $numDays = 10;
            $stationId = $this->argument('station-id');

            // Get lat lon
            $station = Station::where(['id' => $stationId])->first();
            $lat = $station->latitude;
            $lon = $station->longitude;

            // Api request
            $url = "http://api.worldweatheronline.com/premium/v1/weather.ashx?q=$lat,$lon&tp=$timeStamp&num_of_days=$numDays&key=$key&format=json";
            $response = $curl->get($url);

            $body = json_decode($response, true);
            $data = $body["data"];

            echo "Begin get data \n";
            \Log::info("Begin get data");

            // Start transaction
            DB::beginTransaction();
            try {
                $today = date('Y-m-d', strtotime("now"));

                // Delete duplicate data in world_weather_lv1 table (date_call_api = now)
                $worldWeatherLv1Old = WorldWeatherLv1::where([['date_call_api', '=', $today], ['station_id', '=', $stationId]])->first();

                // insert to level 1 table
                $worldWeatherLv1 = new WorldWeatherLv1();
                $worldWeatherLv1->date_call_api = $today;
                $worldWeatherLv1->station_id = $stationId;
                //$worldWeatherLv1->data = $response;
                $worldWeatherLv1->save();


                // insert to level 2 table
                if (empty($data["weather"])) {
                    sleep(60);
                    echo "Retry \n";
                    \Log::info("Retry");
                    continue;
                }

                $weathers = $data["weather"];

                foreach ($weathers as $weather) {
                    $date = $weather["date"];
                    $maxtempC = $weather["maxtempC"];
                    $mintempC = $weather["mintempC"];

                    // Delete duplicate data
                    WorldWeatherLv3::leftJoin('world_weather_lv2', 'world_weather_lv3.parent_id', '=', 'world_weather_lv2.id')
                        ->leftJoin('world_weather_lv1', 'world_weather_lv2.parent_id', '=', 'world_weather_lv1.id')
                        ->where([['world_weather_lv2.date', '=', $date],['world_weather_lv1.station_id', '=', $stationId]])
                        ->delete();

                    WorldWeatherLv2::leftJoin('world_weather_lv1', 'world_weather_lv2.parent_id', '=', 'world_weather_lv1.id')
                        ->where([['world_weather_lv2.date', '=', $date],['world_weather_lv1.station_id', '=', $stationId]])
                        ->delete();

//                $dataLv2s = WorldWeatherLv2::with([
//                    'lv1' => function($query) use ($subDistrictId) {
//                        $query->where(['sub_district_id' => $subDistrictId]);
//                    }
//                ])->where([['date', '=', $date]])->get();
//
//                foreach ($dataLv2s as $dataLv2) {
//                    $dataLv2->lv3()->delete();
//                    $dataLv2->delete();
//                }

                    $worldWeatherLv2 = new WorldWeatherLv2();
                    $worldWeatherLv2->parent_id = $worldWeatherLv1->id;
                    $worldWeatherLv2->date = $date;
                    $worldWeatherLv2->maxtempC = $maxtempC;
                    $worldWeatherLv2->mintempC = $mintempC;
                    $worldWeatherLv2->save();

                    // insert to level 3 table
                    $hourlyDatas = $weather["hourly"];
                    foreach ($hourlyDatas as $hourlyData) {
                        $timeVal = $hourlyData["time"];
                        $tempC = $hourlyData["tempC"];
                        $precipMM = $hourlyData["precipMM"];
                        $humidity = $hourlyData["humidity"];
                        $visibility = $hourlyData["visibility"];
                        $pressure = $hourlyData["pressure"];

                        $cloudcover = $hourlyData["cloudcover"];
                        $heatIndexC = $hourlyData["HeatIndexC"];
                        $dewPointC = $hourlyData["DewPointC"];
                        $windChillC = $hourlyData["WindChillC"];
                        $windGustMiles = $hourlyData["WindGustMiles"];
                        $feelsLikeC = $hourlyData["FeelsLikeC"];
                        $chanceofrain = $hourlyData["chanceofrain"];
                        $chanceofremdry = $hourlyData["chanceofremdry"];
                        $chanceofwindy = $hourlyData["chanceofwindy"];
                        $chanceofovercast = $hourlyData["chanceofovercast"];
                        $chanceofsunshine = $hourlyData["chanceofsunshine"];
                        $chanceoffrost = $hourlyData["chanceoffrost"];
                        $chanceofhightemp = $hourlyData["chanceofhightemp"];
                        $chanceoffog = $hourlyData["chanceoffog"];
                        $chanceofsnow = $hourlyData["chanceofsnow"];
                        $chanceofthunder = $hourlyData["chanceofthunder"];

                        $windspeedKmph = $hourlyData["windspeedKmph"];

                        // weather icon
                        $weatherIconUrlTag = $hourlyData["weatherIconUrl"];
                        $weatherIcon = "";
                        if (count($weatherIconUrlTag) > 0) {
                            $weatherIcon = $weatherIconUrlTag[0]["value"];
                        }


                        $worldWeatherLv3 = new WorldWeatherLv3();
                        $worldWeatherLv3->time_val = $timeVal;
                        $worldWeatherLv3->tempC = $tempC;
                        $worldWeatherLv3->precipMM = $precipMM;
                        $worldWeatherLv3->humidity = $humidity;
                        $worldWeatherLv3->visibility = $visibility;
                        $worldWeatherLv3->pressure = $pressure;
                        $worldWeatherLv3->cloudcover = $cloudcover;
                        $worldWeatherLv3->heatIndexC = $heatIndexC;
                        $worldWeatherLv3->dewPointC = $dewPointC;
                        $worldWeatherLv3->windChillC = $windChillC;
                        $worldWeatherLv3->windGustMiles = $windGustMiles;
                        $worldWeatherLv3->feelsLikeC = $feelsLikeC;
                        $worldWeatherLv3->chanceofrain = $chanceofrain;
                        $worldWeatherLv3->chanceofremdry = $chanceofremdry;
                        $worldWeatherLv3->chanceofwindy = $chanceofwindy;
                        $worldWeatherLv3->chanceofovercast = $chanceofovercast;
                        $worldWeatherLv3->chanceofsunshine = $chanceofsunshine;
                        $worldWeatherLv3->chanceoffrost = $chanceoffrost;
                        $worldWeatherLv3->chanceofhightemp = $chanceofhightemp;
                        $worldWeatherLv3->chanceoffog = $chanceoffog;
                        $worldWeatherLv3->chanceofsnow = $chanceofsnow;
                        $worldWeatherLv3->chanceofthunder = $chanceofthunder;
                        $worldWeatherLv3->chanceofthunder = $chanceofthunder;
                        $worldWeatherLv3->windspeedKmph = $windspeedKmph;

                        $worldWeatherLv3->weather_icon = $weatherIcon;
                        $worldWeatherLv3->parent_id = $worldWeatherLv2->id;
                        $worldWeatherLv3->save();
                    }
                }

                if (!empty($worldWeatherLv1Old)) {
                    $worldWeatherLv1Old->delete();
                }

                // Commit transaction
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                echo $e->getMessage();
                \Log::error("Error when get data from world weather api:" . $e->getMessage());
                echo $e->getTraceAsString();
                \Log::error("Error when get data from world weather api:" . $e->getTraceAsString());
            }

            break;
        }
    }


    private function getInfoExt() {
        while(true) {
            $curl = new cURL();

            // Get param
            $key = env('WORLD_WEATHER_API_KEY', "");
            $timeStamp = 24;
            $numDays = 10;
            $stationId = $this->argument('station-id');

            // Get lat lon
            $station = Station::where(['id' => $stationId])->first();
            $lat = $station->latitude;
            $lon = $station->longitude;

            // Api request
            $url = "http://api.worldweatheronline.com/premium/v1/weather.ashx?q=$lat,$lon&tp=$timeStamp&num_of_days=$numDays&key=$key&format=json";
            $response = $curl->get($url);

            $body = json_decode($response, true);
            $data = $body["data"];

            echo "Begin get data \n";
            \Log::info("Begin get data");

            // Start transaction
            DB::beginTransaction();
            try {
                $today = date('Y-m-d', strtotime("now"));

                // Delete duplicate data in world_weather_lv1 table (date_call_api = now)
                $oldData = WorldWeatherExt::where([['date_call_api', '=', $today], ['station_id', '=', $stationId]])->first();


                if (empty($data["weather"])) {
                    sleep(60);
                    echo "Retry \n";
                    \Log::info("Retry");
                    continue;
                }


                $weathers = $data["weather"];

                foreach ($weathers as $weather) {
                    $date = $weather["date"];

                    $hourlyDatas = $weather["hourly"];

                    if(!empty($hourlyDatas) && !empty($hourlyDatas[0])) {
                        $hourlyData = $hourlyDatas[0];
                        $precipMM = $hourlyData["precipMM"];

                        // Save to database
                        $wwo = new WorldWeatherExt();
                        $wwo->date_call_api = $today;
                        $wwo->date_data = $date;
                        $wwo->station_id = $stationId;
                        $wwo->precipMM = $precipMM;
                        $wwo->save();
                    }
                }

                if (!empty($oldData)) {
                    $oldData->delete();
                }

                // Commit transaction
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                echo $e->getMessage();
                \Log::error("Error when get data from world weather api:" . $e->getMessage());
                echo $e->getTraceAsString();
                \Log::error("Error when get data from world weather api:" . $e->getTraceAsString());
            }

            break;
        }
    }

    private function calculateCanhBao() {
        $canhbao = new WorldWeatherBusiness();
        $arrStrWarning = $canhbao->calculateCanhBao();

        $today = date('Y-m-d', strtotime('now'));
        $canhbaoNote = CanhBaoNote::where(['date_canh_bao' => $today])->first();

        if (!isset($canhbaoNote)) {
            $canhbaoNote = new CanhBaoNote();
            $canhbaoNote->date_canh_bao = $today;
            $canhbaoNote->text_note = json_encode($arrStrWarning);
            $canhbaoNote->save();
        } else {
            dd(json_decode($canhbaoNote->text_note));
        }

    }

    private function calculateCanhBaoRoute() {
        $canhbao = new WorldWeatherBusiness();
        $canhbao->calculateCanhBaoRoute();
    }

    //select sub_district.`name`,world_weather_history.date, world_weather_history.time , world_weather_history.tempC,
    //world_weather_history.precipMM, world_weather_history.humidity, world_weather_history.pressure
    //from world_weather_history
    //join sub_district on world_weather_history.xa_id = sub_district.id
    //where xa_id=1

    private function getInfoHistory() {
        $curl = new cURL();

        // Get param
        $key = env('WORLD_WEATHER_API_KEY', "");
        $timeStamp = 1;
        $startDate = "2015-08-05";
        $endDate = "2015-09-01";
        $subDistrictId = $this->argument('sub-district-id');

        // Get lat lon
        $subDistrict = SubDistrict::where(['id' => $subDistrictId])->first();
        $lat = $subDistrict->lat;
        $lon = $subDistrict->lon;

        // Api request
        $url = "http://api.worldweatheronline.com/premium/v1/past-weather.ashx?q=$lat,$lon&tp=$timeStamp&date=$startDate&enddate=$endDate&key=$key&format=json";
        $response = $curl->get($url);

        $body = json_decode($response, true);
        $data = $body["data"];

        // Start transaction
        DB::beginTransaction();
        try {

            $weathers = $data["weather"];
            foreach ($weathers as $weather) {
                $date = $weather["date"];

                $hourlyDatas = $weather["hourly"];
                foreach ($hourlyDatas as $hourlyData) {
                    $timeVal = $hourlyData["time"];
                    $tempC = $hourlyData["tempC"];
                    $precipMM = $hourlyData["precipMM"];
                    $humidity = $hourlyData["humidity"];
                    $pressure = $hourlyData["pressure"];

                    $historyModel = new WorldWeatherHistory();
                    $historyModel->xa_id = $subDistrictId;
                    $historyModel->date = $date;
                    $historyModel->time = $timeVal;
                    $historyModel->tempC = $tempC;
                    $historyModel->precipMM = $precipMM;
                    $historyModel->humidity = $humidity;
                    $historyModel->pressure = $pressure;

                    $historyModel->save();
                }
            }

            // Commit transaction
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            \Log::error("Error when get data from world weather api:" . $e->getMessage());

            echo $e->getTraceAsString();
            \Log::error("Error when get data from world weather api:" . $e->getTraceAsString());
        }
    }

    private function test() {
        //SendMailUtility::sendMail();
        //SendSMSUtility::sendSMS();
    }

    private function getInfoByDistrict() {
        while(true) {
            $curl = new cURL();

            // Get param
            $key = env('WORLD_WEATHER_API_KEY', "");
            $timeStamp = 1;
            $numDays = 10;
            $subDistrictId = $this->argument('sub-district-id');

            // Get lat lon
            $subDistrict = SubDistrict::where(['id' => $subDistrictId])->first();
            $lat = $subDistrict->lat;
            $lon = $subDistrict->lon;

            // Api request
            $url = "http://api.worldweatheronline.com/premium/v1/weather.ashx?q=$lat,$lon&tp=$timeStamp&num_of_days=$numDays&key=$key&format=json";
            $response = $curl->get($url);

            $body = json_decode($response, true);
            $data = $body["data"];

            echo "Begin get data \n";
            \Log::info("Begin get data");

            // Start transaction
            DB::beginTransaction();
            try {
                $today = date('Y-m-d', strtotime("now"));

                // Delete duplicate data in world_weather_lv1 table (date_call_api = now)
                $worldWeatherLv1Old = WorldWeatherLv1::where([['date_call_api', '=', $today], ['sub_district_id', '=', $subDistrictId]])->first();

                // insert to level 1 table
                $worldWeatherLv1 = new WorldWeatherLv1();
                $worldWeatherLv1->date_call_api = $today;
                $worldWeatherLv1->sub_district_id = $subDistrictId;
                //$worldWeatherLv1->data = $response;
                $worldWeatherLv1->save();


                // insert to level 2 table
                $weathers = $data["weather"];

                if (empty($weathers)) {
                    sleep(60);
                    echo "Retry \n";
                    \Log::info("Retry");
                    continue;
                }

                foreach ($weathers as $weather) {
                    $date = $weather["date"];
                    $maxtempC = $weather["maxtempC"];
                    $mintempC = $weather["mintempC"];

                    // Delete duplicate data
                    WorldWeatherLv3::leftJoin('world_weather_lv2', 'world_weather_lv3.parent_id', '=', 'world_weather_lv2.id')
                        ->leftJoin('world_weather_lv1', 'world_weather_lv2.parent_id', '=', 'world_weather_lv1.id')
                        ->where([['world_weather_lv2.date', '=', $date],['world_weather_lv1.sub_district_id', '=', $subDistrictId]])
                        ->delete();

                    WorldWeatherLv2::leftJoin('world_weather_lv1', 'world_weather_lv2.parent_id', '=', 'world_weather_lv1.id')
                        ->where([['world_weather_lv2.date', '=', $date],['world_weather_lv1.sub_district_id', '=', $subDistrictId]])
                        ->delete();

//                $dataLv2s = WorldWeatherLv2::with([
//                    'lv1' => function($query) use ($subDistrictId) {
//                        $query->where(['sub_district_id' => $subDistrictId]);
//                    }
//                ])->where([['date', '=', $date]])->get();
//
//                foreach ($dataLv2s as $dataLv2) {
//                    $dataLv2->lv3()->delete();
//                    $dataLv2->delete();
//                }

                    $worldWeatherLv2 = new WorldWeatherLv2();
                    $worldWeatherLv2->parent_id = $worldWeatherLv1->id;
                    $worldWeatherLv2->date = $date;
                    $worldWeatherLv2->maxtempC = $maxtempC;
                    $worldWeatherLv2->mintempC = $mintempC;
                    $worldWeatherLv2->save();

                    // insert to level 3 table
                    $hourlyDatas = $weather["hourly"];
                    foreach ($hourlyDatas as $hourlyData) {
                        $timeVal = $hourlyData["time"];
                        $tempC = $hourlyData["tempC"];
                        $precipMM = $hourlyData["precipMM"];
                        $pressure = $hourlyData["pressure"];

                        // weather icon
                        $weatherIconUrlTag = $hourlyData["weatherIconUrl"];
                        $weatherIcon = "";
                        if (count($weatherIconUrlTag) > 0) {
                            $weatherIcon = $weatherIconUrlTag[0]["value"];
                        }


                        $worldWeatherLv3 = new WorldWeatherLv3();
                        $worldWeatherLv3->time_val = $timeVal;
                        $worldWeatherLv3->tempC = $tempC;
                        $worldWeatherLv3->precipMM = $precipMM;
                        $worldWeatherLv3->pressure = $pressure;
                        $worldWeatherLv3->weather_icon = $weatherIcon;
                        $worldWeatherLv3->parent_id = $worldWeatherLv2->id;
                        $worldWeatherLv3->save();
                    }
                }

                if (!empty($worldWeatherLv1Old)) {
                    $worldWeatherLv1Old->delete();
                }

                // Commit transaction
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                echo $e->getMessage();
                \Log::error("Error when get data from world weather api:" . $e->getMessage());
                echo $e->getTraceAsString();
                \Log::error("Error when get data from world weather api:" . $e->getTraceAsString());
            }

            break;
        }
    }

}
