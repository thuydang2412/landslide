@extends("site.base")

@section('title', 'Thiết lập')

@section('css-define')
    <link rel="stylesheet" href="{{ URL::asset('libs/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('content')


<div class="md-mt-15" style="margin: 20px 60px 20px 60px;">

    <div style="margin-bottom: 20px;">
        <span style="font-weight: bold; font-size: 12pt;">Tổng lượng truy cập: {{$totalVisited}}</span>
        
        <div id="embed-api-auth-container"></div>
        <div id="chart-container"></div>
        <div id="view-selector-container"></div>
       <script>
            (function(w,d,s,g,js,fs){
              g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
              js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
              js.src='https://apis.google.com/js/platform.js';
              fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
            }(window,document,'script'));
        </script>
        
    </div>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#permission">Phân quyền</a></li>
        <li><a data-toggle="tab" href="#color-setting">Màu sắc cảnh báo</a></li>
    </ul>

    <div class="tab-content">
        <div id="permission" class="tab-pane fade in active">
            @include("admin/user-setting")
        </div>
        <div id="color-setting" class="tab-pane fade" style="padding: 20px;">
            @include("admin/color-setting")
        </div>
    </div>
</div>


{{ csrf_field() }}


@section('js-define')
<script src="{{ URL::asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('libs/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('libs/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('libs/jscolor/jscolor.min.js') }}"></script>
<script src="{{ URL::asset('js/setting.js') }}"></script>
@endsection

@endsection