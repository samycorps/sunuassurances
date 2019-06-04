<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleTransactionDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Services\LegendService;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class VehicleTransactionDetailController extends Controller
{
    protected $helper;
    private $legendService;

    public function __construct(Helper $helper, LegendService $legendService)
    {
        $this->helper = $helper;
        $this->legendService = $legendService;
    }
    /**
     * Create a new vehicle transaction details.
     *
     * @param  array  $data
     * @return VehicleTransactionDetail
     */
    public function store(Request $data)
    {
        Log::info($data);
        $tableEntry = [
            'vehicle_details_id' => $data['id'],
            'profile_id' => $data['profileId'],
            'user_id' => $data['userId'],
            'registration_number' => $data['registrationNumber'],
            'form_details' => json_encode($data['formDetails']),
            // 'vehicle_model' => trim($data['vehicleModel']),
            // 'colour' => $data['vehicleColour'],
            // 'chasis_number' => $data['chasisNumber'],
            // 'engine_number' => $data['engineNumber'],
            // 'vehicle_make' => trim($data['vehicleMakeName']),
            // 'vehicle_status' => $data['vehicleStatus'],
            // 'issue_date' => $data['isssueDate'],
            // 'expiry_date' => $data['expiryDate'],
            // 'vehicle_body' => $data['vehicleBody'],
            // 'vehicle_cubic_capacity' => $data['vehicleCubicCapacity'],
            // 'vehicle_num_of_seats' => $data['vehicleNumOfSeats'],
            // 'year_of_make' => $data['vehicleYearOfMake'],
            // 'year_of_purchase' => $data['vehicleYearOfPurchase'],
            // 'purchase_price' => $data['vehiclePurchasePrice'],
            // 'state_of_purchase' => $data['vehicleStateOfPurchase'],
            // 'contact_person' => $data['contactPerson'],
            // 'bank_account_bvn' => $data['bankAccountBvn'],
            // 'bank_account_number' => $data['bankAccountNumber'],
            // 'customer_bank_name' => $data['customerBank'],
            // 'company_bank_name' => $data['companyBank'],
            // 'sector' => $data['occupationSector'],
            // 'effective_date' => $data['vehicleEffectiveDate']
        ];
        $vehicleLog = new Logger('vehicle');
        $vehicleLog->pushHandler(new StreamHandler(storage_path('logs/vehicle.log')), Logger::INFO);
        $vehicleLog->info('VehicleLog', $tableEntry);
        try {
            $transaction_creation = VehicleTransactionDetail::create($tableEntry);
            // $vehicleLog->info('VehicleLog Entry', json_encode($transaction_creation));
            Log::info($transaction_creation);
            return $transaction_creation;
        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
        
    }

    /**
    * Return a single transaction details information
    */
    public function show($registrationNumber)
    {
        try {
            $vehicleDetails = VehicleTransactionDetail::with('payment', 'policy')->where('registration_number', $registrationNumber)->first();
            return $vehicleDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }

    /**
     * Update existing vehicle information
     */
    public function update(Request $request, $id)
    {
        // 'color' => 'VC13-004',
        // 'model' => 'VM10-195',
        // 'registrationNumber' => 'FST884DU',
        // 'chasisNumber' => 'SHKYF1866H538424',
        // 'engineNumber' => '6PVJAA5J35A91341523',
        // 'vehicleMakeName' => 'Honda',
        // 'vehicleStatus' => 'Default',
        // 'isssueDate' => '2018-10-26 17:41:56',
        // 'expiryDate' => '2019-10-25 17:41:56',
        // 'profileId' => '10',
        // 'vehicleModel' => 'VM10-195',
        // 'vehicleBody' => 'VB13-002',
        // 'vehicleColour' => 'VC13-004',
        // 'vehicleCubicCapacity' => '2.8',
        // 'vehicleNumOfSeats' => '8',
        // 'vehicleYearOfMake' => '2015',
        // 'vehicleYearOfPurchase' => '2018',
        // 'vehiclePurchasePrice' => '3500000.00',
        // 'vehicleStateOfPurchase' => 'AKW',
        // 'contactPerson' => 'Eyitayo Falana',
        // 'bankAccountBvn' => '1111111111',
        // 'bankAccountNumber' => '0001966164',
        // 'customerBank' => 'BNK0001',
        // 'companyBank' => '310-0342',
        // 'occupationSector' => '05',
        // 'vehicleEffectiveDate' => '2018-12-25',
        try {
            $vehicleData = VehicleTransactionDetail::findOrFail($id);
            Log::info($request->all());
            $data = $request->all();

            if(!empty($vehicleData)) {
                $vehicleData->update([
                    'profile_id' => $data['profileId'],
                    'registration_number' => $data['registrationNumber'],
                    'form_details' => json_encode($data['formDetails']),
                    // 'vehicle_model' => trim($data['vehicleModel']),
                    // 'colour' => $data['vehicleColour'],
                    // 'chasis_number' => $data['chasisNumber'],
                    // 'engine_number' => $data['engineNumber'],
                    // 'vehicle_make' => trim($data['vehicleMakeName']),
                    // 'vehicle_status' => $data['vehicleStatus'],
                    // 'issue_date' => $data['isssueDate'],
                    // 'expiry_date' => $data['expiryDate'],
                    // 'vehicle_body' => $data['vehicleBody'],
                    // 'vehicle_cubic_capacity' => $data['vehicleCubicCapacity'],
                    // 'vehicle_num_of_seats' => $data['vehicleNumOfSeats'],
                    // 'year_of_make' => $data['vehicleYearOfMake'],
                    // 'year_of_purchase' => $data['vehicleYearOfPurchase'],
                    // 'purchase_price' => $data['vehiclePurchasePrice'],
                    // 'state_of_purchase' => $data['vehicleStateOfPurchase'],
                    // 'contact_person' => $data['contactPerson'],
                    // 'bank_account_bvn' => $data['bankAccountBvn'],
                    // 'bank_account_number' => $data['bankAccountNumber'],
                    // 'customer_bank_name' => $data['customerBank'],
                    // 'company_bank_name' => $data['companyBank'],
                    // 'sector' => $data['occupationSector'],
                    // 'effective_date' => $data['vehicleEffectiveDate']
                ]);
            }
            return $vehicleData;
        }catch(\Exception $exception) {
            Log::error($exception);
            Log::info($id.' could not be found');
            $error = $this->helper->parseException($exception);
            return response()->json($error, $error['code']);
        }
    }

    public function gettransactiondetails($id)
    {
        try {
            $vehicleDetails = VehicleTransactionDetail::with('payment', 'policy', 'requestLog')->where('vehicle_details_id', $id)->first();
            Log::info($vehicleDetails);

            return $vehicleDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
            return ['vehicleDetails' => []];
        }
    }

    public function gettransactiondetailsByProfile($profile_id)
    {
        try {
            $vehicleDetails = VehicleTransactionDetail::with('payment', 'policy', 'requestLog')->where('profile_id', $profile_id)->get();
            Log::info($vehicleDetails);

            return $vehicleDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
            return ['vehicleDetails' => []];
        }
    }

    public function gettransactiondetailsByRegistrationNumber($registration_number)
    {
        try {
            $vehicleDetails = VehicleTransactionDetail::with('payment', 'policy', 'requestLog')->where('registration_number', $registration_number)->first();
            Log::info($vehicleDetails);

            return $vehicleDetails;
        } catch(\Exception $ne) {
            Log::error($ne);
            return ['vehicleDetails' => []];
        }
    }

    public function getpolicy($id)
    {
        try {
            $vehicleDetails = VehicleTransactionDetail::with('requestLog')->where('vehicle_details_id', $id)->first();
            Log::info($vehicleDetails);

            $legendRequest = json_decode($vehicleDetails['requestLog'], true);
            Log::info($legendRequest);

            $legendData = json_decode($legendRequest[0]['request_body'], true);
            Log::info($legendData);

            $policyResponse = $this->legendService->getPolicyNumber($legendData);

            // return view('motor-policy', ['vehicleDetails' => $vehicleDetails, 'policyDetails' => $policyResponse]);
            return ['vehicleDetails' => $vehicleDetails, 'policyDetails' => $policyResponse];
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }
}
