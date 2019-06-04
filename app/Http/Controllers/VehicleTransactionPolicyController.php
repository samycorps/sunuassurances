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
use Illuminate\Support\Facades\DB;

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
        // $policyLog->info('PolicyLog', $policies);
        return $policies;
    }

    public function getPoliciesByUserId($userId)
    {
        try {
            $query = "SELECT policy.*, details.form_details, details.registration_number, payment.transaction_reference, payment.transaction_amount, payment.transaction_date, payment.response_message, profiles.firstname, profiles.lastname, profiles.title, profiles.company_name, profiles.user_category 
            FROM vehicle_transaction_policy policy 
            JOIN vehicle_transaction_details details ON 
            SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) = SUBSTRING_INDEX(details.vehicle_details_id, '_',-1)
            AND policy.user_id = details.user_id
            JOIN vehicle_transaction_payment payment ON 
            SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) = SUBSTRING_INDEX(payment.vehicle_transaction_details_id, '_',-1)
            AND policy.user_id = payment.user_id
            JOIN profiles ON policy.profile_id = profiles.id
            WHERE policy.user_id = $userId;";
            $policies = DB::select( DB::raw($query));
            Log::info($query);
            Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }

}
