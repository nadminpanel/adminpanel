@if(NAdminPanel\AdminPanel\Models\PermissionLabel::exists('permission') && (\Auth::user()->hasPermissionTo('show permission') || \Auth::user()->hasPermissionTo('create permission') || \Auth::user()->hasRole('developer')))
<li class="{{ active_route('permission.*') }} treeview">
    <a href="#">
        <i class="fa fa-shield"></i>
        <span>Permissions</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @if(\Auth::user()->hasPermissionTo('show permission') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('permission.index') }}"><a href="{{ route('permission.index') }}"><i class="fa fa-circle-o"></i> Permission List</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('create permission') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('permission.create') }}"><a href="{{ route('permission.create') }}"><i class="fa fa-circle-o"></i> Create Permission</a></li>
        @endif
        @if(\Auth::user()->hasPermissionTo('show permission') || \Auth::user()->hasRole('developer'))
            <li class="{{ active_route('permission.archive') }}"><a href="{{ route('permission.archive') }}"><i class="fa fa-circle-o"></i> Archive Permission List</a></li>
        @endif
    </ul>
</li>
@endif