<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function save(Request $request)
    {
    //    request()->validate([
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //    ]);
    Log::info($request->file('file'));
    Log::info($request->id);
       if ($files = $request->file('file')) {
           $destinationPath = 'uploads/'; // upload path
           foreach($files as $file) {
            $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileImage);
            $insert['image'] = "$profileImage";
           } 
        }
        // $check = Image::insertGetId($insert);
 
        return response()->json('image successfully uploaded', 200);

    }
}
