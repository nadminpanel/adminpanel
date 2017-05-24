<?php

namespace NAdminPanel\AdminPanel\Repositories;

use Illuminate\Support\Facades\Auth;

class CommonRepository
{
    public function isHasPermissionAccess($permission, $request = null)
    {
        $user = Auth::user();
        if(!$user->hasPermissionTo($permission) || !$user->hasRole('developer')) {
            if($request != null && $request->ajax()) {
                return response()->json(['message' => 'Permission Denied'], 403);
            }
            return redirect()->to(config('nadminpanel.admin_landing_link'));
        }
    }
}