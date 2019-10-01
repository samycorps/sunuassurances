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
            // $query = "SELECT DISTINCT policy.*, c_max.*, v_max.*, profiles.firstname, profiles.lastname, profiles.title, profiles.company_name, profiles.user_category FROM vehicle_transaction_policy policy JOIN (
            //     SELECT    MAX(payment.id) max_id, payment.vehicle_transaction_details_id, payment.transaction_reference, payment.transaction_amount, payment.transaction_date, payment.response_message
            //     FROM vehicle_transaction_payment payment
            //     where user_id = $userId
            //     GROUP BY  vehicle_transaction_details_id, transaction_reference, payment.transaction_amount, payment.transaction_date, payment.response_message
            //     ORDER BY max_id LIMIT 1
            // ) c_max ON (SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) = SUBSTRING_INDEX(c_max.vehicle_transaction_details_id, '_',-1))
            // JOIN (
            //     SELECT MAX(details.id) max_id, vehicle_details_id, details.form_details, details.registration_number
            //     FROM 	vehicle_transaction_details details
            //     WHERE user_id = $userId
            //     GROUP BY  vehicle_details_id, registration_number, form_details
            //     ORDER BY max_id LIMIT 1
            // ) v_max ON (SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) = SUBSTRING_INDEX(v_max.vehicle_details_id, '_',-1))
            // JOIN profiles ON policy.profile_id = profiles.id
            // WHERE policy.user_id = $userId;";
            
            $query = "SELECT policy.*, SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) as registration_number,  
            profiles.firstname, profiles.lastname, profiles.title, profiles.company_name, profiles.user_category, 
            payment.vehicle_transaction_details_id, payment.transaction_reference, payment.transaction_amount, payment.transaction_date, payment.response_message,
            details.form_details 
            FROM vehicle_transaction_policy policy JOIN vehicle_transaction_payment payment ON policy.vehicle_transaction_details_id = payment.vehicle_transaction_details_id 
            JOIN profiles ON profiles.id = policy.profile_id
            LEFT JOIN vehicle_transaction_details details ON policy.vehicle_transaction_details_id = details.vehicle_details_id 
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
