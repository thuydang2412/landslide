@extends('base')

@section('header')
    <title>@yield('title')</title>
    {{--Define common css site here--}}
    <link rel="stylesheet" href="{{ URL::asset('libs/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/site.css') }}">
    @yield('css-define')
@endsection

@section('body')


    <div>
        

            <div class="home-container" style="background-color: #00897B; height: 75px">
                {{-- <span class="general-title">Website thử nghiệm đề tài độc lập ĐTĐLCN. 23/17</span> --}}
                <div id="logo" class="col-lg-12 col-md-12 col-sm-12" style="padding-left:0px; padding-right:0px;">
                        <a href="{{ url('/trang-chu') }}"><img class="img-fluid" src="/images/landslide.png"></a>
                        
                        
                        <a href="{{ url('/trang-chu') }}" <h1 id = "tieude" style="line-height: 75px;margin-left: 10px;letter-spacing: 1.8px; font-weight: bold; color:white; font-size:20px; font-family:Bookman, Tahoma, Verdana;">CỔNG THÔNG TIN TRƯỢT LỞ TỈNH QUẢNG NAM</h1></a>
               {{-- <br><span>Website thử nghiệm đề tài độc lập ĐTĐLCN. 23/17</span> --}}
                        </div>
            </div>

            <div>
                <nav id="nav" class="ry">
                        <ul id="main-menu">
                                <li>
                                    <a href="{{ url('/trang-chu') }}"><i class="fa fa-home"></i> <span class="menu-title">Trang chủ</span></a>
                                </li>
                
                                {{--<li>--}}
                                    {{--<a href="{{ url('/map') }}"><i class="fa fa-map"></i> Bản đồ</a>--}}
                                {{--</li>--}}
                
                                <li>
                                    <a href="{{ url('/thoi-tiet') }}"><i class="fa fa-bar-chart"></i> <span class="menu-title">Thời tiết</span></a>
                                </li>
                
                                {{--<li>--}}
                                    {{--<a href="{{ url('/tintuc/hientrang') }}"><i class="fa fa-map"></i> Hiện trạng trượt lở</a>--}}
                                {{--</li>--}}
                
                                {{--<li>--}}
                                    {{--<a href="{{ url('/tintuc/dauhieu') }}"><i class="fa fa-bar-chart"></i> Dấu hiệu và ứng phó</a>--}}
                                {{--</li>--}}
                
                                <li>
                                <a href="{{ url('/tintuc') }}"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <span class="menu-title">Thông tin trượt lở</span></a>
                                </li>
                
                                @if(session('user_id'))
                                <li>
                                    <a href="{{ url('/admin') }}"><i class="fa fa-user-o"></i> <span class="menu-title">Admin</span></a>
                                </li>
                                @endif
                
                                <li>
                                    <a href="{{ url('/admin') }}"><i class="fa fa-comment"></i> <span class="menu-title">Quản trị</span></a>
                                </li>
                
                                @if(session('user_id'))
                                    <li>
                                        <a href="{{ url('/admin/logout') }}"><i class="fa fa-user-o"></i> <span class="menu-title">Đăng xuất</span></a>
                                    </li>
                                @endif
                
                
                                {{--<li>--}}
                                {{--<a href="#"><i class="fa fa-user"></i> About <i class="fa fa-caret-down"></i></a>--}}
                                {{--<ul class="submenu">--}}
                                {{--<li><a href="#0">Meet the Team</a></li>--}}
                                {{--<li><a href="#0">Careers</a></li>--}}
                                {{--<li>--}}
                                {{--<a href="#0">More Items <i class="fa fa-caret-right"></i></a>--}}
                                {{--<ul class="submenu">--}}
                                {{--<li><a href="#0">A Sub-Item</a></li>--}}
                                {{--<li>--}}
                                {{--<a href="#0">A Sub-Item</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<a href="#0">A Sub-Item</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                
                            </ul>
                        </nav>
                
            </div>

           
    </div>
{{-- 
    <div class="nav-fake-div"></div> --}}

    <div>
        @yield('content')
    </div>

@endsection

@section('footer')
    {{--Define common js here--}}
    <script src="{{ URL::asset('libs/gasparesganga-jquery-loading-overlay/src/loadingoverlay.min.js') }}"></script>
    <script src="{{ URL::asset('libs/gasparesganga-jquery-loading-overlay/extras/loadingoverlay_progress/loadingoverlay_progress.min.js') }}"></script>
    <script src="{{ URL::asset('libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('js/common.js') }}"></script>
    @yield('js-define')
@endsection