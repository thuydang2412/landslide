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