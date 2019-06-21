<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleClaim;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;

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
}
