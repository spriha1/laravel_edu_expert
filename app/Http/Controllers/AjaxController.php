<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserType;
use App\TeacherSubject;
use App\GoalPlan;

use Illuminate\Support\Facades\Hash;
class AjaxController extends Controller
{
	public function add_goals(Request $request)
	{
		$goal_plan = new GoalPlan;
		$from_time = time();
		$id = $goal_plan->insertGetId(
			['user_id' => $request->input('user_id'), 'goal' => $request->input('goal'), 'on_date' => $request->input('on_date'), 'from_time' => $from_time]
		);
		$goal_plan = GoalPlan::where([
			['user_id', $request->input('user_id')],
			['id', $id]
		])->get();
		print_r(json_encode($goal_plan));
	}

	public function update_goals(Request $request)
	{
		$to_time = time();
		GoalPlan::where('id', $request->input('goal_id'))->update(['to_time' => $to_time, 'check_status' => 1]);
		$goal_plan = GoalPlan::where('id', $request->input('goal_id'))->select('total_time')->get();
		print_r(json_encode($goal_plan));
	}

	public function display_goals(Request $request)
	{
		$goal_plan = GoalPlan::where([
			['user_id', $request->input('user_id')],
			['on_date', $request->input('date')]
		])->get();
		print_r(json_encode($goal_plan));
	}

	public function remove_goals(Request $request)
	{
		GoalPlan::where('id', $request->input('goal_id'))->delete();
	}

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

    public function update_profile(Request $request)
    {
    	$msg = (object) null;
        if($request->filled('fname')) {
        	User::where('id', session('id'))->update(['firstname' => $request->input('fname')]);
        	$msg->success = 1;
        }
        if($request->filled('lname')) {
        	User::where('id', session('id'))->update(['lastname' => $request->input('lname')]);
        	$msg->success = 1;
        }
        if($request->filled('date_format')) {
        	User::where('id', session('id'))->update(['date_format' => $request->input('date_format')]);
        	$msg->success = 1;
        }
        if($request->filled('password')) {
        	User::where('id', session('id'))->update(['password' => Hash::make($request->input('fname'))]);
        	$msg->success = 1;
        }
        if($request->filled('email')) {
        	$result = User::where('email', $request->input('email'))->select('id')->get();
        	if ($result->count()) {
        		$msg->email = 0;
        	}
        	else {
        		//mail
        	}
        }
        if($request->filled('username')) {
        	$result = User::where('username', $request->input('username'))->select('id')->get();
        	if ($result->count()) {
        		$msg->username = 0;
        	}
        	else {
        		User::where('id', session('id'))->update(['username' => $request->input('username')]);
        		$msg->success = 1;
        	}
        }
        $res = json_encode($msg);
		print_r($res);
    }

}
