@extends("site.base")

@section('title', 'Trượt lở Quảng Nam')

@section('css-define')
    <link rel="stylesheet" href="{{ URL::asset('libs/jqplot/jquery.jqplot.css') }}">

    <!-- Datatables -->
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('libs/bxslider/jquery.bxslider.min.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            @foreach($warningInfos as $warningInfo)
                <h4 class="modal-title" style="color:red;font-weight:bold; text-align: center">THÔNG TIN CẢNH BÁO TRƯỢT LỞ</h4>
                <h5><b><i>Thời gian dự báo: {{$warningInfo->hour_warning}} ngày {{$warningInfo->date_warning}}</i></b></h5>
                
                <h5><b><i>Ngày tính từ 12:00 ngày dự báo đến cận 12:00 ngày hôm sau</i></b></h5>
                {{--<h5 style="color: red";><b><i>Trượt lở diện rộng từ ngày 3/7 đến sáng 5/7/2019</i></b></h5>--}}
            @endforeach
                
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr class ="bg-primary">
                        <th colspan="2" class="text-center">Khu vực</th>
                        <th colspan="5" class="text-center">Nguy cơ trượt lở theo từng ngày</th>
                    </tr>
                    <tr>
                        <th scope="col" class="text-center">TT</th>
                        {{-- <th scope="col" class="text-center">Tỉnh</th> --}}
                        <th scope="col" class="text-center">Huyện</th>
                        {{-- <th scope="col" class="text-center">Xã</th> --}}

                    @foreach($warningInfos as $warningInfo)
                        <th scope="col" class="text-center">{{$warningInfo->day_01}}</th>
                        <th scope="col" class="text-center">{{$warningInfo->day_02}}</th>
                        <th scope="col" class="text-center">{{$warningInfo->day_03}}</th>
                        <th scope="col" class="text-center">{{$warningInfo->day_04}}</th>
                        <th scope="col" class="text-center">{{$warningInfo->day_05}}</th>
                    @endforeach
                    </tr> 
                    </thead>
                    <tbody>
                    @foreach($warningLandsiles as $warningLandsile)
                        <tr>
                            <td class="text-center">{{$warningLandsile->id_warning}}</td>
                            {{-- <td>{{$warningLandsile->province_name}}</td> --}}
                            <td>{{$warningLandsile->district_name}}</td>
                            {{-- <td>{{$warningLandsile->village_name}}</td> --}}
                            <td class="text-center">{{$warningLandsile->day_01}}</td>
                            <td class="text-center">{{$warningLandsile->day_02}}</td>
                            <td class="text-center">{{$warningLandsile->day_03}}</td>
                            <td class="text-center">{{$warningLandsile->day_04}}</td>
                            <td class="text-center">{{$warningLandsile->day_05}}</td>
                        </tr>
                    @endforeach

                    @foreach($warningInfos as $warningInfo)
                    <tr>   
                    <td colspan="7">{{$warningInfo->note}}</td>
                        {{--<td>Khu vực nghiên cứu không có nguy cơ trượt lở</td>--}}
                    </tr>

                    @endforeach
                    </tbody>
                </table>
                {{-- <p class="text-justify">
                    <i>
                        <b style="color:red">Ghi chú:</b> Dự báo tiến hành trong 13 tỉnh: Bắc Kạn, Cao Bằng, Điện Biên, Hà Giang, Hòa Bình, Lai Châu, Lào Cai, Sơn La, Tuyên Quang, Yên Bái, Thanh Hóa, Nghệ An, Quảng Nam.
                    </i>
                </p> --}}
                <p>
                    <i>Kết quả được cập nhật liên tục sau 12-24h.</i></p>
                    <!-- Chú giải - có định -->
                <h5 class="modal-title"><b>Chú giải các cấp nguy cơ trượt lở</b></h5>
                <p></p>
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-primary">
                        <th scope="col" class="text-center" style="width: 12%">Ký hiệu</th>
                        <th scope="col" class="text-center" style="width: 12%">Nguy cơ</th>
                        <th scope="col" class="text-center" style="width: 76%">Chú giải vắn tắt</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center">0</td>
                        <td class="text-center">Không có</td>
                        <td>Hiếm khi xảy ra trượt lở</td>

                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-center">Rất thấp</td>
                        <td><b><i>Chú ý</i></b> nguy cơ phát sinh trượt lở</td>

                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-center">Thấp</td>
                        <td class="text-justify">
                            <b><i>Chú ý</i></b>
                            trượt lở có thể phát sinh cục bộ, nhất là các vị trí đã có dấu hiệu nguy hiểm như khe nứt tách, khu vực đã có dấu hiệu dịch chuyển từ trước, khu vực đang khắc phục trượt lở (nếu có),…</td>

                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-center">Trung bình</td>
                        <td class="text-justify"><b><i>Cảnh báo</i></b> phát sinh trượt lở cục bộ, chủ yếu trượt lở
                            có quy mô nhỏ. Chủ động cảnh giác đối với các khu vực nguy hiểm. </td>

                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-center">Cao</td>
                        <td class="text-justify"><b><i>Cảnh báo</i></b> nguy cơ trượt lở trên diện rộng, có thể phát sinh
                            trượt lở quy mô lớn. Theo dõi và sẵn sàng ứng phó ở các khu vực nguy hiểm. </td>

                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-center">Rất cao</td>
                        <td class="text-justify"><b><i>Trượt lở trên diện rộng,</i></b> phát sinh trượt lở quy mô lớn.
                            Di chuyển dân trong vùng nguy hiểm đến nơi an toàn.</td>

                    </tr>
                    </tbody>
                </table>


                <p class="text-justify">
                    <i>
                        <b style="color:red">Chú ý:</b> Cảnh báo dựa trên các luận cứ khoa học của đề tài mã số ĐTĐL.CN-23/17, không thay thế cho các bản tin chính thức của các cơ quan Nhà nước về dự báo thời tiết và cảnh báo thiên tai.
                    </i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
        <input id="input_is_admin" type="hidden" value="{{$isAdmin}}">
        <div class="home-container">
            <div class="row no-padding">

                <div class="col-md-12">

                    @if (count($arrCanhBao) > 0)
                        <input type="hidden" id="isShowPopupCanhBao" value="1">
                    @else
                        <input type="hidden" id="isShowPopupCanhBao" value="0">
                    @endif

                    <div class="row no-padding">
                        {{--<span class="section-title">Bản đồ</span>--}}

                        {{--Map--}}
                        <div class="col-md-8">
                            <div id="map" class="md-mt-30" style="width: 100%; height: 75vh; border: 1px solid #2D3F51">
                                    
                            </div>
                        </div>

                        {{--Checkbox panel--}}
                        <div class="col-md-4 md-mt-30">
                            <div style="border: 1px solid; padding: 10px 20px 0 20px;">

                                {{--<div class="checkbox">--}}
                                {{--<label><input type="checkbox" class="cb-layer" layer-id="diem_truot_nhom_khao_sat"/><span--}}
                                {{--class="layer-name">Điểm trượt do nhóm khảo sát</span></label>--}}
                                {{--</div>--}}

                                {{--<div class="checkbox">--}}
                                {{--<label><input type="checkbox" class="cb-layer" layer-id="diem_truot_vien_dia_chat"/><span--}}
                                {{--class="layer-name">Điểm trượt đề tài viện địa chất</span></label>--}}
                                {{--</div>--}}


                                @if($isAdmin)
                                <div>
                                    <span class="home-layer-header">Tra cứu thông tin</span>
                                </div>
                                @endif

                                <div class="ly-trinh">
                                    <div style="padding: 5px 5px 5px 5px;">

                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        
                                        {{--Đường--}}
                                        <div class="row">
                                            <div class="col-md-4">
                                                Đường
                                            </div>

                                            <div class="col-md-8">
                                                <select id="select-route" class="form-control" id="sel1">
                                                    <option value="0">Chọn tuyến đường</option>
                                                    <option value="quoclo_40b">Quốc lộ 40B</option>
                                                    <option value="quoclo_14g">Quốc lộ 14G</option>
                                                    <option value="quoclo_14E">Quốc lộ 14E</option>
                                                    <option value="quoclo_14D">Quốc lộ 14D</option>
                                                    <option value="quoclo_14B">Quốc lộ 14B</option>
                                                    <option value="hcm">Hồ Chí Minh</option>
                                                    <option value="hcm_nhanh_tay">Hồ Chí Minh nhánh Tây</option>
                                                </select>

                                                <div style="margin-top: 5px;">
                                                    <span id="route-description"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif {{--------------------------------------------------------}}


                                        {{--Lý trình--}}
                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        <div class="row" style="padding-top: 5px;">
                                            <div class="col-md-4">
                                                Lý trình
                                            </div>

                                            <div class="col-md-8">
                                                <input id="input_distance" type="text" class="form-control"
                                                placeholder="Ví dụ: 20+100">
                                            </div>
                                        </div>
                                        @endif {{--------------------------------------------------------}}

                                        {{--Vĩ độ--}}
                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        <div class="row" style="padding-top: 5px;">
                                            <div class="col-md-4">
                                                Vĩ độ
                                            </div>

                                            <div class="col-md-8">
                                                <input id="input_lat" type="text" class="form-control"
                                                placeholder="Ví dụ: 18.181028915872226">
                                            </div>
                                        </div>

                                        {{--Kinh độ--}}
                                        <div class="row" style="padding-top: 5px;">
                                            <div class="col-md-4">
                                                Kinh độ
                                            </div>

                                            <div class="col-md-8">
                                                <input id="input_lon" type="text" class="form-control"
                                                placeholder="Ví dụ: 108.11627276066235">
                                            </div>
                                        </div>
                                        @endif {{--------------------------------------------------------}}


                                        {{--Đặc điểm--}}
                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        <div class="row" style="padding-top: 5px;">
                                            <div class="col-md-4">
                                                Đặc điểm
                                            </div>

                                            <div class="col-md-8">
                                                <select id="select-route-status" class="form-control" id="sel1">
                                                    <option value="truotlo">Trượt lở</option>
                                                    <option value="ngap">Ngập</option>
                                                    <option value="duongxau">Đường xấu</option>
                                                    <option value="suaduong">Sửa đường</option>
                                                    <option value="camduong">Cấm đường</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif {{--------------------------------------------------------}}

                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        <div style="margin-top: 15px; text-align: right; margin-bottom: 15px;">
                                            <button id="button-delete" type="button" class="btn btn-error" style="display: none;">Xóa</button>
                                            <button id="button-save" type="button" class="btn btn-primary" style="display: none;">Lưu</button>
                                            <button id="button-search" type="button" class="btn btn-primary">Hiển thị</button>
                                        </div>

                                        <input id="input-token-field" type="hidden" name="_token" value="{{ csrf_token() }}">

                                        @endif {{--------------------------------------------------------}}

                                        @if($isAdmin) {{--------------------------------------------------------}}
                                        <div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <img class="icon-canhbao" src="/images/canhbao/truotlo.png" alt=""> Trượt lở
                                                </div>

                                                <div class="col-md-5">
                                                    <img class="icon-canhbao" src="/images/canhbao/ngap.png" alt=""> Ngập
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <img class="icon-canhbao" src="/images/canhbao/duongxau.png" alt=""> Đường xấu
                                                </div>

                                                <div class="col-md-5">
                                                    <img class="icon-canhbao" src="/images/canhbao/suaduong.png" alt=""> Sửa đường
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-5">
                                                    <img class="icon-canhbao" src="/images/canhbao/camduong.png" alt=""> Cấm đường
                                                </div>

                                            </div>

                                        </div>

                                        {{--Hiển thị trong trường hợp với người dùng thông thường, chỉ hiển thị icon có cảnh báo--}}
                                        @else

                                            <div class="row" id="panel_icon_instruction_user">
                                                <div id="icon_truotlo_container" class="col-md-5" style="display: none;">
                                                    <img class="icon-canhbao" src="/images/canhbao/truotlo.png" alt=""> Trượt lở
                                                </div>

                                                <div id="icon_ngap_container" class="col-md-5" style="display: none;">
                                                    <img class="icon-canhbao" src="/images/canhbao/ngap.png" alt=""> Ngập
                                                </div>

                                                <div id="icon_duongxau_container" class="col-md-5" style="display: none;">
                                                    <img class="icon-canhbao" src="/images/canhbao/duongxau.png" alt=""> Đường xấu
                                                </div>

                                                <div id="icon_suaduong_container" class="col-md-5" style="display: none;">
                                                    <img class="icon-canhbao" src="/images/canhbao/suaduong.png" alt=""> Sửa đường
                                                </div>

                                                <div id="icon_camduong_container" class="col-md-5" style="display: none;">
                                                    <img class="icon-canhbao" src="/images/canhbao/camduong.png" alt=""> Cấm đường
                                                </div>

                                            </div>

                                        @endif {{--------------------------------------------------------}}


                                    </div>
                                </div>
                                {{--Nguy cơ trượt lở--}}
                                {{-- <div>
                                    <span class="home-layer-header">Nguy cơ trượt lở</span>
                                    <div style="margin-left:25px">
                                            <div><img src="/images/canhbao/ratthap.png" alt=""> Rất thấp </br></div>
                                            <div><img src="/images/canhbao/thap.png" alt=""> Thấp </br></div>
                                            <div> <img src="/images/canhbao/trungbinh.png" alt=""> Trung bình </br></div>
                                            <div> <img src="/images/canhbao/cao.png" alt=""> Cao </br></div>
                                            <div><img src="/images/canhbao/ratcao.png" alt=""> Rất cao </br></div>
                                    </div>
                                </div> --}}
                                
                                {{-- <div>
                                        <p style="font-weight:bold;">Mức nguy cơ trượt lở</p>
                                        <img src="images/ratthap.png" alt="" style="float: left; padding-left:10px;"> <p style="margin-left:40px;">1 - Rất thấp</p>
                                        <img src="images/thap.png" alt="" style="float: left; padding-left:10px;"><p style="margin-left:40px;">2 - Thấp</p>
                                        <img src="images/trungbinh.png" alt="" style="float: left; padding-left:10px;"><p style="margin-left:40px;">3 - Trung bình</p>
                                        <img src="images/cao.png" alt="" style="float: left; padding-left:10px;"><p style="margin-left:40px;">4 - Cao</p>
                                        <img src="images/ratcao.png" alt="" style="float: left;padding-left:10px;"><p style="margin-left:40px;">5 - Rất cao</p>
            
                                </div> --}}
                                <div>
                                    <span class="home-layer-header">Điểm trượt lở</span>
                                </div>

                                <div class="checkbox">

                                    <label><input type="checkbox" class="cb-layer" layer-id="hcm"/><span
                                                class="layer-name" style="margin-right: 10px;">Hồ Chí Minh</span></label>

                                    <label><input type="checkbox" class="cb-layer" layer-id="tsd"/><span
                                                class="layer-name" style="margin-right: 10px;">Trường Sơn Đông</span></label>

                                    <br/>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_14b"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 14B</span></label>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_14d"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 14D</span></label>

                                    <br/>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_14e"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 14E </span></label>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_14g"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 14G </span></label>

                                    <br/>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_24c"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 24C </span></label>

                                    <label><input type="checkbox" class="cb-layer" layer-id="ql_40B"/><span
                                                class="layer-name" style="margin-right: 10px;">Quốc lộ 40B </span></label>


                                    <br/>
                                    <label><input type="checkbox" class="cb-layer" layer-id="duong_tinh"/><span
                                                class="layer-name" style="margin-right: 10px;">Tỉnh lộ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

                                    <label><input type="checkbox" class="cb-layer" layer-id="all_routes"/><span
                                                class="layer-name" style="margin-right: 10px;">Tất cả</span></label>

                                </div>

                                <div>
                                    <span class="home-layer-header"></span>
                                </div>

                                <div class="checkbox">
                                    <label><input type="checkbox" class="cb-layer" layer-id="vi_tri_tram_wwo"/><span
                                                class="layer-name" style="font-weight: bold">Vị trí trạm World Weather Online</span></label>
                                </div>



                                <div>
                                    <span class="home-layer-header"></span>
                                </div>

                                <div class="checkbox">
                                    <label><input type="checkbox" class="cb-layer" layer-id="phan_vung_khi_hau"/>
                                        <span class="layer-name" style="font-weight: bold">Phân vùng khí hậu</span> <br/>
                                        <span class="layer-name">Nguồn: <i>Trung tâm Khí tượng Thủy văn Quảng Nam</i></span>
                                    </label>
                                </div>

                                {{--Dia chat--}}
                                @if($isAdmin)
                                    <div>
                                        <span class="home-layer-header"></span>
                                    </div>

                                    <div class="checkbox">
                                        <label><input type="checkbox" class="cb-layer" layer-id="dia_chat"/>
                                            <span class="layer-name" style="font-weight: bold">Địa chất</span> <br/>
                                        </label>
                                    </div>
                                @endif


                            </div>
                        </div>

                    </div>


                    {{--<div class="row md-mt-30 no-padding">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<ul class="bxslider">--}}
                                {{--<li><img src="images/slide/2.jpg" /></li>--}}
                                {{--<li><img src="images/slide/1.jpg" /></li>--}}
                                {{--<li><img src="images/slide/3.jpg" /></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="row md-mt-30 hidden">
                        <span class="section-title">Lượng mưa</span>
                        <div class="col-md-12">
                            <div id="precipmm-graph-panel" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>

                    {{--<div class="row md-mt30">--}}
                        {{--<span class="section-title">Nhiệt độ</span>--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div id="tempC-graph-panel" style="height: 400px; width: 100%;"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="row md-mt-30 no-padding hidden">
                        {{--<div class="col-md-12 statistics-table">--}}
                        {{--<div class="row header">--}}
                        {{--<div class="col-md-2">Ngày</div>--}}
                        {{--<div class="col-md-2">Giờ</div>--}}
                        {{--<div class="col-md-2">Nhiệt độ (°C)</div>--}}
                        {{--<div class="col-md-2">Thời tiết</div>--}}
                        {{--<div class="col-md-2">Lượng mưa (mm)</div>--}}
                        {{--<div class="col-md-2">Áp suất (atm)</div>--}}
                        {{--</div>--}}

                        {{--<div id="block-search-content">--}}

                        {{--</div>--}}
                        {{--</div>--}}

                        <span class="section-title">Thống kê</span>

                        <table id="tbl-data" class="table table-striped table-bordered tbl-data">
                            <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ</th>
                                <th>Nhiệt độ (°C)</th>
                                <th>Thời tiết</th>
                                <th>Lượng mưa (mm)</th>
                                <th>Độ ẩm</th>
                                <th>Áp suất</th>
                            </tr>
                            </thead>

                            <tbody id="tbl-body">

                            </tbody>
                        </table>

                    </div>

                    <div class="row md-mt-30 no-padding">
                    </div>

                </div>

            </div>
        </div>



        {{--Cảnh báo mức độ trượt lở--}}
        
        <div id="warningPopupModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">CHÚ Ý</h4>
                    </div>
                    <div class="modal-body">
                        <div id="popup-warning-content"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    </div>
                </div>

            </div>
        </div>


          
                    
        
            {{--Cảnh báo màu sắc mức độ trượt ở 7 tỉnh--}}
       
            {{-- <div id="warningMucDoTruotLoPopupModal" class="modal fade warningMucDoTruotLoPopupModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                {{-- <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Dự báo nguy cơ trượt lở (Bản thử nghiệm)</h4>
                    </div>
                    <div class="modal-body">

                        <div>

                            <div class="table-responsive tbl-canhbao-muc-do-container">
                                <table id="tbl-data-pessl" class="table table-bordered tbl-data tbl-canhbao-temp">
                                    <thead>
                                    <tr id="tbl-data-row-head">
                                        <th>Trạm</th>
                                        <th>Huyện</th>
                                        <th>{{date('d-m-Y', strtotime("now"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+1 day"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+2 day"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+3 day"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+4 day"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+5 day"))}}</th>
                                        <th>{{date('d-m-Y', strtotime("+6 day"))}}</th>

                                    </tr>
                                    </thead>

                                    <tbody id="tbl-body">
                                    @foreach($arrCanhBao as $canhBaoItem)
                                        <tr>

                                            {{--Name--}}
                                            {{-- <td>
                                                <b>{{$canhBaoItem["station_name"]}}</b>
                                            </td> --}}

                                            {{--Huyện--}}
                                            {{-- <td>
                                                <b>{{$canhBaoItem["district_name"]}}</b>
                                            </td> --}}

                                            {{--Day 1--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_01"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 2--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_02"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 3--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_03"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 4--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_04"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 5--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_05"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 6--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_06"]}}">&nbsp</div>
                                            </td> --}}

                                            {{--Day 7--}}
                                            {{-- <td>
                                                <div class="container-color" style="background-color: {{$canhBaoItem["color_day_07"]}}">&nbsp</div>
                                            </td> --}}
{{-- 
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div> --}}

{{-- 
                            <div class="container-explain-color" style="">
                                <div>
                                    <div style="min-height: 30px; line-height: 30px;">
                                        <span style="display: inline-block; width: 50px; height: 30px; border: solid 1px; background-color: #FFFFFF;"></span>
                                        <span style="position: relative; top: -10px; font-weight: bold; margin-right: 15px;">Không có nguy cơ</span>

                                        <span style="display: inline-block; width: 50px; height: 30px; border: solid 1px; background-color: #22b14c;"></span>
                                        <span style="position: relative; top: -10px; font-weight: bold; margin-right: 15px;">Thấp</span>

                                        <span style="display: inline-block; width: 50px; height: 30px; border: solid 1px; background-color: #FFFF00;"></span>
                                        <span style="position: relative; top: -10px; font-weight: bold; margin-right: 15px;">Trung bình</span>

                                        <span style="display: inline-block; width: 50px; height: 30px; border: solid 1px; background-color: #FF0000;"></span>
                                        <span style="position: relative; top: -10px; font-weight: bold; margin-right: 15px;">Cao</span>

                                        <span style="display: inline-block; width: 50px; height: 30px; border: solid 1px; background-color: #800080;"></span>
                                        <span style="position: relative; top: -10px; font-weight: bold; margin-right: 15px;">Rất cao</span>

                                    </div>

                                </div>
                            </div> --}}

                        {{-- </div> --}}


                    {{-- </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    </div> --}}
                {{-- </div> --}}

            {{-- </div>
        </div> --}} 



        {{--<div id="row-search-template" class="hidden">--}}
            {{--<div class="row" style="background: #FFFFFF">--}}
                {{--<div class="col-md-2"><span class="date"></span></div>--}}
                {{--<div class="col-md-2"><span class="time"></span></div>--}}
                {{--<div class="col-md-2"><span class="tempC"></span></div>--}}
                {{--<div class="col-md-2"><img class="weather-icon" src="" alt="" width="30px" height="30px" /></div>--}}
                {{--<div class="col-md-2"><span class="precipMM"></span></div>--}}
                {{--<div class="col-md-2"><span class="pressure"></span></div>--}}
            {{--</div>--}}
        {{--</div>--}}
@endsection

@section('js-define')
    <script src="{{ URL::asset('libs/jqplot/jquery.jqplot.min.js') }}"></script>
    <script src="{{ URL::asset('libs/jqplot/plugins/jqplot.canvasTextRenderer.min.js') }}"></script>
    <script src="{{ URL::asset('libs/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js') }}"></script>
    <script src="{{ URL::asset('libs/jqplot/plugins/jqplot.dateAxisRenderer.min.js') }}"></script>


    <!-- Datatables -->
    <script src="{{ URL::asset('libs/datatable.net/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>

    <script src="{{ URL::asset('libs/bxslider/jquery.bxslider.min.js') }}"></script>

    <script src="{{ URL::asset('libs/moment/moment.min.js') }}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANCLP-LOwPUPr0HpeBGWxl4ZfoRXVgdzY"></script>
    <script src="{{ URL::asset('libs/map-v3-utility-library/markerwithlabel/markerwithlabel.js') }}"></script>
    <script src="{{ URL::asset('js/diemtruot1.js') }}"></script>
    <script src="{{ URL::asset('js/home.js') }}"></script>

    <script src="{{ URL::asset('js/admin/v3_epoly.js') }}"></script>
    <script src="{{ URL::asset('js/admin/ly_trinh_data.js') }}"></script>
    <script src="{{ URL::asset('js/admin/home_ly_trinh.js') }}"></script>
    <script src="{{ URL::asset('js/admin/home_route.js') }}"></script>
<script src="{{ URL::asset('js/admin/home_canhbao.js') }}"></script>
    <script>
        function setupMaps() {
          var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 15.5193887, lng: 107.9616405},
            zoom: 9
              });
        @foreach($doannguyco as $item)
    
        var polylines = [
              {lat: {{$item->lat_start}}, lng: {{$item->lng_start}}}, //start
              {lat: {{$item->lat_finish}}, lng: {{$item->lng_finish}}}  //finish
            ];
        var flightPath = new google.maps.Polyline({
              path: polylines,
              geodesic: true,
              @if($item->doanNguyco->warning==1)
              strokeColor: '#E1E1E1',
              @elseif($item->doanNguyco->warning==2)
              strokeColor: '#A1A1F7',
              @elseif($item->doanNguyco->warning==3)
              strokeColor: '#F6F5A2',
              @else
              strokeColor: '#FF0000',
              @endif
              strokeOpacity: 1.0,
              strokeWeight: 4,
              map: map
            });
       @endforeach
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANCLP-LOwPUPr0HpeBGWxl4ZfoRXVgdzY&callback=setupMaps"
    async defer></script>
<script>
        $('#exampleModal').modal('show');
</script>

@endsection
