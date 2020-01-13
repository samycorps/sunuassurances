<?php

namespace App\Http\Services;

use App\Profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProfileService {

    public function addProfile($profileData, $userId) {
        Log::info($profileData);
        $profile = Profile::create([
            'user_id' => $userId,
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
            'contact_person' => isset($profileData['contact_person']) ? $profileData['contact_person'] : 'N/A',
            'bank_account_number' => isset($profileData['bank_account_number']) ? $profileData['bank_account_number'] : 'N/A',
            'customer_bank' => isset($profileData['customer_bank']) ? $profileData['customer_bank'] : 'N/A',
            'website' => isset($profileData['website']) ? $profileData['website'] : 'N/A',
            'agent_code' => isset($profileData['agent_code']) ? $profileData['agent_code'] : NULL,
        ]);

        return $profile;
    }

    public function update(Request $request, $id)
    {
        
    }

    public function getProfile($userId) {
        return Profile::where('user_id', $userId)->first();
    }
}