<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\LegendService;
use App\LegendRequestsLog;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;

class LegendController extends Controller
{
    private $legendService;

    public function __construct(LegendService $legendService)
    {
        $this->legendService = $legendService;
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
}
