<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    public function readPermission($role)
    {
        return RolePermission::where('role_id', $role)->where('action_id', READ)->where('permission_id', $this->id)->exists();
    }

    public function writePermission($role)
    {
        return RolePermission::where('role_id', $role)->where('action_id', WRITE)->where('permission_id', $this->id)->exists();
    }
}
