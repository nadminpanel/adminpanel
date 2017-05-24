@if(\Auth::user()->hasPermissionTo('show role') || \Auth::user()->hasPermissionTo('create role') || \Auth::user()->hasRole('developer'))
<li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/role', true) }} treeview">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Roles</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @if(\Auth::user()->hasPermissionTo('show role') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/role') }}"><a href="{{ route('role.index') }}"><i class="fa fa-circle-o"></i> Role List</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('create role') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/role/create') }}"><a href="{{ route('role.create') }}"><i class="fa fa-circle-o"></i> Create Role</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('show role') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/role/archive') }}"><a href="{{ route('role.archive') }}"><i class="fa fa-circle-o"></i> Archive Role List</a></li>
        @endif
    </ul>
</li>
@endif