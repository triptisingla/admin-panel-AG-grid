<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    public function showUploadForm()
    {
        // return "hii";
        // return response()->json(['message'=>'sucess']);
        return view('upload');
    }

    public function storeUploads(Request $request)
    {   

        // dd(Auth::user());
        $response = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();

        dd($response);

        return back()
            ->with('success', 'File uploaded successfully');



        ///////////////////////////////////// on local device
        // $request->file('file')->store('images');

        // return back()->with('success', 'File uploaded successfully');
        /////////////////////////////////////



    }
}
