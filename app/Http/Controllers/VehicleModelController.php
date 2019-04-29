<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleModel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class VehicleModelController extends Controller
{
    public function index() {
        return VehicleModel::all();
    } 
}
