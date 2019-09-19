<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function upload()
    {
    	return view('/upload');
    }

    public function post_upload(Request $request)
    {
    	if ($request->hasFile('cv')) {
    		$path = $request->file('cv')->store(
			    '/', 's3'
			);
       }
       return back()->with('success', 'Image uploaded successfully');
    }
}
