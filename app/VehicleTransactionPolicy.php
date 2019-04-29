<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleTransactionPolicy extends Model
{
    //
    protected $table = 'vehicle_transaction_policy';

    protected $fillable = [
        'vehicle_transaction_details_id', 'user_id', 'profile_id', 'policy_type', 'cover_type', 'cover_add_ons', 'cover_option',  'client_number', 'policy_number', 'certificate_number', 'debit_note_number', 'receipt_number', 'expiry_date', 'created_at', 'updated_at'
    ];

    public function details()
    {
    	return $this->belongsTo('App\VehicleTransactionDetail');
    }
}
