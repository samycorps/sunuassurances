<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('signin');
});

Route::get('/login', function () {
    return view('signin');
});

Route::get('/user-profile', function () {
    return view('user-profile');
});

Route::get('/signup/direct', function () {
    return view('signup', ['role' => 'client']);
});

Route::get('/signup/agent', function () {
    return view('signup', ['role' => 'agent']);
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', function () {
        return view('home');
    });

    Route::get('/motor/client', function () {
        return view('motor');
    });

    Route::get('/motor/agent', function () {
        return view('motor-broker');
    });

    Route::get('/motor/administrator', function () {
        return view('motor-admin');
    });
    
    Route::get('/marine/client', function () {
        return view('marine-client');
    });

    Route::get('/marine/agent', function () {
        return view('marine-agent');
    });

    // Route::get('/motor-policy/{id}', 'VehicleTransactionDetailController@getpolicy');
    Route::get('/motor-policy/{id}', function($id)
    {
        $id = Route::current()->id;

        $policyResponse = App::call('App\Http\Controllers\VehicleTransactionDetailController@getpolicy', ['id' => $id]);
        // $policyResponse = VehicleTransactionDetailController::getpolicy($id);
    
        return view('motor-policy', $policyResponse);
    });

    Route::get('/renew-policy/{id}', function($id)
    {
        $id = Route::current()->id;
        $vehicleDetails = App::call('App\Http\Controllers\VehicleTransactionDetailController@gettransactiondetails', ['id' => $id]);
    
        return view('renew-policy', ['vehicleDetails' => $vehicleDetails]);
    });

    Route::get('/payment/client', function () {
        return view('user-payment');
    });

    Route::get('/payment/agent', function () {
        return view('agent-payment');
    });

    Route::get('/terms', function () {
        return view('terms');
    });

    Route::get('/profile-kyc', function () {
        return view('profile-kyc');
    });

    Route::get('/profile-kyc/agent', function () {
        return view('profile-kyc');
    });

    Route::get('/claims/client', function () {
        return view('client-claims');
    });

    Route::get('/claims/agent', function () {
        return view('agent-claims');
    });

    Route::get('/logout', function () {
        Auth::logout();
        Session::flush();
        return redirect('/');
    });
});
Route::group(['prefix' => 'api'], function() {
    Route::resource('roles', 'RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('colours', 'ColourController', ['only' => ['index']]);
    Route::resource('vehiclebodies', 'VehicleBodyController', ['only' => ['index']]);
    Route::resource('covertypes', 'CoverTypeController', ['only' => ['index']]);
    Route::resource('sectors', 'SectorController', ['only' => ['index']]);
    Route::resource('states', 'StateController', ['only' => ['index']]);
    Route::resource('riskclasses', 'RiskClassController', ['only' => ['index']]);
    Route::resource('banks', 'BankController', ['only' => ['index']]);
    Route::resource('companybanks', 'CompanyBankController', ['only' => ['index']]);
    Route::resource('titles', 'TitleController', ['only' => ['index']]);
    Route::resource('occupations', 'OccupationController', ['only' => ['index']]);
    Route::resource('vehiclemodels', 'VehicleModelController', ['only' => ['index']]);
    Route::resource('cities', 'CityController', ['only' => ['index']]);
    Route::resource('locations', 'LocationController', ['only' => ['index']]);
    Route::resource('users', 'UserController', ['only' => ['store']]);
    Route::resource('profiles', 'ProfileController', ['only' => ['store', 'update']]);
    Route::post('uservalidate', 'UserController@validator');
    Route::resource('currency','SoapController', ['only' => ['show']]);
    Route::resource('autoreg','AutoRegController', ['only' => ['show']]);
    Route::post('getpolicy', 'LegendController@createPolicy');
    Route::post('getadditionalpolicy', 'LegendController@createAdditionalPolicy');
    Route::post('renewpolicy', 'LegendController@renewPolicy');
    Route::post('enquirypolicynumber', 'LegendController@enquiryPolicyNumber');
    Route::resource('vehicletransactiondetails', 'VehicleTransactionDetailController', ['only' => ['store', 'show', 'update']]);
    Route::resource('vehicletransactionpayment', 'VehicleTransactionPaymentController', ['only' => ['store']]);
    Route::resource('vehicletransactionpolicy', 'VehicleTransactionPolicyController', ['only' => ['store']]);
    Route::get('agentclientlist/{user_id}', 'ProfileController@getAgentClientList');
    Route::get('profilelist/{param}', 'ProfileController@getProfileList');
    Route::get('individualpolicylist/{profile_id}', 'VehicleTransactionPolicyController@getIndividualPolicyList');
    Route::get('policylist/{user_id}', 'VehicleTransactionPolicyController@getPoliciesByUserId');
    Route::get('gettransactiondetails/{id}', 'VehicleTransactionDetailController@gettransactiondetails');
    Route::get('gettransactiondetailsbyprofile/{profile_id}', 'VehicleTransactionDetailController@gettransactiondetailsByProfile');
    Route::get('gettransactiondetailsbyregistration/{registration_number}', 'VehicleTransactionDetailController@gettransactiondetailsByRegistrationNumber');
    Route::get('getcovertypes/{type}', 'CoverTypeController@getTypes');
    Route::post('sendemail', 'EmailController@sendEMail');
    Route::post('saveImages/{id}', 'UploadController@save');
    Route::resource('claimdetails', 'VehicleClaimController', ['only' => ['store', 'show', 'update']]);
    Route::get('getclaimdetailsbyprofile/{profile_id}', 'VehicleClaimController@getClaimsByProfile');
    Route::post('changeclaimstatus/{id}', 'VehicleClaimController@changeStatus');
    Route::get('getpoliciesrequestlog/{page}/{limit}/{start_date}/{end_date}/{filter}', 'LegendController@getPoliciesRequestLog');
});
