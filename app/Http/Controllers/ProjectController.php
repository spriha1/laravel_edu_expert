<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_type_id' => 1])) {
            return redirect('admin_dashboard');
        }
        else if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_type_id' => 2])) {
            return redirect('teacher_dashboard');
        }
        else if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_type_id' => 3])) {
            return redirect('student_dashboard');
        }
        else {
            return redirect('welcome');
        }
    }

    public function admin_dashboard()
    {
        return view('admin_dashboard');
    }

    public function teacher_dashboard()
    {
        return view('teacher_dashboard');
    }

    public function student_dashboard()
    {
        return view('student_dashboard');
    }
}
