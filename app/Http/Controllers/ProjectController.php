<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class ProjectController extends Controller
{

	// public function forgot_password($id)
 //    {
 //        return view('user.profile', ['user' => User::findOrFail($id)]);
 //    }

    public function home()
    {
        return view('welcome');
    }

    public function register()
    {
    	$user_types = \App\UserType::where('user_type', '!=', "Admin")->get();
    	$subjects = \App\Subject::all();

        return view('register', [
        	'user_types' => $user_types, 
        	'subjects' => $subjects
        ]);
    }

    public function forgot_password()
    {
        return view('forgot_password');
    }
}
