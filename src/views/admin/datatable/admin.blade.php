@if(active_check(config('nadminpanel.admin_backend_prefix').'/admin') == 'active')
	@if($user->hasPermissionTo('edit user') || $user->hasRole('developer'))
		<a href="{{ route('user.edit', $user->id) }}" class="btn btn-xs btn-primary">
			<i class="glyphicon glyphicon-edit"></i> Edit
		</a>
	@endif
	@if($user->hasPermissionTo('delete user') || $user->hasRole('developer'))
		<a class="btn btn-xs bg-red-active delete" data-id="{{ $user->id }}">
			<i class="fa fa-trash"></i> Archive
		</a>
	@endif

@elseif(active_check(config('nadminpanel.admin_backend_prefix').'/admin/archive') == 'active')
	@if($user->hasPermissionTo('edit user') || $user->hasRole('developer'))
		<a class="btn btn-xs bg-blue-active unarchive" data-id="{{ $user->id }}">
			<i class="fa fa-trash"></i> UnArchived
		</a>
	@endif
	@if($user->hasPermissionTo('delete user') || $user->hasRole('developer'))
		<a class="btn btn-xs bg-red-active delete" data-id="{{ $user->id }}">
			<i class="fa fa-trash"></i> Deleted
		</a>
	@endif

@endif

