<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleTransactionDetail extends Model
{
    //
    protected $table = 'vehicle_transaction_details';

    protected $fillable = [
        'vehicle_details_id', 'user_id', 'profile_id', 'registration_number', 'form_details',  'vehicle_model', 'colour', 'chasis_number', 'engine_number', 'vehicle_make', 'vehicle_status', 'issue_date', 'expiry_date', 'vehicle_body', 'vehicle_cubic_capacity', 'vehicle_num_of_seats', 'year_of_make', 'year_of_purchase', 'purchase_price', 'state_of_purchase', 'contact_person', 'bank_account_bvn', 'bank_account_number', 'customer_bank_name', 'company_bank_name', 'sector', 'effective_date', 'created_at', 'updated_at'
    ];

    public function payment()
    {
    	return $this->hasMany('App\VehicleTransactionPayment','vehicle_transaction_details_id', 'vehicle_details_id');
    }

    public function policy()
    {
    	return $this->hasMany('App\VehicleTransactionPolicy', 'vehicle_transaction_details_id', 'vehicle_details_id');
    }

    public function requestLog()
    {
    	return $this->hasMany('App\LegendRequestsLog', 'vehicle_transaction_details_id', 'vehicle_details_id');
    }
}
