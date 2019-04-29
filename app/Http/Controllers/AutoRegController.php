<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AutoRegService;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;

class AutoRegController extends Controller
{
    private $autoRegService;

    public function __construct(AutoRegService $autoRegService)
    {
        $this->autoRegService = $autoRegService;
    }

    /**
    * Return a single role information
    */
    public function show($id)
    {
        try {
            $response = $this->autoRegService->getVehicleDetails($id);
            // $json = json_encode($response);
            return $response;
        } catch(\Exception $ne) {
            Log::error($ne);
        }
    }
}
