<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\LegendService;
use App\LegendRequestsLog;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class LegendController extends Controller
{
    private $legendService;
    protected $helper;

    public function __construct(LegendService $legendService, Helper $helper)
    {
        $this->legendService = $legendService;
        $this->helper = $helper;
    }

    /**
    * Return a single role information
    */
    public function createPolicy(Request $request)
    {
        try {
            $data = $request->all();
            Log::info($data);
                $transaction_creation = LegendRequestsLog::create([
                    'vehicle_transaction_details_id' => $data['vehicleTransactionDetailsId'],
                    'payment_reference' => $data['payment_reference'],
                    'request_body' => json_encode($data)
                ]);
            if ($data['policy_type'] === 'motor') {
                $response = $this->legendService->getPolicyNumber($data);
            }
            else if ($data['policy_type'] === 'marine') {
                $response = $this->legendService->getMarinePolicyNumber($data);
            }

            //Update request log
            $transaction_creation->update([
                'legend_response' => $response
            ]);
            return response()->json(array('message' => $response), 200);
        } catch(\Exception $ne) {
            Log::error($ne);
            return response()->json($ne->getMessage(), 400);
        }
    }

    public function createAdditionalPolicy(Request $request)
    {
        try {
            $data = $request->all();
            Log::info($data);
                $transaction_creation = LegendRequestsLog::create([
                    'vehicle_transaction_details_id' => isset($data['vehicleTransactionDetailsId']) ? $data['vehicleTransactionDetailsId'] : NULL,
                    'payment_reference' => $data['payment_reference'],
                    'request_body' => json_encode($data)
                ]);
            $response = $this->legendService->additionalPolicy($data);
            //Update request log
            $transaction_creation->update([
                'legend_response' => $response
            ]);
            return response()->json(array('message' => $response), 200);
        } catch(\Exception $ne) {
            Log::error($ne);
            return response()->json($ne->getMessage(), 400);
        }
    }

    public function renewPolicy(Request $request)
    {
        try {
            $data = $request->all();
            Log::info($data);
                $transaction_creation = LegendRequestsLog::create([
                    'vehicle_transaction_details_id' => isset($data['vehicleTransactionDetailsId']) && !empty($data['vehicleTransactionDetailsId']) ? $data['vehicleTransactionDetailsId'] : 0,
                    'payment_reference' => $data['payment_reference'],
                    'request_body' => json_encode($data)
                ]);
            $response = $this->legendService->renewPolicy($data);
            //Update request log
            $transaction_creation->update([
                'legend_response' => $response
            ]);
            return response()->json(array('message' => $response), 200);
        } catch(\Exception $ne) {
            Log::error($ne);
            return response()->json($ne->getMessage(), 400);
        }
    }

    public function enquiryPolicyNumber(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->legendService->enquiryPolicyNumber($data);
            return response()->json(array('message' => $response), 200);
        } catch(\Exception $ne) {
            Log::error($ne);
            return response()->json($ne->getMessage(), 400);
        }
    }

    public function getPoliciesRequestLog($page, $limit, $start_date, $end_date, $filter=null)
    {
        $start = (intval($page) < 1 ? 0 : intval($page) - 1) * intval($limit);
        $end = intval($limit) + $start;
        $filterStr = '';
        if (!is_null($filter) && $filter === 'success') {
            $filterStr = " AND legend.legend_response LIKE 'Client Number:%'";
        }
        else if (!is_null($filter) && $filter === 'failure') {
            $filterStr = " AND legend.legend_response NOT LIKE 'Client Number:%'";
        }
        Log::info($filterStr);
        try {
            $query = "(SELECT legend.request_body, legend.legend_response, legend.payment_reference, legend.created_at, 
            payment.transaction_reference, payment.response_message,payment.customer_email, payment.transaction_date, 
            payment.response_reference, payment.created_at,
            (CASE 
                WHEN legend.legend_response LIKE 'Client Number:%' THEN 'Policy Generation Sucessful'
                ELSE 'Policy Generation Failed'
            END) AS legend_status
                FROM legend_requests_log legend
            INNER JOIN vehicle_transaction_payment payment 
            ON legend.vehicle_transaction_details_id = payment.vehicle_transaction_details_id
            WHERE (legend.created_at between '$start_date 00:00:00' AND '$end_date 23:59:59')
            $filterStr 
            LIMIT $start, $end)
UNION 
(SELECT COUNT(legend.id) AS total_records, '','','','','','','','','', ''
                FROM legend_requests_log legend
            INNER JOIN vehicle_transaction_payment payment 
            ON legend.vehicle_transaction_details_id = payment.vehicle_transaction_details_id
            WHERE (legend.created_at between '$start_date 00:00:00' AND '$end_date 23:59:59')
            $filterStr)
            ;";
            
            $policies = DB::select( DB::raw($query));
            Log::info($query);
            // Log::info($policies);
            return $policies;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }
}
