<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Jobs\SendMail;

class FilesController extends Controller
{
    public function upload()
    {
    	return view('/upload');
    }

    public function post_upload(Request $request)
    {
        try {
        	if ($request->hasFile('cv')) {
        		$path = $request->file('cv')->store('/');
               return back()->with('success', 'Image uploaded successfully');
           }
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function send_mails()
    {
        $users = User::where('firstname', 'spriha')
            ->orWhere('firstname', 'srivastava')
            ->get();
            
        foreach ($users as $user) {
            SendMail::dispatch($user);
        }
    }
}
