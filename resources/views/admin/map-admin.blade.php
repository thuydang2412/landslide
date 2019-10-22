@extends("base")

@section('header')
<title>Map Admin</title>
@endsection

@section('body')

    <div class="col-md-8">
        <div id="map_canvas" style="width: 100%; height: 500px;"></div>
    </div>

    <div class="col-md-4">
        <label for="">Nhập khoảng cách</label>
        <input id="input_distance" type="text" class="form-control">
        <button id="button_search" class="btn btn-primary">Tìm</button>

        <div>
            Latitude: <span id="lat_val"></span>
        </div>

        <div>
            Longitude: <span id="lgn_val"></span>
        </div>
    </div>



@endsection

@section('footer')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANCLP-LOwPUPr0HpeBGWxl4ZfoRXVgdzY"></script>
    <script src="{{ URL::asset('js/admin/v3_epoly.js') }}"></script>
    <script src="{{ URL::asset('js/admin/map-admin.js') }}"></script>
@endsection