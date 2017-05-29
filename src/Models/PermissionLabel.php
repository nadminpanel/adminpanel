<?php

namespace NAdminPanel\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionLabel extends Model
{
    use SoftDeletes;

    protected $table = 'permission_labels';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    public static function exists($permission_name)
    {
        return (PermissionLabel::whereName($permission_name)->first()) ? true : false;
    }
}
