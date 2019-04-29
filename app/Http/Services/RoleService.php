<?php

namespace App\Http\Services;

use App\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RoleService {

    public function getRole($roleId) {
        return Role::where('id', $roleId)->first();
    }
}