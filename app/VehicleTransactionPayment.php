<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleTransactionPayment extends Model
{
    //
    protected $table = 'vehicle_transaction_payment';

    protected $fillable = [
        'vehicle_transaction_details_id', 'user_id', 'transaction_reference', 'customer_email', 'transaction_amount', 'transaction_date', 'payment_gateway', 'response_status', 'response_reference', 'response_message', 'retry_count', 'created_at', 'updated_at'
    ];

    public function details()
    {
    	return $this->belongsTo('App\VehicleTransactionDetail');
    }
}
