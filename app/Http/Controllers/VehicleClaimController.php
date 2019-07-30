<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleClaim;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VehicleClaimController extends Controller
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Create a new claim instance.
     *
     * @param  array  $data
     * @return VehicleClaim
     */
    public function store(Request $data)
    {
        try {
            Log::info($data);
            $claim_creation = VehicleClaim::create([
                'claim_no' => $data['claimNumber'],
                'user_id' => $data['userId'],
                'profile_id' => $data['profileId'],
                'policy_no' => $data['policyNumber'],
                'registration_no' => $data['registrationNumber'],
                'form_details' => json_encode($data['formDetails']),
                'status' => 'new'
            ]);
            
            return $claim_creation;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
        
    }

    /**
    * Return a single claim details information
    */
    public function show($claim_no)
    {
        try {
            $vehicleClaimDetails = VehicleClaim::where('claim_no', $claim_no)->first();
            return $vehicleClaimDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }

    /**
    * Return a claim details information by profile
    */
    public function getClaimsByProfile($profile_id)
    {
        try {
            $vehicleClaimDetails = VehicleClaim::where('profile_id', $profile_id)->get();
            return $vehicleClaimDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }

    /**
     * Update existing claim details information
     */
    public function update(Request $request, $id)
    {
        try {
            $vehicleClaimDetails = VehicleClaim::findOrFail($id);
            Log::info($request->all());
            $data = $request->all();
            if(!empty($vehicleClaimDetails)) {
                $vehicleClaimDetails->update([
                    'user_id' => $data['userId'],
                    'profile_id' => $data['profileId'],
                    'registration_no' => $data['registrationNumber'],
                    'form_details' => json_encode($data['formDetails']),
                    'status' => $data['status']
                ]);
            }
            return $vehicleClaimDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }

    /**
     * Update existing claim details information
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $vehicleClaimDetails = VehicleClaim::findOrFail($id);
            Log::info($request->all());
            $data = $request->all();
            if(!empty($vehicleClaimDetails)) {
                $vehicleClaimDetails->update([
                    'status' => $data['status'],
                    'staff_id' => $data['staff_id']
                ]);
            }
            return $vehicleClaimDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }

    public function getClaimsLog($page, $limit, $start_date, $end_date, $filter=null, $search_by=null, $search_value=null)
    {
        $start = (intval($page) < 1 ? 0 : intval($page) - 1) * intval($limit);
        $end = intval($limit) + $start;
        $filterStr = '';
        if (!is_null($filter) && ($filter != 'all')) {
            $filterStr = " AND status = '$filter'";
        }
        
        if(!is_null($search_by)) {
            $search_value = str_replace('\\', '/', $search_value);
            Log::info('Search By '.$search_by.' Search Value: '.$search_value);
            switch ($search_by) {
                case 'email': {
                    $filterStr .= " AND form_details like '%\"email_address\":\"$search_value%'";
                    break;
                }
                case 'policy_number': {
                    $filterStr .= " AND policy_no LIKE '%$search_value%'";
                    break;
                }
                case 'registration_number': {
                    $filterStr .= " AND registration_no LIKE '%$search_value%'";
                    break;
                }
            }
        }
        Log::info($filterStr);
        try {
            $query = "(SELECT * from vehicle_claims
            WHERE (created_at between '$start_date 00:00:00' AND '$end_date 23:59:59')
            $filterStr 
            LIMIT $start, $end)
UNION 
(SELECT COUNT(id) AS total_records, '','','','','','','','','', ''
                FROM vehicle_claims
            WHERE (created_at between '$start_date 00:00:00' AND '$end_date 23:59:59')
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
