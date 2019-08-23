<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserType;
use App\TeacherSubject;
use Illuminate\Support\Facades\Hash;
class AjaxController extends Controller
{
    public function register(Request $request)
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
		$password = Hash::make($password);
		$usertype = $request->input('user_type');
		$hash = md5(uniqid());
		$msg = "";
		$results = User::where('email', $email)->select('id', 'block_status')->get();
		if (!$results->count()) {

			$results = User::where('username', $username)->select('id')->get();
			if (!$results->count()) {

				$result = UserType::where('user_type', $usertype)->select('id')->get();

				$user = new User;
				$user->firstname = $firstname;
				$user->lastname = $lastname;
				$user->email = $email;
				$user->username = $username;
				$user->password = $password;
				$user->email_verification_code = $hash;
				foreach ($result as $res) {
					$user->user_type_id = $res->id;
				}
				$user->save();

				$results = User::where('username', $username)->select('id')->get();
				if ($request->filled('subject')) {

					$subject = $request->subject;
					$length = count($subject);
					for($i = 0; $i < $length; $i++)
					{
						
						$teacher_subject = new TeacherSubject;
						foreach ($results as $result) {
							$teacher_subject->teacher_id = $result->id;
						}
						
						$teacher_subject->subject_id = $subject[$i];
						$teacher_subject->save();
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

}
