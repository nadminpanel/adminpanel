@extends('nadminpanel::admin.common.main')

@section('title')
    @if(!isset($permission))
        Create new Permission
    @else
        Edit permission
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
                @if(!isset($permission))
                    Create
                @else
                    Edit
                @endif
                    Permission
            </h3>
            <a class="btn btn-success pull-right" href="{{ url('permission') }}">See All</a>
        </div><!-- /.box-header -->

    @if(!isset($permission))
        <form id="permission-form" method="POST" action="{{ route('permission.store') }}" class="form-horizontal" role="form" autocomplete="off">
    @else
        <form id="permission-form" method="POST" action="{{ route('permission.update', $permission->id) }}" class="form-horizontal" role="form" autocomplete="off">
            {{ method_field('PUT') }}
    @endif

            {!! csrf_field() !!}

            <div class="box-body">

                <div id="app" class="form-group">
                    <label for="name" class="col-md-3 control-label">Name<span class="text-red">&nbsp;*</span></label>
                    <div class="col-md-6">
                        <input v-model="name" type="text" name="name" id="name" placeholder="Enter Name (lowercase, nospace)" value="{{ (old('name') != null) ? old('name') : (isset($permission->name) ? $permission->name : '') }}" class="form-control" />
                        <span v-if="name">
                            @foreach(config('nadminpanel.permission_titles') as $key => $title)
                                @if($key == 0)
                                    ({{ $title }} @{{ name }},
                                @elseif($key != count(config('nadminpanel.permission_titles'))-1)
                                    {{ $title }} @{{ name }},
                                @else
                                    {{ $title }} @{{ name }})
                                @endif
                            @endforeach
                        </span>
                        @if($errors->has('name'))
                            <span class="text-red">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        @if(!isset($permission))
                            <button type="submit" class="btn btn-success">Save</button>
                        @else
                            <button type="submit" class="btn btn-primary">Update</button>
                        @endif
                        <a href="{{ route('permission.index') }}"><button type="button" class="btn btn-default">Cancel</button></a>
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
    <script type="text/javascript" src="{{ asset('plugins/vuejs/vue.min.js') }}"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                name: '{{ (old('name') != null) ? old('name') : (isset($permission->name) ? $permission->name : '') }}'
            }
        })
    </script>
@endsection