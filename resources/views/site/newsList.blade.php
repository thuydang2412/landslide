@extends("site.base")

@section('title', 'Tin tức')

@section('content')

    <div class="home-container">
        <div class="row no-padding">
            <div class="col-md-8 col-md-offset-0">

                @foreach($listNews as $news)
                    <div class="news-block">
                        <div class="img-container">
                            <a href="{{$news->link}}" target="_blank">
                                <img src="{{$news->thumbnail}}" alt="" width="100% height: 100px">
                            </a>
                        </div>

                        <div class="main-content">
                            <div class="news-title">
                                <p>
                                    {{--<a href="{{url('/tintuc/'.$news->id)}}">{{$news->title}}</a>--}}
                                    <a href="{{$news->link}}" target="_blank">{{$news->title}}</a>
                                </p>
                            </div>

                            <div class="news-detail">
                                <p>
                                    {{$news->description}}
                                </p>
                            </div>

                            <div class="text-right">
                                Nguồn: <b>{{$news->sources}}</b>
                            </div>

                        </div>


                        <div style="clear: both;">

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

@section('js-define')

@endsection

