@extends('base')

@section('body')


    <h2 class="page-header">News</h2>

    <div class="panel panel-default">
        <div class="panel-heading">
            Add/Modify News
        </div>

        <div class="panel-body">

            <form action="{{ url('/admin/news'.( isset($model) ? "/" . $model->id : "")) }}" method="POST"
                  class="form-horizontal">
                {{ csrf_field() }}

                @if (isset($model))
                    <input type="hidden" name="_method" value="PATCH">
                @endif


                <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Id</label>
                    <div class="col-sm-6">
                        <input type="text" name="id" id="id" class="form-control" value="{{$model['id'] or ''}}"
                               readonly="readonly">
                    </div>
                </div>

                <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-6">
                        <input type="text" name="title" id="title" class="form-control"
                               value="{{$model['title'] or ''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-6">
                        <input type="text" name="description" id="description" class="form-control"
                               value="{{$model['description'] or ''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="thumbnail" class="col-sm-3 control-label">Thumbnail</label>
                    <div class="col-sm-6">
                        <input type="text" name="thumbnail" id="thumbnail" class="form-control"
                               value="{{$model['thumbnail'] or ''}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="content" class="col-sm-3 control-label">Link</label>
                    <div class="col-sm-6">
                        <input type="text" name="link" id="link" class="form-control"
                               value="{{$model['link'] or ''}}">
                    </div>
                </div>

                {{--<div class="form-group">--}}
                    {{--<label for="content" class="col-sm-3 control-label">Content</label>--}}
                    {{--<div class="col-sm-6">--}}
                        {{--<input type="text" name="content" id="content" class="form-control"--}}
                               {{--value="{{$model['content'] or ''}}">--}}
                    {{--</div>--}}
                {{--</div>--}}


                <div class="form-group">
                    <label for="sources" class="col-sm-3 control-label">Sources</label>
                    <div class="col-sm-6">
                        <input type="text" name="sources" id="sources" class="form-control"
                               value="{{$model['sources'] or ''}}">
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> Save
                        </button>
                        <a class="btn btn-default" href="{{ url('/admin/news') }}"><i
                                    class="glyphicon glyphicon-chevron-left"></i> Back</a>
                    </div>
                </div>
            </form>

        </div>
    </div>






@endsection