
@if(isset($obj['hien_nay']))
    <b>Hiện nay</b> <br/>
    @foreach($obj['hien_nay'] as $canhBaoSt)
        {{$canhBaoSt}} <br/>
    @endforeach

    <hr/>
@endif



@if(isset($obj['canh_bao']))
    <b>Cảnh báo</b> <br/>
    @foreach($obj['canh_bao'] as $canhBaoSt)
        {{$canhBaoSt}} <br/>
    @endforeach

@endif