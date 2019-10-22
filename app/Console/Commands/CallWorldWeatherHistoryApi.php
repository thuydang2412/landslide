<?php

namespace App\Console\Commands;

use anlutro\cURL\cURL;
use App\Http\Controllers\Common\SendMailUtility;
use App\Http\Controllers\Common\SendSMSUtility;
use App\Models\Station;
use App\Models\SubDistrict;
use App\Models\WorldWeatherHistory;
use App\Models\WorldWeatherLv1;
use App\Models\WorldWeatherLv2;
use App\Models\WorldWeatherLv3;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


// php artisan CallWorldWeatherHistoryApi getInfoHistory 21 2008-07-01 2008-08-01

class CallWorldWeatherHistoryApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CallWorldWeatherHistoryApi {action} {sub-district-id} {start-date} {end-date}';

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

        if ($action === 'getInfoHistory') {
            $this->getInfoHistory();
        }
    }


    //select sub_district.`name`,world_weather_history.date, world_weather_history.time , world_weather_history.tempC,
    //world_weather_history.precipMM, world_weather_history.humidity, world_weather_history.pressure
    //from world_weather_history
    //join sub_district on world_weather_history.xa_id = sub_district.id
    //where xa_id=1

    private function getInfoHistory() {
        while(true) {
            $curl = new cURL();

            // Get param
            $key = env('WORLD_WEATHER_API_KEY', "");
            $timeStamp = 1;

            //$startDate = "2008-07-01";
            //$endDate = "2008-08-01";

            $subDistrictId = $this->argument('sub-district-id');
            $startDate = $this->argument('start-date');
            $endDate = $this->argument('end-date');

            // Get lat lon
            //$subDistrict = SubDistrict::where(['id' => $subDistrictId])->first();
            //$lat = $subDistrict->lat;
            //$lon = $subDistrict->lon;

            $subDistrict = Station::where(['id' => $subDistrictId])->first();
            $lat = $subDistrict->latitude;
            $lon = $subDistrict->longitude;

            // Api request
            $url = "http://api.worldweatheronline.com/premium/v1/past-weather.ashx?q=$lat,$lon&tp=$timeStamp&date=$startDate&enddate=$endDate&key=$key&format=json";
            $response = $curl->get($url);

            $body = json_decode($response, true);
            $data = $body["data"];

            // Start transaction
            DB::beginTransaction();
            try {

                $weathers = $data["weather"];

                if (empty($weathers)) {
                    sleep(60);
                    echo "Retry \n";
                    \Log::info("Retry");
                    continue;
                }

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

            break;
        }
    }
}
