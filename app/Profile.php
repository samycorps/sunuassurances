<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = [
        'user_id', 'user_category', 'title', 'firstname', 'lastname', 'othernames', 'company_name', 'company_reg_num', 'street_address', 'city', 'state', 'local_govt_area', 'date_of_birth', 'gsm_number', 'office_number', 'email_address', 'occupation', 'sector', 'tin_number', 'fax_number', 'website', 'contact_person', 'bank_account_number', 'customer_bank', 'agent_code', 'status', 'created_by'
    ];
}
