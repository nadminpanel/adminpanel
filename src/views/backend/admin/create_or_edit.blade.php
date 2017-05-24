@extends('nadminpanel::backend.admin.common.main')

@section('title')
    @if(!isset($user))
        Create new user
    @else
        Edit user
    @endif
@endsection

@section('extra-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/plugins/datatables.net-bs/css/responsive.bootstrap.min.css') }}">
@endsection

@section('box')
    <div class="box" id="post">

        <div class="box-header with-border">
            <h3 class="box-title">
                @if(!isset($user))
                    Create
                @else
                    Edit
                @endif
                    User
            </h3>
            <a class="btn btn-success pull-right" href="{{ route('user.index') }}">See All</a>
        </div><!-- /.box-header -->

    @if(!isset($user))
        <form id="user-form" method="POST" action="{{ route('user.store') }}" class="form-horizontal" role="form" autocomplete="off" >
    @else
        <form id="user-form" method="POST" action="{{ route('user.update', $user->id) }}" class="form-horizontal" role="form" autocomplete="off" >
            {{ method_field('PUT') }}
    @endif

            {!! csrf_field() !!}

            <div class="box-body">

                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Name<span class="text-red">&nbsp;*</span></label>
                    <div class="col-md-6">
                        <input type="text" name="name" id="name" value="{{ (old('name') != null) ? old('name') : (isset($user->name) ? $user->name : '') }}" class="form-control" autocomplete="off"/>
                        @if($errors->has('name'))
                            <span class="text-red">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Email<span class="text-red">&nbsp;*</span></label>
                    <div class="col-md-6">
                        <input type="email" name="email" id="email" value="{{ (old('email') != null) ? old('email') : (isset($user->email) ? $user->email : '') }}" class="form-control" autocomplete="off"/>
                        @if($errors->has('email'))
                            <span class="text-red">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                @if(!isset($user))
                    <div class="form-group">
                        <label for="password" class="col-md-3 control-label">Password<span class="text-red">&nbsp;*</span></label>
                        <div class="col-md-6">
                            <input type="password" name="password" id="password" class="form-control" />
                            @if($errors->has('password'))
                                <span class="text-red">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="col-md-3 control-label">Confirm Password<span class="text-red">&nbsp;*</span></label>
                        <div class="col-md-6">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" />
                            @if($errors->has('password_confirmation'))
                                <span class="text-red">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 control-label">Roles</label>
                    <div {!! (count($roles) > 0) ? '' : 'style="padding-top: 7px;" ' !!} class="col-md-6">
                        @if(count($roles) > 0)
                            @foreach($roles as $per_chunk)
                                @foreach($per_chunk as $role)
                                    <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ (isset($user)) ? (($user->hasRole($role->name)) ? 'checked' : '') : '' }}><span style="margin-left: -3px; margin-right: 10px;">{{ $role->display_name }}</span></label>
                                @endforeach
                                <br>
                            @endforeach
                        @else
                            <a href="{{ route('role.create') }}">Create Roles</a> for assign to User.
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        @if(!isset($user))
                            <button type="submit" class="btn btn-success">Save</button>
                        @else
                            <button type="submit" class="btn btn-success">Update</button>
                        @endif
                        <a href="{{ route('user.index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
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