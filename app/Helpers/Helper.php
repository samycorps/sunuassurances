<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Helper {

    public function parseException($exception) {
        Log::info($exception->getCode());
        $errorCode = $exception->getCode();
        $errorObject = array('error' => true, 'code' => 500, 'message' => 'An error occured');
        switch ($errorCode) {
            case 23000: {
                $errorObject = array('error' => true, 'code' => 409, 'message' => $exception->getMessage());
                break;
            }
            default: {
                break;
            }
        }

        return $errorObject;
    }

}