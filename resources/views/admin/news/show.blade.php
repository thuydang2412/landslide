@extends('base')

@section('body')



<h2 class="page-header">News</h2>

<div class="panel panel-default">
    <div class="panel-heading">
        View News    </div>

    <div class="panel-body">
                

        <form action="{{ url('/news') }}" method="POST" class="form-horizontal">


                
        <div class="form-group">
            <label for="id" class="col-sm-3 control-label">Id</label>
            <div class="col-sm-6">
                <input type="text" name="id" id="id" class="form-control" value="{{$model['id'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="cat_id" class="col-sm-3 control-label">Cat Id</label>
            <div class="col-sm-6">
                <input type="text" name="cat_id" id="cat_id" class="form-control" value="{{$model['cat_id'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="title" class="col-sm-3 control-label">Title</label>
            <div class="col-sm-6">
                <input type="text" name="title" id="title" class="form-control" value="{{$model['title'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">Description</label>
            <div class="col-sm-6">
                <input type="text" name="description" id="description" class="form-control" value="{{$model['description'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="thumbnail" class="col-sm-3 control-label">Thumbnail</label>
            <div class="col-sm-6">
                <input type="text" name="thumbnail" id="thumbnail" class="form-control" value="{{$model['thumbnail'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="content" class="col-sm-3 control-label">Content</label>
            <div class="col-sm-6">
                <input type="text" name="content" id="content" class="form-control" value="{{$model['content'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="sources" class="col-sm-3 control-label">Sources</label>
            <div class="col-sm-6">
                <input type="text" name="sources" id="sources" class="form-control" value="{{$model['sources'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="created_at" class="col-sm-3 control-label">Created At</label>
            <div class="col-sm-6">
                <input type="text" name="created_at" id="created_at" class="form-control" value="{{$model['created_at'] or ''}}" readonly="readonly">
            </div>
        </div>
        
                
        <div class="form-group">
            <label for="updated_at" class="col-sm-3 control-label">Updated At</label>
            <div class="col-sm-6">
                <input type="text" name="updated_at" id="updated_at" class="form-control" value="{{$model['updated_at'] or ''}}" readonly="readonly">
            </div>
        </div>
        
        
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <a class="btn btn-default" href="{{ url('/news') }}"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
            </div>
        </div>


        </form>

    </div>
</div>







@endsection