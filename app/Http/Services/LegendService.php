<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use SoapClient;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LegendService {

    protected $legendParameters = array(
        'url' => 'http://equilegdbg.equityassuranceplc.com:9500/LegWebs/LegWebsPort',
        'username' => 'website',
        'password' => 'website'
    );

    protected $policyLog;

    function __construct() {
        $this->policyLog = new Logger('policy');
        $this->policyLog->pushHandler(new StreamHandler(storage_path('logs/policy.log')), Logger::INFO);
    }

    public function getPolicyNumber($requestData) {
        $requestData['fax_number'] = empty($requestData['fax_number']) ? $requestData['gsm_number'] : $requestData['fax_number'];
        $requestData['vehicle_plate_number'] = empty($requestData['vehicle_plate_number']) ? '12341234' : $requestData['vehicle_plate_number'];
        $soap_request_data = "<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:leg=\"http://legwebs/\">
        <soap:Header/>
        <soap:Body>
           <leg:getpolnum>
           <arg0>{$this->legendParameters['username']}</arg0>
           <arg1>{$this->legendParameters['password']}</arg1>
            <arg2>{$requestData['lastname']}</arg2>
            <arg3>{$requestData['othernames']}</arg3>
            <arg4>{$requestData['address']}</arg4>
            <arg5>{$requestData['city']}</arg5>
            <arg6>{$requestData['contact_person']}</arg6>
            <arg7>{$requestData['state']}</arg7>
            <arg8>{$requestData['title_id']}</arg8>
            <arg9>{$requestData['client_class']}</arg9>
            <arg10>{$requestData['gsm_number']}</arg10>
            <arg11>{$requestData['office_number']}</arg11>
            <arg12>{$requestData['fax_number']}</arg12>
            <arg13>{$requestData['email_address']}</arg13>
            <arg14>{$requestData['website']}</arg14>
            <arg15>{$requestData['company_reg_num']}</arg15>
            <arg16>{$requestData['date_of_birth']}</arg16>
            <arg17>{$requestData['lga']}</arg17>
            <arg18>{$requestData['tin_number']}</arg18>
            <arg19>{$requestData['bvn']}</arg19>
            <arg20>{$requestData['bank_id']}</arg20>
            <arg21>{$requestData['account_number']}</arg21>
            <arg22>{$requestData['occupation']}</arg22>
            <arg23>{$requestData['sector']}</arg23>
            <arg24>{$requestData['premium']}</arg24>
            <arg25>{$requestData['sum_insured']}</arg25>
            <arg26>{$requestData['vehicle_plate_number']}</arg26>
            <arg27>{$requestData['state']}</arg27>
            <arg28>{$requestData['model']}</arg28>
            <arg29>{$requestData['body']}</arg29>
            <arg30>{$requestData['color']}</arg30>
            <arg31>{$requestData['cubic_capacity']}</arg31>
            <arg32>{$requestData['number_of_seat']}</arg32>
            <arg33>{$requestData['engine_number']}</arg33>
            <arg34>{$requestData['chasis_number']}</arg34>
            <arg35>{$requestData['year_of_make']}</arg35>
            <arg36>{$requestData['year_of_purchase']}</arg36>
            <arg37>{$requestData['mode_of_payment']}</arg37>
            <arg38></arg38>
            <arg39>{$requestData['policy_class']}</arg39>
            <arg40>{$requestData['risk_class']}</arg40>
            <arg41>{$requestData['cover_type']}</arg41>
            <arg42>{$requestData['basic_rate']}</arg42>
            <arg43>{$requestData['location']}</arg43>
            <arg44></arg44>
            <arg45></arg45>
            <arg46></arg46>
            <arg47></arg47>
            <arg48>{$requestData['currency']}</arg48>
            <arg49></arg49>
            <arg50></arg50>
            <arg51>0</arg51>
            <arg52></arg52>
            <arg53></arg53>
            <arg54></arg54>
            <arg55></arg55>
            <arg56></arg56>
            <arg57></arg57>
            <arg58></arg58>
            <arg59>{$requestData['company_bank']}</arg59>
            <arg60>{$requestData['effective_date']}</arg60>
            <arg61>{$requestData['expiry_date']}</arg61>
        </leg:getpolnum>
        </soap:Body>
     </soap:Envelope>";
     $legend_url = $this->legendParameters['url'];
     $this->policyLog->info('Legend Get Policy Number', array('data' => $soap_request_data));
     $curl = curl_init();
     curl_setopt_array($curl, array(
        CURLOPT_PORT => "9500",
        CURLOPT_URL => $legend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $soap_request_data,
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/soap+xml;charset=UTF-8",
          "cache-control: no-cache"
        ),
      ));
      
      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        return $err;
        } else {
            $posStart = strpos($response, '<return>');
            Log::info($posStart);
            $posEnd = strpos($response, '</return>');
            Log::info($posEnd);
            $returnedValue = substr($response, $posStart+8, $posEnd - ($posStart+8));
            Log::info($returnedValue);
            $this->policyLog->info('Legend Policy Response ', array('response' => $returnedValue));
            return $returnedValue;
        }
    }

    public function getMarinePolicyNumber($requestData) {
        $requestData['fax_number'] = empty($requestData['fax_number']) ? $requestData['gsm_number'] : $requestData['fax_number'];
        $requestData['vehicle_plate_number'] = empty($requestData['vehicle_plate_number']) ? '12341234' : $requestData['vehicle_plate_number'];
        $soap_request_data = "<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:leg=\"http://legwebs/\">
        <soap:Header/>
        <soap:Body>
           <leg:getpolnum>
           <arg0>{$this->legendParameters['username']}</arg0>
           <arg1>{$this->legendParameters['password']}</arg1>
            <arg2>{$requestData['lastname']}</arg2>
            <arg3>{$requestData['othernames']}</arg3>
            <arg4>{$requestData['address']}</arg4>
            <arg5>{$requestData['city']}</arg5>
            <arg6>{$requestData['contact_person']}</arg6>
            <arg7>{$requestData['state']}</arg7>
            <arg8>{$requestData['title_id']}</arg8>
            <arg9>{$requestData['client_class']}</arg9>
            <arg10>{$requestData['gsm_number']}</arg10>
            <arg11>{$requestData['office_number']}</arg11>
            <arg12>{$requestData['fax_number']}</arg12>
            <arg13>{$requestData['email_address']}</arg13>
            <arg14>{$requestData['website']}</arg14>
            <arg15>{$requestData['company_reg_num']}</arg15>
            <arg16>{$requestData['date_of_birth']}</arg16>
            <arg17>{$requestData['lga']}</arg17>
            <arg18>{$requestData['tin_number']}</arg18>
            <arg19>{$requestData['bvn']}</arg19>
            <arg20>{$requestData['bank_id']}</arg20>
            <arg21>{$requestData['account_number']}</arg21>
            <arg22>{$requestData['occupation']}</arg22>
            <arg23>{$requestData['sector']}</arg23>
            <arg24>{$requestData['premium']}</arg24>
            <arg25>{$requestData['sum_insured']}</arg25>
            <arg26>{$requestData['vehicle_plate_number']}</arg26>
            <arg27>{$requestData['state']}</arg27>
            <arg28>{$requestData['model']}</arg28>
            <arg29>{$requestData['body']}</arg29>
            <arg30>{$requestData['color']}</arg30>
            <arg31>{$requestData['cubic_capacity']}</arg31>
            <arg32>{$requestData['number_of_seat']}</arg32>
            <arg33>{$requestData['engine_number']}</arg33>
            <arg34>{$requestData['chasis_number']}</arg34>
            <arg35>{$requestData['year_of_make']}</arg35>
            <arg36>{$requestData['year_of_purchase']}</arg36>
            <arg37>{$requestData['mode_of_payment']}</arg37>
            <arg38></arg38>
            <arg39>{$requestData['policy_class']}</arg39>
            <arg40>{$requestData['risk_class']}</arg40>
            <arg41>{$requestData['cover_type']}</arg41>
            <arg42>{$requestData['basic_rate']}</arg42>
            <arg43>{$requestData['location']}</arg43>
            <arg44></arg44>
            <arg45></arg45>
            <arg46></arg46>
            <arg47></arg47>
            <arg48>{$requestData['currency']}</arg48>
            <arg49>{$requestData['voyage_from']}</arg49>
            <arg50>{$requestData['voyage_to']}</arg50>
            <arg51>0</arg51>
            <arg52>{$requestData['packing_type']}</arg52>
            <arg53>{$requestData['vessel_name']}</arg53>
            <arg54>{$requestData['conditions']}</arg54>
            <arg55>{$requestData['excess']}</arg55>
            <arg56>{$requestData['conveyance']}</arg56>
            <arg57>{$requestData['description']}</arg57>
            <arg58>{$requestData['term_of_insurance']}</arg58>
            <arg59>{$requestData['company_bank']}</arg59>
            <arg60>{$requestData['effective_date']}</arg60>
            <arg61>{$requestData['expiry_date']}</arg61>
        </leg:getpolnum>
        </soap:Body>
     </soap:Envelope>";
     Log::info($soap_request_data);
     $this->policyLog->info('Legend Marine Policy Number ', array('data' => $soap_request_data));
     $legend_url = $this->legendParameters['url'];
     $curl = curl_init();
     curl_setopt_array($curl, array(
        CURLOPT_PORT => "9500",
        CURLOPT_URL => $legend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $soap_request_data,
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/soap+xml;charset=UTF-8",
          "cache-control: no-cache"
        ),
      ));
      
      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      curl_close($curl);
      
        if ($err) {
        echo "cURL Error #:" . $err;
        return $err;
        } else {
            $posStart = strpos($response, '<return>');
            Log::info($posStart);
            $posEnd = strpos($response, '</return>');
            Log::info($posEnd);
            $returnedValue = substr($response, $posStart+8, $posEnd - ($posStart+8));
            Log::info($returnedValue);
            $this->policyLog->info('Legend Policy Response ', array('response' => $response));
            return $returnedValue;
        }
    }

    public function enquiryPolicyNumber2($requestData) {
        $client = new SoapClient("http://equilegdbg.equityassuranceplc.com:9500/LegWebs/LegWebsPort?WSDL", array('soap_version'   => SOAP_1_2));
    }

    public function enquiryPolicyNumber($requestData) {
        $soap_request_data = "
        <soap:Envelope xmlns:soap='http://www.w3.org/2003/05/soap-envelope' xmlns:leg='http://legwebs/'>
        <soap:Header/>
        <soap:Body>
           <leg:enquirypol>
              <arg0>{$this->legendParameters['username']}</arg0>
              <arg1>{$this->legendParameters['password']}</arg1>
              <arg2>{$requestData['policyNumber']}</arg2>
              <arg3>{$requestData['registrationNumber']}</arg3>
           </leg:enquirypol>
        </soap:Body>
     </soap:Envelope>";

    $legend_url = $this->legendParameters['url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "9500",
        CURLOPT_URL => $legend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $soap_request_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/soap+xml;charset=UTF-8",
            "cache-control: no-cache"
        ),
    ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        if ($err) {
            Log::error($err);
            return $err;
        } else {
            $posStart = strpos($response, '<return>');
            Log::info($posStart);
            $posEnd = strpos($response, '</return>');
            Log::info($posEnd);
            $returnedValue = substr($response, $posStart+8, $posEnd - ($posStart+8));
            Log::info($returnedValue);
            return $returnedValue;
        }
    }

    public function additionalPolicy($requestData) {
        $soap_request_data = "
            <soap:Envelope xmlns:soap='http://www.w3.org/2003/05/soap-envelope' xmlns:leg='http://legwebs/'>
            <soap:Header/>
            <soap:Body>
                <leg:addschedule>
                <arg0>{$this->legendParameters['username']}</arg0>
                <arg1>{$this->legendParameters['password']}</arg1>
                <arg2>{$requestData['policy_number']}</arg2>
                <arg3>{$requestData['premium']}</arg3>
                <arg4>0</arg4>
                <arg5>{$requestData['vehicle_plate_number']}</arg5>
                <arg6>{$requestData['state']}</arg6>
                <arg7>{$requestData['model']}</arg7>
                <arg8>{$requestData['body']}</arg8>
                <arg9>{$requestData['color']}</arg9>
                <arg10>{$requestData['cubic_capacity']}</arg10>
                <arg11>{$requestData['number_of_seat']}</arg11>
                <arg12>{$requestData['engine_number']}</arg12>
                <arg13>{$requestData['chasis_number']}</arg13>
                <arg14>{$requestData['year_of_make']}</arg14>
                <arg15>{$requestData['year_of_purchase']}</arg15>
                <arg16>{$requestData['mode_of_payment']}</arg16>
                <arg17></arg17>
                <arg18>{$requestData['cover_type']}</arg18>
                <arg19>0.0</arg19>
                <arg20></arg20>
                <arg21></arg21>
                <arg22></arg22>
                <arg23></arg23>
                <arg24>{$requestData['currency']}</arg24>
                <arg25></arg25>
                <arg26></arg26>
                <arg27></arg27>
                <arg28></arg28>
                <arg29></arg29>
                <arg30></arg30>
                <arg31></arg31>
                <arg32></arg32>
                <arg33></arg33>
                <arg34></arg34>
                <arg35>{$requestData['company_bank']}</arg35>
                <arg36>{$requestData['effective_date']}</arg36>
                </leg:addschedule>
            </soap:Body>
            </soap:Envelope>
            ";
    $legend_url = $this->legendParameters['url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "9500",
        CURLOPT_URL => $legend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $soap_request_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/soap+xml;charset=UTF-8",
            "cache-control: no-cache"
        ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        if ($err) {
            Log::error($err);
            return $err;
        } else {
            $posStart = strpos($response, '<return>');
            Log::info($posStart);
            $posEnd = strpos($response, '</return>');
            Log::info($posEnd);
            $returnedValue = substr($response, $posStart+8, $posEnd - ($posStart+8));
            Log::info($returnedValue);
            return $returnedValue;
        }
    }

    public function renewPolicy($requestData) {
        $soap_request_data = "
            <soap:Envelope xmlns:soap='http://www.w3.org/2003/05/soap-envelope' xmlns:leg='http://legwebs/'>
            <soap:Header/>
            <soap:Body>
                <leg:renewpolicy>
                <arg0>{$this->legendParameters['username']}</arg0>
                <arg1>{$this->legendParameters['password']}</arg1>
                <arg2>{$requestData['policy_number']}</arg2>
                <arg3>{$requestData['premium']}</arg3>
                <arg4>0</arg4>
                <arg5>{$requestData['vehicle_plate_number']}</arg5>
                <arg6>{$requestData['state']}</arg6>
                <arg7>{$requestData['model']}</arg7>
                <arg8>{$requestData['body']}</arg8>
                <arg9>{$requestData['color']}</arg9>
                <arg10>{$requestData['cubic_capacity']}</arg10>
                <arg11>{$requestData['number_of_seat']}</arg11>
                <arg12>{$requestData['engine_number']}</arg12>
                <arg13>{$requestData['chasis_number']}</arg13>
                <arg14>{$requestData['year_of_make']}</arg14>
                <arg15>{$requestData['year_of_purchase']}</arg15>
                <arg16>{$requestData['mode_of_payment']}</arg16>
                <arg17></arg17>
                <arg18>{$requestData['agent_name']}</arg18>
                <arg19></arg19>
                <arg20></arg20>
                <arg21>{$requestData['currency']}</arg21>
                <arg22>{$requestData['company_bank']}</arg22>
                <arg23>{$requestData['effective_date']}</arg23>
                <arg24>{$requestData['expiry_date']}</arg24>
                </leg:renewpolicy>
            </soap:Body>
            </soap:Envelope>
            ";
    Log::info($soap_request_data);
    $legend_url = $this->legendParameters['url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "9500",
        CURLOPT_URL => $legend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $soap_request_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/soap+xml;charset=UTF-8",
            "cache-control: no-cache"
        ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        if ($err) {
            Log::error($err);
            return $err;
        } else {
            $posStart = strpos($response, '<return>');
            Log::info($posStart);
            $posEnd = strpos($response, '</return>');
            Log::info($posEnd);
            $returnedValue = substr($response, $posStart+8, $posEnd - ($posStart+8));
            Log::info($returnedValue);
            return $returnedValue;
        }
    }

    private function xmlToArray($xml, $options = array()) {
        $defaults = array(
            'namespaceSeparator' => ':',//you may want this to be something other than a colon
            'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
            'alwaysArray' => array(),   //array of xml tag names which should always become arrays
            'autoArray' => true,        //only create arrays for tags which appear more than once
            'textContent' => '$',       //key used for the text content of elements
            'autoText' => true,         //skip textContent key if node has no attributes or child nodes
            'keySearch' => false,       //optional search and replace on tag and attribute names
            'keyReplace' => false       //replace values for above search values (as passed to str_replace())
        );
        $options = array_merge($defaults, $options);
        $namespaces = $xml->getDocNamespaces();
        $namespaces[''] = null; //add base (empty) namespace
     
        //get attributes from all namespaces
        $attributesArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
                //replace characters in attribute name
                if ($options['keySearch']) $attributeName =
                        str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                        . $attributeName;
                $attributesArray[$attributeKey] = (string)$attribute;
            }
        }
     
        //get child nodes from all namespaces
        $tagsArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childXml) {
                //recurse into child nodes
                $childArray = $this->xmlToArray($childXml, $options);
                list($childTagName, $childProperties) = each($childArray);
     
                //replace characters in tag name
                if ($options['keySearch']) $childTagName =
                        str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
     
                if (!isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] =
                            in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                            ? array($childProperties) : $childProperties;
                } elseif (
                    is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                    === range(0, count($tagsArray[$childTagName]) - 1)
                ) {
                    //key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                } else {
                    //key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }
     
        //get text content of node
        $textContentArray = array();
        $plainText = trim((string)$xml);
        if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
     
        //stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
                ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
     
        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
    }

}