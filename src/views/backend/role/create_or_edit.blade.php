@extends('nadminpanel::backend.admin.common.main')

@section('title')
    @if(!isset($role))
        Create new Role
    @else
        Edit role
    @endif
@endsection

@section('extra-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/responsive.bootstrap.min.css') }}">
    <style>
        .checkbox-inline.no_indent,
        .checkbox-inline.no_indent+.checkbox-inline.no_indent {
            margin-left: 0;
            margin-right: 10px;
        }
        .checkbox-inline.no_indent:last-child {
            margin-right: 0;
        }
    </style>
@endsection

@section('box')
    <div class="box" id="post">

        <div class="box-header with-border">
            <h3 class="box-title">
                @if(!isset($role))
                    Create
                @else
                    Edit
                @endif
                    Role
            </h3>
            <a class="btn btn-success pull-right" href="{{ url('role') }}">See All</a>
        </div><!-- /.box-header -->

    @if(!isset($role))
        <form id="role-form" method="POST" action="{{ route('role.store') }}" class="form-horizontal" role="form">
    @else
        <form id="role-form" method="POST" action="{{ route('role.update', $role->id) }}" class="form-horizontal" role="form">
            {{ method_field('PUT') }}
    @endif

            {!! csrf_field() !!}

            <div class="box-body">

                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Name<span class="text-red">&nbsp;*</span></label>
                    <div class="col-md-6">
                        <input type="text" name="name" id="name" placeholder="Enter Name (lowercase, nospace)" value="{{ (old('name') != null) ? old('name') : (isset($role->name) ? $role->name : '') }}" class="form-control" />
                        @if($errors->has('name'))
                            <span class="text-red">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="display_name" class="col-md-3 control-label">Display Name<span class="text-red">&nbsp;*</span></label>
                    <div class="col-md-6">
                        <input type="text" name="display_name" id="display_name" placeholder="Enter Display Name" value="{{ (old('display_name') != null) ? old('display_name') : (isset($role->display_name) ? $role->display_name : '') }}" class="form-control" />
                        @if($errors->has('display_name'))
                            <span class="text-red">{{ $errors->first('display_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Permissions</label>
                    <div class="col-md-8">
                        @if(count($permission_labels) > 0)
                            @foreach($permission_labels as $per_label_chunk_index => $per_label_chunk)
                                @foreach($per_label_chunk as $per_label)
                                    {!! ($per_label_chunk_index != 0) ? '<div style="padding-top: 12px;" class="col-md-6">' : '<div class="col-md-6">' !!}
                                        <strong>{{ ucfirst($per_label->name) }}</strong><br>
                                        @foreach(config('nadminpanel.permission_titles') as $title)
                                            <div class="col-md-6">
                                                <label class="checkbox-inline no_indent"><input type="checkbox" name="permissions[]" value="{{ \NAdminPanel\AdminPanel\Models\Permission::getId($title.' '.$per_label->name) }}" {{ (isset($role)) ? (($role->hasPermissionTo($title.' '.$per_label->name)) ? 'checked' : '') : '' }}><span style="margin-left: -3px; margin-right: 10px;">{{ $title.' '.$per_label->name }}</span></label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <a href="{{ route('permission.create') }}">Create Permissions</a> for assign to Role.
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        @if(!isset($role))
                            <button type="submit" class="btn btn-success">Save</button>
                        @else
                            <button type="submit" class="btn btn-primary">Update</button>
                        @endif
                        <a href="{{ route('role.index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </form>
    </div>

@endsection

@section('extra-script')
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net-bs/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/plugins/datatables.net-bs/js/dataTables.bootstrap.js') }}"></script>
@endsection