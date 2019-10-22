<?php

namespace App\Console\Commands;

use App\Models\SubDistrictBoundary;
use Illuminate\Console\Command;

/**
 * Dùng file kml export từ arcgis, google earth để lấy tọa độ
 * Copy tọa độ vào file txt để trong thư mục data/boundary/1_Khuon_Lung.txt
 * 104.5334650175589,22.54936409117279,0
 * 104.5333697102668,22.54937447272345,0
 * ......
 * Chạy lệnh
 * php artisan CalculateBoundary [id xã] [Tên file data]
 * => Sẽ insert vào bảng sub_district_boundary
 * Có thể thay đổi độ dày của data bằng biến step
 *
 */

// php artisan CalculateBoundary 1 1_Khuon_Lung.txt
// php artisan CalculateBoundary 2 2_Na_Chi.txt
// php artisan CalculateBoundary 3 3_Nam_Dan.txt
// php artisan CalculateBoundary 4 4_Quang_Nguyen.txt
// php artisan CalculateBoundary 5 5_Che_La.txt
// php artisan CalculateBoundary 6 6_Ban_Ngo.txt
// php artisan CalculateBoundary 7 7_Nan_Ma.txt
// php artisan CalculateBoundary 8 8_Thu_Ta.txt
// php artisan CalculateBoundary 9 9_Ta_Nhiu.txt
// php artisan CalculateBoundary 10 10_Coc_Re.txt
// php artisan CalculateBoundary 11 11_Coc_Pai.txt
// php artisan CalculateBoundary 12 12_Ngan_Chien.txt
// php artisan CalculateBoundary 13 13_Pa_Vay_Su.txt
// php artisan CalculateBoundary 14 14_Trung_Thinh.txt
// php artisan CalculateBoundary 15 15_Then_Phang.txt
// php artisan CalculateBoundary 16 16_Chi_Ca.txt
// php artisan CalculateBoundary 17 17_Ban_Diu.txt
// php artisan CalculateBoundary 18 18_Xin_Man.txt
// php artisan CalculateBoundary 19 19_Nan_Xin.txt

class CalculateBoundary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CalculateBoundary {sub-district-id} {data-file-name}';

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
        $subDistrictId = $this->argument('sub-district-id');
        $dataFileName = $this->argument('data-file-name');

        // Delete old data
        SubDistrictBoundary::where(['sub_district_id' => $subDistrictId])->delete();

        $file = fopen("data/boundary/$dataFileName","r");

        $i=0;
        $index = 0;
        $step = 10;
        while(! feof($file))
        {
            $line = fgets($file);

            if($i % $step === 0){
                $arrData = explode(",", $line);
                $lon = $arrData[0];
                $lat = $arrData[1];
                $model = new SubDistrictBoundary();
                $model->sub_district_id = $subDistrictId;
                $model->index = $index;
                $model->lat = $lat;
                $model->lon = $lon;
                $model->save();
                $index++;
            }

            $i++;
        }

        fclose($file);
    }
}
