<?php

namespace App\Console\Commands;

use App\Models\IGPLv1;
use App\Models\IGPLv2;
use App\Models\IGPLv3;
use App\Models\SubDistrict;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// php artisan CallIGPApi getInfo 3
// 
class CallIGPApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CallIGPApi {action} {sub-district-id}';

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
    }

    private function getInfo() {
        $subDistrictId = $this->argument('sub-district-id');
        $today = date('Ymd', strtotime("now"));
        //$today="20170221";

        // Get district info
        $subDistrict = SubDistrict::where(['id' => $subDistrictId])->first();
        $igpFile = $subDistrict->igp_file;

        // Api request
        $url = "ftp://igp-vast.vn/agpc/hagiang/$today/$igpFile";
        $response = file_get_contents($url);
        $jsonData = json_decode($response, true);

        //echo "<pre>";
        //var_dump($data["data"][0]["data"][0]["luong_mua"]);
        //var_dump($data["Xa"]);
        //echo "</pre>";die;

        DB::beginTransaction();
        try {
            // Delete duplicate data
            $igpLv1Old = IGPLv1::where([['date_call_api', '=', $today], ['sub_district_id', '=', $subDistrictId]])->first();

            // Insert IGPLv1 data
            $igpLv1 = new IGPLv1();
            $igpLv1->date_call_api = $today;
            $igpLv1->sub_district_id = $subDistrictId;
            $igpLv1->save();

            // insert to level 2 table
            $datas = $jsonData["data"];

            foreach ($datas as $data) {
                $date = \DateTime::createFromFormat('d-m-Y', $data['date']);
                $dateString = $date->format('Y-m-d');

                // Delete duplicate data
                IGPLv3::leftJoin('igp_lv2', 'igp_lv3.parent_id', '=', 'igp_lv2.id')
                    ->leftJoin('igp_lv1', 'igp_lv2.parent_id', '=', 'igp_lv1.id')
                    ->where([['igp_lv2.date', '=', $dateString],['igp_lv1.sub_district_id', '=', $subDistrictId]])
                    ->delete();

                IGPLv2::leftJoin('igp_lv1', 'igp_lv2.parent_id', '=', 'igp_lv1.id')
                    ->where([['igp_lv2.date', '=', $dateString],['igp_lv1.sub_district_id', '=', $subDistrictId]])
                    ->delete();

                $igpLv2 = new IGPLv2();
                $igpLv2->parent_id = $igpLv1->id;
                $igpLv2->date = $dateString;
                $igpLv2->save();

                $dataLv3s = $data['data'];
                foreach ($dataLv3s as $dataLv3) {
                    $igpLv3 = new IGPLv3();
                    $igpLv3->parent_id = $igpLv2->id;
                    $igpLv3->time_val = $dataLv3["hour"];
                    $igpLv3->precipMM = $dataLv3["luong_mua"];
                    $igpLv3->humidity = $dataLv3["do_am"];
                    $igpLv3->save();
                }
            }

            if (!empty($igpLv1Old)) {
                $igpLv1Old->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getTraceAsString();
            \Log::error("Error when get data from world weather api:" . $e->getTraceAsString());
        }
    }
}
