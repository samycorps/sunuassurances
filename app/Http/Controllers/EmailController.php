<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Redirect,Response,Config;
use Mail;

class EmailController extends Controller
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function sendEmail(Request $emailData)
    {
        Log::info($emailData);
        $data = array (
            'client_number' => $emailData['client_number'],
            'policy_number' => $emailData['policy_number'],
            'certificate_number' => $emailData['certificate_number'],
            'debit_note_number' => $emailData['debit_note_number'],
            'receipt_number' => $emailData['receipt_number'],
            'expiry_date' => $emailData['expiry_date'],
            'email_address' => $emailData['email_address'],
            'subject' => $emailData['subject']
        );
        Log::info($data);
        try {
 
            Mail::send('email', $data, function($message) use ($data) {
                $message->to($data['email_address'], '')
                        ->from('einsure@sunuassurancesnigeria.com', 'Sunu Assurance Portal')
                        ->subject($data['subject']);
            });
    
            if (Mail::failures()) {
             return response()->Fail(['message' => 'Sorry! Please try again latter']);
            } else{
             return response()->json(['message' => 'Great! Successfully send in your mail']);
            }

        } catch (\Exception $exception) {
            $error = $this->helper->parseException($exception);
            return response()->json($exception->getMessage(), $error['code']);
        }
    }
}
