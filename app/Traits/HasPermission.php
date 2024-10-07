<?php

namespace App\Traits;

use App\Models\Role;

trait HasPermission
{
    public function hasRole($name)
    {
        return $this->roles->contains('name', $name);
    }

    public function hasPermission($permission)
    {
        $user = auth()->user();
        if ($user) {
            return $user->roles->whereHas('permissions', function ($q) use ($permission) {
                $q->whereIn('name', $permission);
            })->exists();
        }
        return !!$permission->intersect($this->roles->pluck('permissions'))->count();
    }
}
