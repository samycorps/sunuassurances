<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Title;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TitleController extends Controller
{
    //
    public function index() {
        return Title::all();
    }
}
