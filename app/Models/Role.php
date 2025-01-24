<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function scopeNotAdmin($query)
    {
        return $query->where('id', '>', '6');
    }

    public function scopeAdmin($query)
    {
        return $query->where('name', 'Admin');
    }
}
