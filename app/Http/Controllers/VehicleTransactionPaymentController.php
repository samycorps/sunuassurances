<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleTransactionPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class VehicleTransactionPaymentController extends Controller
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Create a new vehicle transaction payment.
     *
     * @param  array  $data
     * @return VehicleTransactionPayment
     */
    public function store(Request $data)
    {
        Log::info($data);
        $paymentRecord = [
            'user_id' => $data['userId'],
            'transaction_reference' => $data['transactionReference'],
            'customer_email' => trim($data['customerEmail']),
            'transaction_amount' => $data['transactionAmount'],
            'transaction_date' => $data['transactionDate'],
            'payment_gateway' => $data['paymentGateway'],
            'response_status' => trim($data['responseStatus']),
            'response_reference' => $data['responseReference'],
            'response_message' => $data['responseMessage'],
            'vehicle_transaction_details_id' => $data['vehicleTransactionDetailsId']
        ];
        $paymentLog = new Logger('payment');
        $paymentLog->pushHandler(new StreamHandler(storage_path('logs/payment.log')), Logger::INFO);
        $paymentLog->info('PaymentLog', $paymentRecord);
        try {
            $transaction_payment_creation = VehicleTransactionPayment::create($paymentRecord);
            return $transaction_payment_creation;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }
}
