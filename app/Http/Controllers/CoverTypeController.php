<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoverType;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class CoverTypeController extends Controller
{
    public function index() {
        return CoverType::all();
    }
    
    public function getTypes($type) {
        return CoverType::where('insurance_type', $type)->get();
    } 
}
