<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['permission_id', 'role_id', 'action_id'];

    public function permission()
    {
        return $this->belongsTo('App\Permission', 'permission_id');
    }
}
