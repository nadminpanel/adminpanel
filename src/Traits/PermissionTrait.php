<?php

namespace NAdminPanel\AdminPanel\Traits;

trait PermissionTrait
{
    public function scopeGetId($query, $permission_name)
    {
        return $query->whereName($permission_name)->first()->id;
    }
}