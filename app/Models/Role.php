<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }


    public function users()
    {
        return $this->belongsToMany(User::class,'users_roles');
    }

    public function hasPermission($permissionSlugPattern)
    {
        // Check if the role has a permission matching the given pattern
        $permissions = $this->permissions->pluck('slug')->toArray();

        foreach ($permissions as $permission) {
            if (preg_match($permissionSlugPattern, $permission)) {
                return true;
            }
        }

        return false;
    }

}
