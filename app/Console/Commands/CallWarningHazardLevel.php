<?php

namespace App\Console\Commands;

use anlutro\cURL\cURL;
use App\Models\CanhBaoTemp01;
use App\Models\CanhBaoTemp01Pessl;
use App\Models\CanhBaoTemp01WWO;
use App\Models\Station;
use App\Models\StationPessl;
use Illuminate\Console\Command;

class CallWarningHazardLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CallWarningHazardLevel';

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
        $this->calculate();
    }

    ///
    /// Output
    /// [
    ///     [
    ///         id:
    ///         day1:
    ///         day2:
    ///         ...
    ///         day7:
    ///     ],
    ///     ...
    /// ]

    public function calculate() {
        $idWarning = [1, 2, 3, 4, 5, 6, 7, 8];
        $idPessStation = [1, 3, 3, 5, 6, 4, 7, 6];
        $idStation = [1, 4, 6, 11, 15, 27, 3, 24];

        $this->getAccessToken();

        for ($i = 0; $i < count($idWarning); $i++) {

            if ($i != 1) {
                continue;
            }

            $index = $i;
            // Lấy dữ liệu 12 ngày quá khứ

            $arrPassData = $this->getPassData($idPessStation[$index]);

            // Lấy dữ liệu 7 ngày tiếp theo
            $arrFutureData = $this->getFutureData($idStation[$index]);

            // Tính toán dựa trên dữ liệu 12 ngày quá khứ và 7 ngày tương lai
            $arrOutput = $this->calculateWarningLevel($arrPassData, $arrFutureData);

            if (count($arrOutput) >= 6) {
                $model = CanhBaoTemp01::query()
                    ->where(["id" => $idWarning[$index]])
                    ->first();

                if (isset($model)) {
                    $model->day_01 = $arrOutput[0];
                    $model->day_02 = $arrOutput[1];
                    $model->day_03 = $arrOutput[2];
                    $model->day_04 = $arrOutput[3];
                    $model->day_05 = $arrOutput[4];
                    $model->day_06 = $arrOutput[5];
                    $model->day_07 = $arrOutput[6];
                    $model->save();
                }

            }
        }

    }

    /// Input: Dữ liệu 12 ngày quá khứ và 7 ngày tiếp theo.
    /// Output: Cảnh báo cho 7 ngày tiếp theo
    function calculateWarningLevel($arrPassData, $arrFutureData) {
        $arrInput = [];
        $arrOutput = [];

        // Copy dữ liệu 12 ngày quá khứ
        for ($i = 0; $i < count($arrPassData); $i++) {
            $arrInput[] = $arrPassData[$i];
        }

        // Copy dữ liệu 7 ngày tương lai, trừ ngày đầu tiên, vì ngày đầu tiên đã lấy ở dữ liệu quá khứ rồi
        for ($i = 0; $i < count($arrFutureData); $i++) {
            if ($i == 0) {
                continue;
            }

            $arrInput[] = $arrFutureData[$i];
        }

        if (count($arrInput) < 18) {
            echo "Không đủ dữ liệu";
            return;
        }

        // Tính lượng mưa 12 ngày
        $r12d = 0;
        for($i=0; $i < 12; $i++) {
            $r12d += $arrInput[$i];
        }

        $this->printLine($r12d);

        for($i=11; $i < 18; $i++) {
            // Lượng mưa ngày
            $rDay = $arrInput[$i];

            // Lượng mưa 6 ngày quá khứ
            $r6d = 0;

            for($k = $i - 5; $k <= $i; $k++) {
                $r6d += $arrInput[$k];
            }

            //$this->printLine($r6d);

            // Tính điểm
            $score = 0;
            if ($rDay >= 180) {
                $score = 7;
            } else if ($rDay >= 150) {
                $score = 6;
            } else if ($rDay >= 120) {
                $score = 5;
            } else if ($rDay >= 100) {
                $score = 4;
            } else if ($rDay >= 80) {
                $score = 3;
            } else if ($rDay >= 60) {
                $score = 2;
            } else if ($rDay >= 30) {
                $score = 1;
            } else if ($rDay < 30) {
                $score = 0;
            }

            //$this->printLine($score);

            $score12D = 0;
            if ($r12d >= 550) {
                $score12D = 5;
            } else if ($r12d >= 500) {
                $score12D = 4;
            } else if ($r12d >= 450) {
                $score12D = 3;
            } else if ($r12d >= 400) {
                $score12D = 2;
            } else if ($r12d >= 300) {
                $score12D = 1;
            } else if ($r12d < 300) {
                $score12D = 0;
            }

            //$this->printLine($score12D);

            $score6D = 0;
            if ($r6d >= 350) {
                $score6D = 5;
            } else if ($r6d >= 310) {
                $score6D = 4;
            } else if ($r6d >= 270) {
                $score6D = 3;
            } else if ($r6d >= 240) {
                $score6D = 2;
            } else if ($r6d >= 200) {
                $score6D = 1;
            } else if ($r6d < 200) {
                $score6D = 0;
            }

            //$this->printLine($score6D);


            if ($score12D >= $score6D) {
                $scoreSum = $score12D + $score;
            } else {
                $scoreSum = $score6D + $score;
            }

            //$this->printLine($scoreSum);

            // Nếu mưa ngày lớn hơn 15 thì tính toán tiếp
            if ($rDay >= 15) {
                $level = 0;
                if ($scoreSum >= 11) {
                    $level = 4; // Rất cao
                } else if ($scoreSum >= 9) {
                    $level = 3; // Cao
                } else if ($scoreSum >= 7) {
                    $level = 2; // TB
                } else if ($scoreSum >= 5) {
                    $level = 1; // Thấp
                } else if ($scoreSum < 5) {
                    $level = 0; // Không
                }

                //$this->printLine($level);

                $arrOutput[] = $level;
            } else {
                // Nếu mưa ngày nhỏ hơn 15 thì không có nguy cơ trượt lở
                $arrOutput[] = 0;
            }
        }

        return $arrOutput;

    }

    function getFutureData($id) {
        $station = Station::query()
            ->where(["id" => $id])
            ->first();

        if (empty($station)) {
            echo "Not found station for id $id";
            return [];
        } else {
            return $this->getWWOInfo($station);
        }
    }

    function getWWOInfo($station) {
        $lat = $station->latitude;
        $lon = $station->longitude;

        // Get param
        $key = env('WORLD_WEATHER_API_KEY', "");
        $timeStamp = 24;
        $numDays = 7;
        $url = "http://api.worldweatheronline.com/premium/v1/weather.ashx?q=$lat,$lon&tp=$timeStamp&num_of_days=$numDays&key=$key&format=json";

        $retryCount = 0;

        while(true) {
            $retryCount++;

            $curl = new cURL();

            echo "Begin get data \n";

            // Api request
            $response = $curl->get($url);

            $body = json_decode($response, true);
            $data = $body["data"];

            if (empty($body) || empty($data) || empty($data["weather"])) {

                if ($retryCount >= 3) {
                    return [];
                }

                sleep(60);
                echo "Retry \n";
                continue;
            }

            echo "Get data success";

            $weathers = $data["weather"];

            $arrPrecipMM = [];

            foreach ($weathers as $weather) {
                $date = $weather["date"];

                $hourlyDatas = $weather["hourly"];
                foreach ($hourlyDatas as $hourlyData) {
                    $precipMM = $hourlyData["precipMM"];
                    $arrPrecipMM[] = $precipMM;

                    $wwoData = new CanhBaoTemp01WWO();
                    $wwoData->station_id = $station->id;
                    $wwoData->time = $date;
                    $wwoData->precipMM = $precipMM;
                    $wwoData->save();
                }
            }

            return $arrPrecipMM;
        }
    }

    // Get Pass data by pessl station
    private $accessToken;

    function getPassData($id) {
        $station = StationPessl::query()
            ->where(["id" => $id])
            ->first();

        if (isset($station)) {
            echo "Get pessl data for station $station->station_id";
            return $this->getPesslData($station->station_id);
        } else {
            echo "Not found station for id $id";
            return [];
        }

    }

    function getPesslData($stationId) {
        $curl = curl_init();

        $header = array(
            'Authorization' => "Authorization: Bearer " . $this->accessToken,
            'Accept' => "application/json"
        );

        curl_setopt($curl,CURLOPT_URL, "https://api.tramthoitiet.vn/v1/data/$stationId/hourly/last/12d");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Will return the response, if false it print the response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status_code != 200) {
            return;
        }

        $dataJson = json_decode($response, true);

        $dates = $dataJson["dates"];
        $data = $dataJson["data"];

        $dataPrecipitation = $data[2];

        $arrDataPrecipMM = [];

        $index = 0;
        foreach ($dates as $dateItem) {
            $pesslData = new CanhBaoTemp01Pessl();

            // Station id
            $pesslData->station_id = $stationId;

            // Time
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateItem);
            $pesslData->time = date_format($date, 'Y-m-d H:i:s');

            // PrecipMM
            $pesslData->precipMM = $dataPrecipitation["values"]["sum"][$index];
            $arrDataPrecipMM[] = $dataPrecipitation["values"]["sum"][$index];

            // Save data
            $pesslData->save();

            $index++;
        }

        return $arrDataPrecipMM;
    }

    function getPesslDataOld($stationId) {
        $curl = curl_init();

        $header = array(
            'Authorization' => "Authorization: Bearer " . $this->accessToken,
            'Accept' => "application/json"
        );

        curl_setopt($curl,CURLOPT_URL, "https://api.fieldclimate.com/v1/data/$stationId/daily/last/12d");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Will return the response, if false it print the response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status_code != 200) {
            return;
        }

        $data = json_decode($response, true);

        $data = $data["data"];

        $arrDataPrecipMM = [];

        foreach ($data as $dataItem) {
            $pesslData = new CanhBaoTemp01Pessl();

            // Station id
            $pesslData->station_id = $stationId;

            // Time
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dataItem["date"]);
            $pesslData->time = date_format($date, 'Y-m-d H:i:s');

            // PrecipMM
            $pesslData->precipMM = $dataItem["5_X_X_6_sum"];
            $arrDataPrecipMM[] = $dataItem["5_X_X_6_sum"];

            // Save data
            $pesslData->save();
        }

        return $arrDataPrecipMM;
    }

    private function getAccessToken() {
        $clientId = "FieldclimateNG";
        $clientSecret = "618a5baf48287eecbdfc754e9c933a";
        $userName = "pcttquangnam";
        $password = "Pcttquangnam";

        // Call api get access token ===========================================================
        $curl = curl_init();

        $postFields = array('client_id' => $clientId,
            'client_secret' => $clientSecret,
            'username' => $userName,
            'password' => $password,
            'grant_type' => 'password',);

        curl_setopt($curl,CURLOPT_URL, "https://oauth.fieldclimate.com/token");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Will return the response, if false it print the response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status_code != 200) {
            return;
        }

        $data = json_decode($response, true);
        $this->accessToken = $data["access_token"];
    }

    function printLine($st) {
        echo $st . "\n";
    }
}
