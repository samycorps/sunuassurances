<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class ReportService {

    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function getSalesByDateRange($start, $end, $startDate, $endDate, $filterStr) {
        try {
            $query = "(SELECT SUBSTRING_INDEX(policy.vehicle_transaction_details_id, '_',-1) as registration_number, policy.vehicle_transaction_details_id, policy.policy_type, policy.cover_type, policy.client_number, 
            policy.client_number, policy.policy_number, policy.created_at, payment.transaction_amount, payment.transaction_date,
            profiles.firstname, profiles.lastname, profiles.title, profiles.company_name, profiles.user_category 
            FROM vehicle_transaction_policy policy JOIN vehicle_transaction_payment payment ON policy.vehicle_transaction_details_id = payment.vehicle_transaction_details_id 
            JOIN profiles ON profiles.id = policy.profile_id
            WHERE (policy.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')
            $filterStr 
            LIMIT $start, $end)
            UNION 
            (SELECT COUNT(id) AS total_records, '','','','','','','','','', '','','','',''
                FROM vehicle_transaction_policy
            WHERE (created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')
            )";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }

    public function getSalesByProducts() {
        try {
            $query = "SELECT policy.cover_type, count(policy.vehicle_transaction_details_id) as total_sales, sum(payment.transaction_amount)/100 total_amount 
            from vehicle_transaction_policy policy inner join vehicle_transaction_payment payment 
            on policy.vehicle_transaction_details_id = payment.vehicle_transaction_details_id
            group by cover_type;";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }

    public function getSalesByAgents() {
        try {
            $query = "select policy.user_id, count(policy_number) num_of_policies, 
            profiles.firstname, profiles.lastname, profiles.company_name,
            sum(payment.transaction_amount)/100 as total_amount 
            from vehicle_transaction_policy policy inner join users
            on policy.user_id = users.id
            inner join profiles on profiles.user_id = users.id
            inner join vehicle_transaction_payment payment ON policy.vehicle_transaction_details_id = payment.vehicle_transaction_details_id
            where users.role_id = 2
            group by policy.user_id, profiles.firstname, profiles.lastname, profiles.company_name";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }

    public function getActivePolicies() {
        try {
            $query = "SELECT  SUBSTRING_INDEX(vehicle_transaction_details_id, '_',-1) as registration_number, cover_type, policy_number, client_number, certificate_number, policy.created_at, STR_TO_DATE(expiry_date, '%d-%b-%y.') as formatted_expiry_date,
            profiles.firstname, profiles.lastname, profiles.company_name
            FROM  vehicle_transaction_policy policy INNER JOIN profiles
            ON policy.profile_id = profiles.id
            WHERE STR_TO_DATE(expiry_date, '%d-%b-%y.') > DATE_FORMAT(now(), '%Y-%m-%d');";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }

    public function getExpiringPolicies($days) {
        try {
            $query = "SELECT  SUBSTRING_INDEX(vehicle_transaction_details_id, '_',-1) as registration_number, cover_type, policy_number, client_number, certificate_number, policy.created_at, STR_TO_DATE(expiry_date, '%d-%b-%y.') as formatted_expiry_date,
            profiles.firstname, profiles.lastname, profiles.company_name
            FROM  vehicle_transaction_policy policy INNER JOIN profiles
            ON policy.profile_id = profiles.id
            WHERE (STR_TO_DATE(expiry_date, '%d-%b-%y.') BETWEEN DATE_FORMAT(now(), '%Y-%m-%d') AND DATE_FORMAT(now() + INTERVAL $days DAY, '%Y-%m-%d') );";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }

    public function getExpiredPolicies() {
        try {
            $query = "SELECT  SUBSTRING_INDEX(vehicle_transaction_details_id, '_',-1) as registration_number, cover_type, policy_number, client_number, certificate_number, policy.created_at, STR_TO_DATE(expiry_date, '%d-%b-%y.') as formatted_expiry_date,
            profiles.firstname, profiles.lastname, profiles.company_name
            FROM  vehicle_transaction_policy policy INNER JOIN profiles
            ON policy.profile_id = profiles.id
            WHERE STR_TO_DATE(expiry_date, '%d-%b-%y.') <= DATE_FORMAT(now(), '%Y-%m-%d');";
            Log::info($query);
            
            $policies = DB::select( DB::raw($query));
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return json_encode($error, $error['code']);
        }
    }
}