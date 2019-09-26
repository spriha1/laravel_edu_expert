<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\{User, UserType, TeacherSubject, GoalPlan, Holiday, TeacherRate, Clas, Subject, RequestStatus, Status, Tax, Currency};
use Carbon\Carbon;
use App\Mail\UserVerification;
use App\Mail\UpdateMail;
use App\Http\Requests\Registration;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddGoal;
class AjaxController extends Controller
{
    protected $user_type, $teacher_subject, $holiday, $goal_plan, $user, $teacher_rate, $clas, $request_status, $status, $subject, $tax, $currency;

    public function __construct()
    {
        $this->user            = new User;
        $this->user_type       = new UserType;
        $this->teacher_subject = new TeacherSubject;
        $this->goal_plan       = new GoalPlan;
        $this->holiday         = new Holiday;
        $this->teacher_rate    = new TeacherRate;
        $this->clas            = new Clas;
        $this->subject         = new Subject;
        $this->request_status  = new RequestStatus;
        $this->status          = new Status;
        $this->tax             = new Tax;
        $this->currency        = new Currency;
    }

    /**
    * 
    * @method add_goals() 
    * 
    * @param Request object  
    * @return json 
    * Desc : This method adds a goal for a user and returns the same
    */

    public function add_goals(AddGoal $request)
    {
        $goal_plan = new GoalPlan;
        $from_time = time();
        try {
            $id = $goal_plan->insertGetId([
                'user_id'   => $request->input('user_id'),
                'goal'      => $request->input('goal'),
                'on_date'   => $request->input('on_date'),
                'from_time' => $from_time
            ]);
            $goal_plan = $this->goal_plan->where([
                ['user_id', $request->input('user_id')],
                ['id', $id]
            ])->get();
            return(json_encode($goal_plan));
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method update_goals() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method updates the total time taken by a user to complete a goal and returns the same
    */

    public function update_goals(Request $request)
    {
        $to_time = time();
        try {
            $this->goal_plan
            ->where('id', $request->input('goal_id'))
            ->update([
                'to_time'      => $to_time,
                'check_status' => 1
            ]);
            $goal_plan = $this->goal_plan
                        ->where('id', $request->input('goal_id'))
                        ->select('total_time')
                        ->get();
            return(json_encode($goal_plan));
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method display_goals() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns goals for a particular user on a particular date
    */

    public function display_goals(Request $request)
    {
        try {
            $goal_plan = $this->goal_plan->where([
                ['user_id', $request->input('user_id')],
                ['on_date', $request->input('date')]
            ])->get();
            return(json_encode($goal_plan));
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method remove_goals() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method deletes a goal when removed by the user
    */

    public function remove_goals(Request $request)
    {
        try {
            $this->goal_plan->where('id', $request->input('goal_id'))->delete();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method fetch_info() 
    * 
    * @param Request object
    * @return integer (0 or 1) 
    * Desc : This method checks if a given value is present in the given column
    */

    public function fetch_info(Request $request)
    {
        $search_field       = $request->input('q1');
        $search_field_value = $request->input('q2');
        $res = 0;
        try {
            $results = $this->user
                        ->where($search_field, $search_field_value)
                        ->select($search_field)
                        ->get();
            if ($results->count()) {
                $res = 1;
            }

            return ($res);
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method register() 
    * 
    * @param Request object and Registration request object
    * @return string (message)
    * Desc : This method inserts a record for each user who registers and sends a mail to that user
    */

    public function register(Request $request, Registration $req)
    {
        $validated = $req->validated();
        $firstname = $validated['fname'];
        $lastname  = $validated['lname'];
        $email     = $validated['email'];
        $username  = $validated['username'];
        $password  = $validated['password'];
        $password  = Hash::make($password);
        $usertype  = $validated['user_type'];
        $hash      = md5(uniqid());
        $msg       = "";
        $results = $this->user->where('email', $email)
                    ->select('id', 'block_status')
                    ->get();
        if (!$results->count()) {
            $results = $this->user->where('username', $username)
                        ->select('id')
                        ->get();
            if (!$results->count()) {
                $result = $this->user_type->where('user_type', $usertype)
                            ->select('id')
                            ->get();
                $user                          = new User;
                $user->firstname               = $firstname;
                $user->lastname                = $lastname;
                $user->email                   = $email;
                $user->username                = $username;
                $user->password                = $password;
                $user->email_verification_code = $hash;
                foreach ($result as $res) {
                    $user->user_type_id = $res->id;
                }

                $user->save();
                $results = $this->user->where('username', $username)
                            ->select('id')
                            ->get();
                if ($request->filled('subject')) {
                    $subject = $request->subject;
                    $length  = count($subject);
                    for ($index = 0; $index < $length; $index++) {
                        $teacher_subject = new TeacherSubject;
                        foreach ($results as $result) {
                            $teacher_subject->teacher_id = $result->id;
                        }
                        $teacher_subject->subject_id = $subject[$index];
                        $teacher_subject->save();
                    }
                }
                
                //mail
                Mail::to($email)->send(new UserVerification($request, $hash));
                $msg = "Please verify it by clicking the activation link that has been send to your email.";
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
        return($msg);
    }

    /**
    * 
    * @method update_profile() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method updates the user details
    */

    public function update_profile(Request $request)
    {
        $msg = (object) null;
        if($request->filled('fname')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['firstname' => $request->input('fname')]);
            $msg->success = 1;
        }

        if($request->filled('lname')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['lastname' => $request->input('lname')]);
            $msg->success = 1;
        }

        if($request->filled('date_format')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['date_format' => $request->input('date_format')]);
            $msg->success = 1;
        }

        if($request->filled('password')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['password' => Hash::make($request->input('password'))]);
            $msg->success = 1;
        }

        if($request->filled('currency')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['currency_id' => $request->input('currency')]);
            $msg->success = 1;
        }

        if($request->filled('lat')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['latitude' => $request->input('lat')]);
            $msg->success = 1;
        }

        if($request->filled('long')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['longitude' => $request->input('long')]);
            $msg->success = 1;
        }

        if($request->filled('rate')) {
            $rates = $this->user->where('id', Auth::user()->id)
            ->update(['rate' => $request->input('rate')]);
            $msg->success = 1;
        }

        if($request->filled('address')) {
            $this->user->where('id', Auth::user()->id)
            ->update(['address' => $request->input('address')]);
            $msg->success = 1;
        }

        if($request->filled('tax')) {
            $this->tax->where('name', 'GST')
            ->update(['percentage' => $request->input('tax')]);
            $msg->success = 1;
        }

        if($request->filled('email')) {
            $result = $this->user->where('email', $request->input('email'))
                        ->select('id')
                        ->get();
            if ($result->count()) {
                $msg->email = 0;
            }

            else {
                //mail
                $hash = Auth::user()->email_verification_code;
                $this->user->where('id', Auth::user()->id)->update(['email_verification_status' => 0]);
                Mail::to($request->input('email'))->send(new UpdateMail($hash, $request->input('email')));
                $msg->email = 1;
            }
        }

        if($request->filled('username')) {
            $result = $this->user->where('username', $request->input('username'))
                        ->select('id')
                        ->get();
            if ($result->count()) {
                $msg->username = 0;
            }

            else {
                $this->user->where('id', Auth::user()->id)
                ->update(['username' => $request->input('username')]);
                $msg->success = 1;
            }
        }

        $res = json_encode($msg);
        return($res);
    }

    /**
    * 
    * @method add_holiday() 
    * 
    * @param Request object
    * @return string 
    * Desc : This method adds a particular day or a date range as holiday to prevent displaying tasks for those days
    */

    public function add_holiday(Request $request)
    {
        if ($request->filled('day')) {
            $length = count($request->input('day'));
            for ($index = 0; $index < $length; $index++) {
                try {
                    $test = $this->holiday->insert(['dow' => $request->input('day')[$index]]);
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }

        else if ($request->filled('start_date') && $request->filled('end_date')) {
            $date_format = $request->input('date_format');
            $start_date  = $request->input('start_date');
            $end_date    = $request->input('end_date');
            $start_date  = date_to_timestamp($date_format, $start_date);
            $end_date    = date_to_timestamp($date_format, $end_date);
            try {
                $this->holiday->insert([
                    'start_date' => $start_date, 
                    'end_date'   => $end_date
                ]);
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        return("Added successfully");
    }

    /**
    * 
    * @method fetch_teacher_class() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the class corresponding to a particular teacher
    */

    public function fetch_teacher_class(Request $request)
    {
        try {
            $results = $this->clas->where('teacher_id', $request->input('teacher_id'))
                    ->select('class')
                    ->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return(json_encode($results));
    }

    /**
    * 
    * @method fetch_teacher_class_subjects() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the subjects associated with a particular teacher and class
    */

    public function fetch_teacher_class_subjects(Request $request)
    {
        try {
            $results = $this->clas->join('subjects', 'subjects.id', '=', 'class.subject_id')
                    ->where([
                        ['class', $request->input('class_id')],
                        ['teacher_id', $request->input('teacher_id')]
                    ])->select('subjects.id', 'name')
                    ->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return(json_encode($results));
    }

    /**
    * 
    * @method update_request_status() 
    * 
    * @param Request object
    * @return integer
    * Desc : This method updates the status of the timesheets shared and returns the same
    */

    public function update_request_status(Request $request)
    {
        $check  = 0;
        if ($request->input('status') === 'Approved' || $request->input('status') === 'Rejected') {
            try {
                $result = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                        ->where('users.id', $request->input('user'))
                        ->select('user_type')
                        ->first();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            if ($result['user_type'] === 'Admin') {
                $check = 1;
            }
        }

        if ($request->input('status') === 'Pending') {
            try {
                $result = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                        ->where('users.id', $request->input('user'))
                        ->select('user_type')
                        ->first();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            if ($result['user_type'] === 'Teacher') {
                try {
                    $result = $this->request_status
                    ->join('statuses', 'status_code', '=', 'statuses.id')
                    ->where([
                        ['user_id', $request->input('user_id')],
                        ['week_number', $request->input('week')],
                        ['year', $request->input('year')]
                    ])->select('name')
                    ->first();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                if ($result['name'] !== 'Approved') {
                    $check = 1;
                }
            }
        }

        if ($check === 1) {
            try {
                $statuses = $this->status->where('name', $request->input('status'))
                        ->select('id')
                        ->first();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            $status_code = $statuses['id'];
            try {
                $results = $this->request_status->where([
                    ['user_id', $request->input('user_id')],
                    ['week_number', $request->input('week')],
                    ['year', $request->input('year')]
                ])->select()
                ->get();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            if ($results->count()) {
                try {
                    $this->request_status->where([
                        ['user_id', $request->input('user_id')],
                        ['week_number', $request->input('week')],
                        ['year', $request->input('year')]
                    ])->update(['status_code' => $status_code]);
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            else {
                try {
                    $this->request_status->insert([
                        'user_id'     => $request->input('user_id'),
                        'week_number' => $request->input('week'),
                        'status_code' => $status_code,
                        'year'        => $request->input('year')
                    ]);
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            return $request->input('status');
        }
    }

    /**
    * 
    * @method fetch_request_status() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the status of request for a particular user in a particular week and year
    */

    public function fetch_request_status(Request $request)
    {
        try {
            $results = $this->request_status
            ->join('statuses', 'statuses.id', '=', 'request_status.status_code')
            ->where([
                ['user_id', $request->input('user_id')],
                ['week_number', $request->input('week')],
                ['year', $request->input('year')]
            ])->select('statuses.name')
            ->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return(json_encode($results));
    }
}
