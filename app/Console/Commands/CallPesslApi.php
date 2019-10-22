<?php

namespace App\Console\Commands;

use App\Models\PesslData;
use App\Models\StationPessl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


// php artisan CallPesslApi

class CallPesslApi extends Command
{
    private $accessToken;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CallPesslApi';

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
        $this->getData();
    }


    private function getData()
    {
        // Get access token
        $this->getAccessToken();

        // Get station data
        $this->getDataAllStations();
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

        //curl_setopt($curl,CURLOPT_URL, "https://oauth.fieldclimate.com/token");
        curl_setopt($curl,CURLOPT_URL, "https://oauth.tramthoitiet.vn/token");
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

    private function getDataAllStations() {
        $stations = StationPessl::all();
        foreach($stations as $station) {
            $stationId = $station->station_id;
            $this->getDataStation($stationId);
        }
    }


    private function getDataStation($stationId) {
        // Call api get data ================================================================
        echo "Get data station $stationId \n";
        $curl = curl_init();

        $header = array(
            'Authorization' => "Authorization: Bearer " . $this->accessToken,
            'Accept' => "application/json"
        );

        curl_setopt($curl,CURLOPT_URL, "https://api.tramthoitiet.vn/data/$stationId/hourly/last/24h");
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

        $dataAirTemperature = $data[0];
        $dataSolarPanel = $data[1];
        $dataPrecipitation = $data[2];
        $dataBattery = $data[3];
        $dataLatitude = $data[4];
        $dataLongitude = $data[5];
        $dataAltitude = $data[6];
        $dataHorizontalDilusionOfPosition = $data[7];

        $index = 0;
        foreach ($dates as $dateItem) {
            $pesslData = new PesslData();

            // Time
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateItem);
            $pesslData->date_value = date_format($date, 'Y-m-d');
            $pesslData->hour_value = date_format($date, 'H:i:s');

            // Data
            $pesslData->station_id = $stationId;
            $pesslData->data_1_X_X_497_avg = $dataAirTemperature["values"]["avg"][$index];
            $pesslData->data_1_X_X_497_max = $dataAirTemperature["values"]["max"][$index];
            $pesslData->data_1_X_X_497_min = $dataAirTemperature["values"]["min"][$index];

            $pesslData->data_4_X_X_30_last = $dataSolarPanel["values"]["last"][$index];
            $pesslData->data_5_X_X_6_sum = $dataPrecipitation["values"]["sum"][$index];
            $pesslData->data_7_X_X_7_last = $dataBattery["values"]["last"][$index];
            $pesslData->data_21_X_X_650_last = $dataLatitude["values"]["last"][$index];
            $pesslData->data_22_X_X_651_last = $dataLongitude["values"]["last"][$index];
            $pesslData->data_23_X_X_652_last = $dataAltitude["values"]["last"][$index];
            $pesslData->data_24_X_X_659_last = $dataHorizontalDilusionOfPosition["values"]["last"][$index];

            // Save data
            $pesslData->save();

            $index++;
        }

        return;
    }

    private function getDataStationOld($stationId) {
        // Call api get data ================================================================
        echo "Get data station $stationId \n";
        $curl = curl_init();

        $header = array(
            'Authorization' => "Authorization: Bearer " . $this->accessToken,
            'Accept' => "application/json"
        );

        curl_setopt($curl,CURLOPT_URL, "https://api.fieldclimate.com/v1/data/$stationId/hourly/last/24h");
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

        foreach ($data as $dataItem) {
            $pesslData = new PesslData();

            // Time
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dataItem["date"]);
            $pesslData->date_value = date_format($date, 'Y-m-d');
            $pesslData->hour_value = date_format($date, 'H:i:s');

            // Data
            $pesslData->station_id = $stationId;
            $pesslData->data_1_X_X_497_avg = $dataItem["1_X_X_497_avg"];
            $pesslData->data_1_X_X_497_max = $dataItem["1_X_X_497_max"];
            $pesslData->data_1_X_X_497_min = $dataItem["1_X_X_497_min"];
            $pesslData->data_4_X_X_30_last = $dataItem["4_X_X_30_last"];
            $pesslData->data_5_X_X_6_sum = $dataItem["5_X_X_6_sum"];
            $pesslData->data_7_X_X_7_last = $dataItem["7_X_X_7_last"];
            $pesslData->data_21_X_X_650_last = $dataItem["21_X_X_650_last"];
            $pesslData->data_22_X_X_651_last = $dataItem["22_X_X_651_last"];
            $pesslData->data_23_X_X_652_last = $dataItem["23_X_X_652_last"];
            $pesslData->data_24_X_X_659_last = $dataItem["24_X_X_659_last"];

            // Save data
            $pesslData->save();
        }

        return;
    }
}
