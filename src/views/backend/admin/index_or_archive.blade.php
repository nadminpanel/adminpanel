@extends('nadminpanel::backend.admin.common.main')

@section('title')
    @if( active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user') == 'active' )
        {{ (active_check(config('nadminpanel.admin_backend_prefix').'/admin', true) == 'active') ? 'Admin' : 'User' }} Index
    @elseif( active_check(config('nadminpanel.admin_backend_prefix').'/admin/archive') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user/archive') == 'active' )
        {{ (active_check(config('nadminpanel.admin_backend_prefix').'/admin', true) == 'active') ? 'Admin' : 'User' }} Archive
    @endif
@endsection

@section('extra-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/responsive.bootstrap.min.css') }}">
@endsection

@section('box')
    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">{{ ucfirst($role) }}{{ (active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user') == 'active') ? ' ' : ' Archive ' }}List</h3>
            {!! (active_check(config('nadminpanel.admin_backend_prefix').'/user') == 'active') ? '<a style="margin-right:5px" class="btn btn-success pull-right " href="' . route('user.create') .'">Create User</a>' : '' !!}
        </div><!-- /.box-header -->

        <div class="box-body">
            <table class="table table-bordered dt-responsive nowrap" id="data_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div><!-- /.box-body -->

        <div class="box-footer clearfix"></div>

    </div>
@endsection

@section('extra-script')
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net-bs/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net-bs/js/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        @if(active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user') == 'active')
            var mainUrl = "{!! (active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active') ? route('admin.index') : route('user.index') !!}";
        @elseif(active_check(config('nadminpanel.admin_backend_prefix').'/admin/archive') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user/archive') == 'active')
            var mainUrl = "{!! (active_check(config('nadminpanel.admin_backend_prefix').'/admin/archive') == 'active') ? route('admin.archive') : route('user.archive')!!}";
        @endif

        
            var table=$('#data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url : mainUrl
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'DT_Row_Index', orderable: false, searchable: false },
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'action', name : 'action', orderable: false, searchable: false}
                ],
                order: [[ 1, "asc" ]]
                });

            $('#state').on('change', function(e) {
                table.draw();
                e.preventDefault();
            });

            var successHandler=function($msg){
                toastr.success($msg,'Status',{timeOut: 2000});
                table.ajax.reload(null, false);
            };

            var errorHandler=function($msg){
                toastr.error($msg,'Status', {timeOut: 2000});
            };

            @if(active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user') == 'active')

                $('#data_table').on('click','.delete',function (e) {
                e.preventDefault();
                $.ajax({
                    url: mainUrl+"/"+$(this).data('id'),
                    type: 'DELETE',
                    success: function(result) {
                        successHandler('Successfully Archived Admin');
                    },
                    error: function (err) {
                        errorHandler('Error Admin. Please reload the page.');
                    }
                });
            });

            @elseif(active_check(config('nadminpanel.admin_backend_prefix').'/admin/archive') == 'active' || active_check(config('nadminpanel.admin_backend_prefix').'/user/archive') == 'active')

                $('#data_table').on('click','.unarchive',function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: mainUrl+"/"+$(this).data('id'),
                        type: 'PATCH',
                        success: function(result) {
                            successHandler('Successfully Unarchived Library');
                        },
                        error: function (err) {
                            errorHandler('Error Library. Try to reload the page or Wait Your connection');
                        }
                    });
                });

                $('#data_table').on('click','.delete',function (e) {
                e.preventDefault();
                $.ajax({
                    url: mainUrl+"/"+$(this).data('id'),
                    type: 'DELETE',
                    success: function(result) {
                        successHandler('Successfully deleted Admin');
                    },
                    error: function (err) {
                        errorHandler('Error Admin. Please reload the page.');
                    }
                });
            });

            @endif

        });
    </script>
@endsection