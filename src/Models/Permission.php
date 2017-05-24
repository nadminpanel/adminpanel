<?php

namespace NAdminPanel\AdminPanel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NAdminPanel\AdminPanel\Traits\PermissionTrait;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use SoftDeletes, PermissionTrait;

    protected $dates = ['deleted_at'];

}
