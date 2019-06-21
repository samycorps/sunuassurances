<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleClaim extends Model
{
    //
    protected $table = 'vehicle_claims';

    protected $fillable = [
        'user_id', 'claim_no', 'profile_id', 'policy_no', 'registration_no', 'form_details', 'created_at'. 'updated_at', 'status', 'staff_id'
    ];
}
