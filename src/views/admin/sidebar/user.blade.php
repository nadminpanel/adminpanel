@if(NAdminPanel\AdminPanel\Models\PermissionLabel::exists('user') && (\Auth::user()->hasPermissionTo('show user') || \Auth::user()->hasPermissionTo('create user') || \Auth::user()->hasRole('developer')))
<li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/user', true) }} treeview">
    <a href="#">
        <i class="fa fa-user"></i>
        <span>Users</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @if(\Auth::user()->hasPermissionTo('show user') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('user.index') }}"><a href="{{ route('user.index') }}"><i class="fa fa-circle-o"></i> User List</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('create user') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('user.create') }}"><a href="{{ route('user.create') }}"><i class="fa fa-circle-o"></i> Create User</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('show user') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('user.archive') }}"><a href="{{ route('user.archive') }}"><i class="fa fa-circle-o"></i> Archive User List</a></li>
        @endif
    </ul>
</li>
@endif