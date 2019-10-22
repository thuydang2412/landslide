@extends('base')

@section('header')

    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('libs/datatable.net/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">


    <title>News</title>
@endsection

@section('body')


<h2 class="page-header">{{ ucfirst('news') }}</h2>

<div class="panel panel-default">


    <div class="panel-body">
        <div class="">
            <table class="table table-striped" id="thegrid">
              <thead>
                <tr>
                                        <th>Id</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th style="width:50px"></th>
                    <th style="width:50px"></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
        </div>
        <a href="{{url('admin/news/create')}}" class="btn btn-primary" role="button">Add news</a>

        <input id="input-token-field" type="hidden" name="_token" value="{{ csrf_token() }}">

    </div>
</div>




@endsection



@section('footer')

    <!-- Datatables -->
    <script src="{{ URL::asset('libs/datatable.net/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('libs/datatable.net/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>

    <script type="text/javascript">
        var theGrid = null;
        $(document).ready(function(){
            theGrid = $('#thegrid').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": true,
                "responsive": true,
                "ajax": "{{url('admin/news/grid')}}",
                "columnDefs": [
                    {
                        {{--"render": function ( data, type, row ) {--}}
                            {{--return '<a href="{{ url('/admin/news') }}/'+row[0]+'">'+data+'</a>';--}}
                        {{--},--}}
                        {{--"targets": 5--}}
                    },
                    {
                        "render": function ( data, type, row ) {
                            return '<a href="{{ url('/admin/news') }}/'+row[0]+'/edit" class="btn btn-default">Update</a>';
                        },
                        "targets": 4                 },
                    {
                        "render": function ( data, type, row ) {
                            return '<a href="#" onclick="return doDelete('+row[0]+')" class="btn btn-danger">Delete</a>';
                        },
                        "targets": 4+1
                    },
                ]
            });
        });
        function doDelete(id) {
            if(confirm('You really want to delete this record?')) {
               $.ajax({ url: '{{ url('/admin/news') }}/' + id, type: 'DELETE', data:{_token : $("#input-token-field").val(),}}).success(function() {
                theGrid.ajax.reload();
               });
            }
            return false;
        }
    </script>
@endsection