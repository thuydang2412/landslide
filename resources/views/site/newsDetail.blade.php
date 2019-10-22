@extends("site.base")

@section('title', 'Tin tức')

@section('content')

    <div style="background-color: #F0F0F0; padding: 20px; min-height: 100vh;">
        <div class="row no-padding">
            <div class="detail-news-block col-md-8 col-md-offset-2">
                @if(!empty($news))

                    <p class="news-title">
                        {{$news->title}}
                    </p>

                    <p>
                        {!! $news->content !!}
                    </p>

                    <div class="text-right">
                        Nguồn: <b>{{$news->sources}}</b>
                    </div>

                @endif
            </div>
        </div>
    </div>

@endsection

@section('js-define')

@endsection

