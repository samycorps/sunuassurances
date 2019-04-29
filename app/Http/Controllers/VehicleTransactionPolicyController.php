<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleTransactionPolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class VehicleTransactionPolicyController extends Controller
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Create a new vehicle transaction policy.
     *
     * @param  array  $data
     * @return VehicleTransactionPolicy
     */
    public function store(Request $data)
    {
        Log::info($data);
        $policyRecord = [
            'user_id' => $data['userId'],
            'profile_id' => $data['profileId'],
            'vehicle_transaction_details_id' => $data['vehicleTransactionDetailsId'],
            'policy_type' => $data['policyType'],
            'cover_type' => $data['coverType'],
            'cover_option' => $data['coverOption'],
            'cover_add_ons' => $data['coverAddOns'],
            'client_number' => trim($data['ClientNumber']),
            'policy_number' => $data['PolicyNumber'],
            'certificate_number' => $data['CertificateNumber'],
            'debit_note_number' => $data['DebitNoteNumber'],
            'receipt_number' => trim($data['ReceiptNumber']),
            'expiry_date' => $data['ExpiryDate']
        ];
        $policyLog = new Logger('policy');
        $policyLog->pushHandler(new StreamHandler(storage_path('logs/policy.log')), Logger::INFO);
        $policyLog->info('PolicyLog', $policyRecord);
        try {
            $transaction_policy_creation = VehicleTransactionPolicy::create($policyRecord);
            return $transaction_policy_creation;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }

    public function getIndividualPolicyList($profileId)
    {
        $policies = VehicleTransactionPolicy::where('profile_id', $profileId)->get();

        return $policies;
    }

}
