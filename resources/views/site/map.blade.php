@extends("site.base")

@section('title', 'Bản Đồ')

@section('css-define')
    <link rel="stylesheet" href="{{ URL::asset('libs/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('content')

    <div style="background-color: #F0F0F0; padding: 20px;">
        <div class="row" style="height: 100vh; margin: 0px;">
            <div class="col-md-3 select-layer-panel">
                <div id="left_select_panel">
                    <span class="section-title md-mt-10"><i class="fa fa-map" aria-hidden="true"></i> Chọn lớp hiển thị</span>
                </div>

                <div id="check_box_template" style="display: none;">
                    <div class="checkbox">
                        <label><input type="checkbox" class="cb-layer" layer-id=""/><span
                                    class="layer-name"></span></label>
                    </div>
                </div>

            </div>

            <div class="col-md-9">
                <div id="map" style="width: 100%; height: 80vh; border: 1px solid #2D3F51"></div>
            </div>
        </div>
    </div>
@endsection


@section('js-define')
    <script src="{{ URL::asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('libs/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('js/map.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANCLP-LOwPUPr0HpeBGWxl4ZfoRXVgdzY&callback=initMap"
            async defer></script>
@endsection

