@extends('nadminpanel::admin.common.main')

@section('title')
    @if( active_check(config('nadminpanel.admin_backend_prefix').'/role') == 'active' )
        Role Index
    @elseif( active_check(config('nadminpanel.admin_backend_prefix').'/role/archive') == 'active' )
        Role Archive
    @endif
@endsection

@section('extra-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/responsive.bootstrap.min.css') }}">
@endsection

@section('box')
    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">Role{{ (active_check(config('nadminpanel.admin_backend_prefix').'/role') == 'active') ? ' ' : ' Archive ' }}List</h3>
            <a style="margin-right:5px" class="btn btn-success pull-right " href="{{ route('role.create') }}">Create Role</a>
        </div><!-- /.box-header -->

        <div class="box-body">
            <table class="table table-bordered dt-responsive nowrap" id="data_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Permissions</th>
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
        $(document).ready(function  (argument) {

        @if(active_check(config('nadminpanel.admin_backend_prefix').'/role') == 'active')
            var mainUrl="{!! route('role.index') !!}";
        @elseif(active_check(config('nadminpanel.admin_backend_prefix').'/role/archive') == 'active')
            var mainUrl = "{!! route('role.archive') !!}";
        @endif

            var table=$('#data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url : mainUrl
                },
                columns: [
                    { data: 'DT_Row_Index', name: 'DT_Row_Index', orderable: false, searchable: false },
                    { data: 'display_name', name: 'display_name' },
                    { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
                    { data: 'action', name : 'action', orderable: false, searchable: false }
                ],
                order: [[ 1, "asc" ]],
                drawCallback: function() {
                    $('[data-toggle="popover"]').popover();
                }
            });

            $('#state').on('change', function(e) {
                table.draw();
                e.preventDefault();
            });

            $('[data-toggle="popover"]').popover();

            var successHandler=function($msg){
                toastr.success($msg,'Status',{timeOut: 2000});
                table.ajax.reload(null, false);
            };

            var errorHandler=function($msg){
                toastr.error($msg,'Status', {timeOut: 2000});
            };

            @if(active_check(config('nadminpanel.admin_backend_prefix').'/role') == 'active')

                $('#data_table').on('click','.delete',function (e) {
                e.preventDefault();
                $.ajax({
                    url: mainUrl+"/"+$(this).data('id'),
                    type: 'DELETE',
                    success: function(result) {
                        successHandler('Successfully Archived Role');
                    },
                    error: function (err) {
                        errorHandler('Error Role. Please reload the page.');
                    }
                });
            });

            @elseif(active_check(config('nadminpanel.admin_backend_prefix').'/role/archive') == 'active')

                $('#data_table').on('click','.unarchive',function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: mainUrl+"/"+$(this).data('id'),
                        type: 'PATCH',
                        success: function(result) {
                            successHandler('Successfully Unarchived Role');
                        },
                        error: function (err) {
                            errorHandler('Error Role. Try to reload the page or Wait Your connection');
                        }
                    });
                });

                $('#data_table').on('click','.delete',function (e) {
                e.preventDefault();
                $.ajax({
                    url: mainUrl+"/"+$(this).data('id'),
                    type: 'DELETE',
                    success: function(result) {
                        successHandler('Successfully deleted Role');
                    },
                    error: function (err) {
                        errorHandler('Error Role. Please reload the page.');
                    }
                });
            });

            @endif

        });
    </script>
@endsection