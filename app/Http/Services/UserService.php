<?php

namespace App\Http\Services;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\ProfileService;
use App\Http\Services\RoleService;
use Illuminate\Support\Facades\Log;

class UserService {

    public function getSessionUserDetails($userId) {
        
        $user = User::with('profile', 'role')->where('id', $userId)->first();
        $userprofile = [
            'user' => $user,
            'profile' => $user['profile'],
            'role' => $user['role']
        ];
        $request->session()->put('userData', $userprofile);
        return $user;
    }
}