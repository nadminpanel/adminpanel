@if(\Auth::user()->hasPermissionTo('show admin') || \Auth::user()->hasRole('developer'))
<li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/admin', true) }} treeview">
    <a href="#">
        <i class="fa fa-user-secret"></i>
        <span>Admins</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'/admin') }}"><a href="{{ route('admin.index') }}"><i class="fa fa-circle-o"></i> Admin List</a></li>
        <li class="{{ active_check(config('nadminpanel.admin_backend_prefix').'admin/archive') }}"><a href="{{ route('admin.archive') }}"><i class="fa fa-circle-o"></i> Archive Admin List</a></li>
    </ul>
</li>
@endif