@extends("site.base")

@section('title', 'Lượng mưa')

@section('css-define')
    <link rel="stylesheet" href="{{ URL::asset('libs/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('content')

    <div class="md-mt-50">
        <h1 class="text-center">Login</h1>
    </div>

        <div class="md-mt-20 col-md-6 col-md-offset-3">

            <form action="/admin/login" method="post">

                {{ csrf_field() }}
                <label for="username" class="control-label">User name</label>
                <input type="text" name="username" class="form-control" />

                <label for="password" class="control-label">Password</label>
                <input type="password" name="password" class="form-control" />

                <div class="md-mt-10">
                    <button type="submit" class="col-md-12 btn btn-primary">Login</button>
                </div>
            </form>
        </div>


@section('js-define')
    @if(!empty($error))
        <script type="text/javascript">
            $(document).ready(function() {
                showErrorMessage('{{ $error }}');
            });
        </script>
    @endif
@endsection

@endsection