<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Artisaninweb\SoapWrapper\SoapWrapper;
// use App\Soap\Request\GetConversionAmount;
// use App\Soap\Response\GetConversionAmountResponse;
use Illuminate\Support\Facades\Log;

class SoapController extends Controller
{
  /**
   * @var SoapWrapper
   */
  protected $soapWrapper;

  /**
   * SoapController constructor.
   *
   * @param SoapWrapper $soapWrapper
   */
  public function __construct(SoapWrapper $soapWrapper)
  {
    $this->soapWrapper = $soapWrapper;
  }


  /**
   * Use the SoapWrapper
   */

  public function demo()
  {
  
  }

  public function autoreg()
  {
  // Add a new service to the wrapper
      SoapWrapper::add('AutoReg', function ($service) {
        $service
        // ->name('AutoReg')
        ->wsdl('http://ws.autoreglive.com/LagService.asmx?WSDL')
        ->trace(true);
      });
    $data = [
      'RegistrationNumber' => '121321312', 
      'Longitude'   => 0, 
      'Latitude'    => 0, 
      'passkey'     => '145LAG7890XP',
    ];
    // Using the added service
    SoapWrapper::service('AutoReg', function ($service) use ($data) {

    var_dump($service->call('WS_LicenseInfoByRegNo', [$data]));
    // var_dump($service->call('Otherfunction'));
    });

    exit;
  }

  public function show() 
  {
    $this->soapWrapper->add('AutoReg', function ($service) {
      $service
        ->wsdl('http://ws.autoreglive.com/LagService.asmx?WSDL')
        ->trace(true)
        // ->classmap([
        //   GetConversionAmount::class,
        //   GetConversionAmountResponse::class,
        // ])
        ;
    });

    // Without classmap
    $response = $this->soapWrapper->call('AutoReg.WS_LicenseInfoByRegNo', [
      'RegistrationNumber' => 'KRD148CV', 
      'Longitude'   => 0, 
      'Latitude'    => 0, 
      'Passkey'     => '1456456AB',
    ]);
    Log::info(json_encode($response, true));
    var_dump($response);

    // // // With classmap
    // // $response = $this->soapWrapper->call('Currency.GetConversionAmount', [
    // //   new GetConversionAmount('USD', 'EUR', '2014-06-05', '1000')
    // // ]);

    // // var_dump($response);
    // exit;
  }
}