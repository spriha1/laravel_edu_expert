<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function ajax_register(Request $request)
    { 

    	Validator::make($request->all(), [
            'fname' => 'required|regex:/^([a-zA-Z]+)$/',
	        'lname' => 'required|regex:/^([a-zA-Z]+)$/',
	        'username' => 'required|regex:/^([a-zA-Z0-9@_]+)$/',
	        'email' => 'required|email',
	        'password' => 'required|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
	        'user_type' => 'required',
        ])->validate();

    	$firstname = $request->input('fname');
		$lastname = $request->input('lname');
		$email = $request->input('email');
		$username = $request->input('username');
		$password = $request->input('password');
		$password = MD5($password);
		$usertype = $request->input('user_type');
		$hash = md5(uniqid());

		$results = \App\User::where('email', $email)->select('id', 'block_status')->get();
		if (!$results) {
			$results = \App\User::where('username', $username)->select('id')->get();
			if (!$results) {
				$result = \App\UserType::where('user_type', $usertype)->select('id')->get();

				$user = new User;
				$user->firstname = $firstname;
				$user->lastname = $lastname;
				$user->email = $email;
				$user->username = $username;
				$user->password = $password;
				$user->email_verification_code = $hash;
				$user->user_type_id = $result->id;
				$user->save();

				$res = \App\User::where('username', $username)->select('id')->get();
				if ($request->filled('subject')) {
					$subject = $request->subject;
					$length = count($subject);
					for($i = 0; $i < $length; $i++)
					{
						$teacher_subject = new TeacherSubject;
						$teacher_subject->teacher_id = $res->id;
						$teacher_subject->subject_id = $subject[$i];
						$request->subject->save();
					}
				}
				//mail
			}
			else {
				$msg = 'Username already exists';
			}
		}
		else {
			foreach ($results as $result) {
				if ($result->block_status == 1) {
					$msg = "This user has been blocked by the admin";
				}
				else {
					$msg = "Email already exists";
				}
			}
		}
    	print_r($msg);
    }

    public function forgot_password()
    {
        return view('forgot_password');
    }
}
