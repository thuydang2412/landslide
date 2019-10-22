@extends("site.baseMobile")

@section('title', 'Thời tiết')

@section('css-define')
    <!-- Datatables -->
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('libs/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('content')

    @if(isset($stationId))
        <input type="hidden" id="input-station-id" value="{{$stationId}}"/>
    @else
        <input type="hidden" id="input-station-id" value=""/>
    @endif

<div style="background-color: #FFF; padding: 20px; min-height: 100vh">


    <div class="row" style="width: 90%; margin: auto;">

        {{--<h3>Thông tin dự báo thời tiết của Đài khí tượng thủy văn tỉnh Quảng Nam</h3>--}}
        {{--<div style="height: 300px;"></div>--}}

        <h4 style="font-weight: bold">Thông tin dự báo thời tiết của World Weather Online</h4><br/>
        <div class="prec-search-panel col-md-3 select-layer-panel" style="padding-top: 30px; height: 100vh">
            <span class="title">Chọn trạm</span>
            <select id="input-station" class='form-control' data-placeholder="-- Chọn trạm --"><option></option></select>

            <span class="title md-mt-5">Từ ngày</span>
            <input type="text" class="form-control" id="input-start-date"></p>
            <span class="title">Đến ngày</span>
            <input type="text" class="form-control" id="input-end-date"></p>

            <hr/>
            <div>
                <span class="title">Chọn dữ liệu hiển thị</span>
                <label class="check-box-column-label"><input id="check-box-precip" type="checkbox" value="0" class="check-box-column-type" checked> Lượng mưa</label>
                <label class="check-box-column-label"><input id="check-box-temp" type="checkbox" value="1" class="check-box-column-type"> Nhiệt độ</label>
                <label class="check-box-column-label"><input id="check-box-humi" type="checkbox" value="2" class="check-box-column-type"> Độ ẩm</label>
                {{--<label class="check-box-column-label"><input type="checkbox" value="3" class="check-box-column-type"> Áp suất</label>--}}
                <label class="check-box-column-label"><input id="check-box-wind" type="checkbox" value="4" class="check-box-column-type"> Tốc độ gió</label>
            </div>

            <hr/>
            <div>
                <span class="title">Chọn loại đồ thị</span>
                <label class="check-box-column-label"><input type="checkbox" value="0" class="check-box-graph-type"> Lượng mưa</label>
                <label class="check-box-column-label"><input type="checkbox" value="1" class="check-box-graph-type"> Nhiệt độ</label>
                <label class="check-box-column-label"><input type="checkbox" value="2" class="check-box-graph-type"> Độ ẩm</label>
                {{--<label class="check-box-column-label"><input type="checkbox" value="3" class="check-box-graph-type"> Áp suất</label>--}}
                <label class="check-box-column-label"><input type="checkbox" value="4" class="check-box-graph-type"> Tốc độ gió</label>
            </div>

            <button type="button" id="btn-search" class="btn btn-primary btn-block md-mt-15">Xem dữ liệu</button>
        </div>

        <div class="col-md-9" style="margin-top: 30px;">
            {{--<div class="col-md-12 statistics-table">--}}
                {{--<div class="row header">--}}
                    {{--<div class="col-md-2">Xã</div>--}}
                    {{--<div class="col-md-2">Ngày</div>--}}
                    {{--<div class="col-md-2">Giờ</div>--}}
                    {{--<div class="col-md-2">Độ ẩm</div>--}}
                    {{--<div class="col-md-2">Lượng mưa</div>--}}
                {{--</div>--}}

                {{--<div id="block-search-content">--}}

                {{--</div>--}}
            {{--</div>--}}

            <table id="tbl-data" class="table table-striped table-bordered tbl-data">
                <thead>
                <tr id="tbl-data-row-head">
                    <th>Ngày</th>
                    <th>Giờ</th>
                    <th>Lượng mưa (mm)</th>
                </tr>
                </thead>

                <tbody id="tbl-body">

                </tbody>
            </table>

            {{--Graph Precipmm--}}
            <div id="precipmm-graph-panel-container" class="collapse md-mt-20">
                <span class="graph-title">Lượng mưa cộng dồn (mm)</span>
                <div id="precipmm-graph-panel" style="height: 400px; width: 100%;"></div>
            </div>


            {{--Graph Temp--}}
            <div id="temp-graph-panel-container" class="collapse md-mt-20">
                <span class="graph-title">Nhiệt độ (°C)</span>
                <div id="temp-graph-panel" style="height: 400px; width: 100%;"></div>
            </div>


            {{--Graph Humidity--}}
            <div id="humidity-graph-panel-container" class="collapse md-mt-20">
                <span class="graph-title">Độ ẩm (%)</span>
                <div id="humidity-graph-panel" style="height: 400px; width: 100%;"></div>
            </div>

            {{--Graph Pressure--}}
            <div id="pressure-graph-panel-container" class="collapse md-mt-20">
                <span class="graph-title">Áp suất</span>
                <div id="pressure-graph-panel" style="height: 400px; width: 100%;"></div>
            </div>

            {{--Wind speed--}}
            <div id="wind-speed-graph-panel-container" class="collapse md-mt-20">
                <span class="graph-title">Tốc độ gió (km/h)</span>
                <div id="wind-speed-graph-panel" style="height: 400px; width: 100%;"></div>
            </div>

        </div>
    </div>


    {{--<div id="row-search-template">--}}
        {{--<div class="row" style="background: #FFFFFF">--}}
            {{--<div class="col-md-2"><span class="district"></span></div>--}}
            {{--<div class="col-md-2"><span class="date"></span></div>--}}
            {{--<div class="col-md-2"><span class="time"></span></div>--}}
            {{--<div class="col-md-2"><span class="humidity"></span></div>--}}
            {{--<div class="col-md-2"><span class="precipMM"></span></div>--}}
        {{--</div>--}}
    {{--</div>--}}

</div>


@section('js-define')


<!-- Jqplot Graph -->
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

<script src="{{ URL::asset('libs/datatable.net/plugins/date-eu.js') }}"></script>

<script src="{{ URL::asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('libs/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('libs/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('js/weather.js') }}"></script>
@endsection

@endsection