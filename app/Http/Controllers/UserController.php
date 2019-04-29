<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Services\ProfileService;
use App\Http\Services\RoleService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $userRoles = array('client' => 3, 'agent' => 2, 'admin' => 1);
    private $className = 'App\Http\Services\ProfileService';
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function store(Request $data)
    {
        $role = $data['role'];
        $profileService = new ProfileService();
        try {
            Log::info($data);
            $user_creation = User::create([
                'username' => $data['username'],
                'email_address' => $data['email_address'],
                'password' => Hash::make($data['password']),
                'role_id' => $this->userRoles[$role],
                'user_category' => $data['user_category'],
                'activation_token' => Hash::make($data['firstname'].$data['lastname'].$data['username'])
            ]);
            $addProfile = $profileService->addProfile($data['profile'], $user_creation['id']);
            $user_creation['profile'] = $addProfile;
            return $user_creation;
        } catch (\Exception $exception) {
            $error = $this->parseException($exception);
            return response()->json($error, $error['code']);
        }
        
    }

    public function validator(Request $request)
    {
        $profileService = new ProfileService();
        $roleService = new RoleService();
        $data = $request->all();
        Log::info($data);
        $response = Auth::attempt(['username' => $data['username'], 'password' => $data['password']]);
        Log::info($response);
        if($response) {
            $user = Auth::user();
            $profile = $profileService->getProfile($user['id']);
            $role = $roleService->getRole($user['role_id']);
            $userprofile = [
                'user' => $user,
                'profile' => $profile,
                'role' => $role
            ];
            $request->session()->put('userData', $userprofile);
            return $userprofile;
            
        }
        else {
            // return json_encode(array('status' => false, 'message'=>'Username or Password is invalid'));
            return response()->json(array('status' => false, 'message'=>'Username or Password is invalid'), 401);
        }

        return response()->json($response, 200);
        
    }

    private function parseException($exception) {
        Log::info($exception->getCode());
        $errorCode = $exception->getCode();
        $errorObject = array('error' => true, 'code' => 500, 'message' => 'An error occured');
        switch ($errorCode) {
            case 23000: {
                $errorObject = array('error' => true, 'code' => 409, 'message' => $exception->getMessage());
                break;
            }
            default: {
                break;
            }
        }

        return $errorObject;
    }
}
