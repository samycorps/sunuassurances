<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function store(Request $profileData)
    {
        Log::info($profileData);
        try {
            $profile = Profile::create([
                'user_id' => isset($profileData['userId']) ? $profileData['userId'] : NULL,
                'user_category' => isset($profileData['user_category']) ? $profileData['user_category'] : '',
                'title' => isset($profileData['title']) ? $profileData['title'] : NULL,
                'gender' => isset($profileData['gender']) ? $profileData['gender'] : 'N/A',
                'firstname' => isset($profileData['firstname']) ? $profileData['firstname'] : NULL,
                'lastname' => isset($profileData['lastname']) ? $profileData['lastname'] : NULL,
                'othernames' => isset($profileData['othernames']) ? $profileData['othernames'] : NULL,
                'company_name' => isset($profileData['company_name']) ? $profileData['company_name'] : NULL,
                'company_reg_num' => isset($profileData['company_reg_num']) ? $profileData['company_reg_num'] : 'N/A',
                'street_address' => $profileData['street_address'],
                'city' => $profileData['city'],
                'state' => $profileData['state'],
                'local_govt_area' => isset($profileData['local_govt_area']) ? $profileData['local_govt_area'] : NULL,
                'date_of_birth' => isset($profileData['date_of_birth']) ? $profileData['date_of_birth'] : NULL,
                'gsm_number' => isset($profileData['gsm_number']) ? $profileData['gsm_number'] : NULL,
                'office_number' => isset($profileData['office_number']) ? $profileData['office_number'] : NULL,
                'email_address' => isset($profileData['email_address']) ? $profileData['email_address'] : NULL,
                'occupation' => isset($profileData['occupation']) ? $profileData['occupation'] : NULL,
                'sector' => isset($profileData['sector']) ? $profileData['sector'] : NULL,
                'tin_number' => isset($profileData['tin_number']) ? $profileData['tin_number'] : NULL,
                'fax_number' => isset($profileData['fax_number']) ? $profileData['fax_number'] : NULL,
                'website' => isset($profileData['website']) ? $profileData['website'] : 'N/A',
                'contact_person' => isset($profileData['contact_person']) ? $profileData['contact_person'] : 'N/A',
                'bank_account_number' => isset($profileData['bank_account_number']) ? $profileData['bank_account_number'] : 'N/A',
                'customer_bank' => isset($profileData['customer_bank']) ? $profileData['customer_bank'] : 'N/A',
                'agent_code' => isset($profileData['agent_code']) ? $profileData['agent_code'] : NULL,
                'created_by' => isset($profileData['created_by']) ? $profileData['created_by'] : NULL,
            ]);
            return $profile;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }

    public function update(Request $request, $id)
    {
        try 
        {
            $profile = Profile::findOrFail($id);
            Log::info($request->all());
            $profileData = $request->all();

            if(!empty($profile)) {
                $profile->update([
                    'title' => isset($profileData['title']) ? $profileData['title'] : NULL,
                    'gender' => isset($profileData['gender']) ? $profileData['gender'] : 'N/A',
                    'firstname' => isset($profileData['firstname']) ? $profileData['firstname'] : NULL,
                    'lastname' => isset($profileData['lastname']) ? $profileData['lastname'] : NULL,
                    'othernames' => isset($profileData['othernames']) ? $profileData['othernames'] : NULL,
                    'company_name' => isset($profileData['company_name']) ? $profileData['company_name'] : NULL,
                    'company_reg_num' => isset($profileData['company_reg_num']) ? $profileData['company_reg_num'] : 'N/A',
                    'street_address' => $profileData['street_address'],
                    'city' => $profileData['city'],
                    'state' => $profileData['state'],
                    'local_govt_area' => isset($profileData['local_govt_area']) ? $profileData['local_govt_area'] : NULL,
                    'date_of_birth' => isset($profileData['date_of_birth']) ? $profileData['date_of_birth'] : NULL,
                    'gsm_number' => isset($profileData['gsm_number']) ? $profileData['gsm_number'] : NULL,
                    'office_number' => isset($profileData['office_number']) ? $profileData['office_number'] : NULL,
                    'email_address' => isset($profileData['email_address']) ? $profileData['email_address'] : NULL,
                    'occupation' => isset($profileData['occupation']) ? $profileData['occupation'] : NULL,
                    'sector' => isset($profileData['sector']) ? $profileData['sector'] : NULL,
                    'tin_number' => isset($profileData['tin_number']) ? $profileData['tin_number'] : NULL,
                    'fax_number' => isset($profileData['fax_number']) ? $profileData['fax_number'] : NULL,
                    'website' => isset($profileData['website']) ? $profileData['website'] : 'N/A',
                    'contact_person' => isset($profileData['contact_person']) ? $profileData['contact_person'] : 'N/A',
                    'bank_account_number' => isset($profileData['bank_account_number']) ? $profileData['bank_account_number'] : 'N/A',
                    'customer_bank' => isset($profileData['customer_bank']) ? $profileData['customer_bank'] : 'N/A',
                    'agent_code' => isset($profileData['agent_code']) ? $profileData['agent_code'] : NULL,
                ]);
            }
            $userData = $request->session()->get('userData', []);
            $userData['profile'] = $profile;
            $request->session()->put('userData', $userData);
            return $profile;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }

    public function getAgentClientList($user_id) {
        $profiles = Profile::where('user_id', $user_id)->get();
        Log::info($profiles);
        return $profiles;
    }

    public function getProfileList($param) {
        $profiles = Profile::where('firstname', 'LIKE', "%{$param}%")
                    ->orWhere('lastname', 'LIKE', "%{$param}%")
                    ->orWhere('company_name', 'LIKE', "%{$param}%")->get();
        // Log::info($profiles);
        $profiledata = [];
        foreach($profiles as $k => $v) {
            $name = empty($v["firstname"]) ? $v["company_name"] : $v["firstname"].' '.$v["lastname"];
            array_push($profiledata, ["id" => $v["id"], "name" => $name, "profile" => $v ]);
        }
        return $profiledata;
    }
}
