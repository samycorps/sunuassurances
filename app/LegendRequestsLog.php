<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegendRequestsLog extends Model
{
    protected $table = 'legend_requests_log';

    protected $fillable = [
        'vehicle_transaction_details_id', 'payment_reference', 'request_body', 'legend_response', 'created_at', 'updated_at'
    ];

    public function requestLog()
    {
    	return $this->belongsTo('App\VehicleTransactionDetail');
    }
}
