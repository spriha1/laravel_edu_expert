<?php

namespace App\Http\Controllers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\{User, UserType, Subject, Clas, Task, TeacherTask, StudentTask, TeacherRate, SharedTimesheet, Tax, Currency};
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateMail;
use App\Mail\ForgotPassword;
use DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProjectController extends Controller
{
    protected $user_type, $teacher_subject, $holiday, $goal_plan, $user, $teacher_rate, $tax, $currency;

    public function __construct()
    {
        $this->user             = new User;
        $this->user_type        = new UserType;
        $this->subject          = new Subject;
        $this->clas             = new Clas;
        $this->task             = new Task;
        $this->teacher_task     = new TeacherTask;
        $this->student_task     = new StudentTask;
        $this->shared_timesheet = new SharedTimesheet;
        $this->teacher_rate     = new TeacherRate;
        $this->tax              = new Tax;
        $this->currency         = new Currency;
    }

    /**
    * 
    * @method home() 
    * 
    * @param void
    * @return string [html view of login page] 
    * Desc : This method returns the login page
    */

    public function home()
    {
        return view('welcome');
    }

    /**
    * 
    * @method register() 
    * 
    * @param void
    * @return string [html view of registration page]  
    * Desc : This method returns the registration page
    */

    public function register()
    {
        try {
            $user_types = $this->user_type->where('user_type', '!=', "Admin")->get();
            $subjects   = $this->subject->all();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('register', [
            'user_types' => $user_types, 
            'subjects'   => $subjects
        ]);
    }

    /**
    * 
    * @method forgot_password() 
    * 
    * @param void
    * @return string [html view of forgot_password page] 
    * Desc : This method returns the forgot_password page
    */

    public function forgot_password()
    {
        return view('forgot_password');
    }

    /**
    * 
    * @method login() 
    * 
    * @param Request object
    * @return string [html view of dashboard]
    * Desc : This method authenticates a user and redirects to the respective dashboard
    */

    public function login(Request $request)
    {
        if (Auth::attempt([
                'username'        => $request->username, 
                'password'        => $request->password, 
                'user_reg_status' => 1, 
                'block_status'    => 0
            ])) {
                    
            try {
                $user_type = $this->user_type->where('id', Auth::user()->user_type_id)->get();
            }      
            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            foreach ($user_type as $val) {                    
                if ($val->user_type === 'Admin') {                        
                    return redirect('admin_dashboard');
                }
                else if ($val->user_type === 'Teacher') {
                    return redirect('teacher_dashboard');
                }
                else if ($val->user_type === 'Student') {
                    return redirect('student_dashboard');
                }
            }
        }
        else {
            $errors = new MessageBag(['password' => ['Username and/or password invalid.']]);
            return redirect()->back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }

    /**
    * 
    * @method pending_requests() 
    * 
    * @param void
    * @return string [html view of pending_requests page] 
    * Desc : This method returns the pending_requests page
    */

    public function pending_requests()
    {
        try {
            $user_types = $this->user_type->where('user_type', '!=', 'Admin')
                        ->select('user_type')
                        ->get();

            $results = $this->user->whereRaw("user_reg_status = 0 AND email_verification_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")
            ->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')
            ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('pending_requests', [
            'user_types' => $user_types,
            'results'    => $results,
            'search'     => ""
        ]);
    }

    /**
    * 
    * @method post_pending_requests() 
    * 
    * @param Request object
    * @return string [html view of pending_requests page] 
    * Desc : This method fetches the users of a specific type with pending requests and returns the pending_requests page
    */

    public function post_pending_requests(Request $request)
    {
        try {
            $user_types = $this->user_type->where('user_type', '!=', 'Admin')
                        ->select('user_type')
                        ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $c = 0;
        foreach ($user_types as $key => $value) {
            if ($value['user_type'] === $request->input('user_type')) {
                $c++;
            }
        }
        if ($c > 0) {
            try {
                $results = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where([
                    ['user_reg_status', 0], 
                    ['user_type', $request->input('user_type')]
                ])->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')
                ->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        else {
            try {
                $results = $this->user->whereRaw("user_reg_status = 0 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")
                ->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')
                ->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        return view('pending_requests', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => $request->input('user_type')
        ]);
    }

    /**
    * 
    * @method add_users() 
    * 
    * @param integer (id of the user to be added)
    * @return view of the calling page 
    * Desc : This method adds a user to the system when the admin accepts their pending request
    */

    public function add_users($id)
    {
        try {
            $this->user->where('id', $id)->update(['user_reg_status' => 1]);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back();
    }

    /**
    * 
    * @method remove_users() 
    * 
    * @param integer (id of the user to be removed)
    * @return view of the calling page 
    * Desc : This method removes a user from the system
    */

    public function remove_users($id)
    {
        try {
            $this->user->where('id', $id)->delete();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back();
    }

    /**
    * 
    * @method block_users() 
    * 
    * @param integer (id of the user to be blocked)
    * @return view of the calling page 
    * Desc : This method blocks a user in the system
    */

    public function block_users($id)
    {
        try {
            $this->user->where('id', $id)->update(['block_status' => 1]);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back();
    }

    /**
    * 
    * @method unblock_users() 
    * 
    * @param integer (id of the user to be unblocked)
    * @return view of the calling page 
    * Desc : This method unblocks a user in the system
    */

    public function unblock_users($id)
    {
        try {
            $this->user->where('id', $id)->update(['block_status' => 0]);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back();
    }

    /**
    * 
    * @method regd_users() 
    * 
    * @param void
    * @return string [html view of regd_users page] 
    * Desc : This method returns the regd_users page
    */

    public function regd_users()
    {
        try {
            $user_types = $this->user_type->where('user_type', '!=', 'Admin')
                        ->select('user_type')
                        ->get();

            $results = $this->user->whereRaw("user_reg_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")
            ->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')
            ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('regd_users', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => ""
        ]);
    }

    /**
    * 
    * @method post_regd_users() 
    * 
    * @param Request object
    * @return string [html view of regd_users page] 
    * Desc : This method fetches the registered users of a specific type and returns the regd_users page
    */

    public function post_regd_users(Request $request)
    {
        try {
            $user_types = $this->user_type->where('user_type', '!=', 'Admin')
                        ->select('user_type')
                        ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $c = 0;

        foreach ($user_types as $key => $value) {
            if ($value['user_type'] === $request->input('user_type')) {
                $c++;
            }
        }

        if ($c > 0) {
            try {
                $results = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where([
                    ['user_reg_status', 1], 
                    ['user_type', $request->input('user_type')]
                ])->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')
                ->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        else {
            try {
                $results = $this->user->whereRaw("user_reg_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")
                ->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')
                ->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        return view('regd_users', [
            'user_types' => $user_types,
            'results'    => $results,
            'search'     => $request->input('user_type')
        ]);
    }

    /**
    * 
    * @method render_admin_dashboard() 
    * 
    * @param void
    * @return string [html view of admin dashboard] 
    * Desc : This method fetches the information required on dashboard and returns the admin dashboard
    */

    public function render_admin_dashboard()
    {
        try {
            $regd_users = $this->user->where('user_reg_status', 1)
                        ->selectRaw('count(*) as total')
                        ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        foreach ($regd_users as $regd_user) {
            $regd_user_count = $regd_user->total;
        }

        try {
            $pending_users = $this->user->where([
                ['user_reg_status', 0],
                ['email_verification_status', 1]
            ])->selectRaw('count(*) as total')
            ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        foreach ($pending_users as $pending_user) {
            $pending_user_count = $pending_user->total;
        }

        try {
            $shared_timesheets = $this->shared_timesheet->where('to_id', Auth::id())
                                ->selectRaw('count(*) as total')
                                ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        foreach ($shared_timesheets as $shared_timesheet) {
            $shared_timesheet_count = $shared_timesheet->total;
        }

        return view('admin_dashboard', [
            'regd_user_count' => $regd_user_count,
            'pending_user_count' => $pending_user_count,
            'shared_timesheet_count' => $shared_timesheet_count
        ]);
    }

    /**
    * 
    * @method render_teacher_dashboard() 
    * 
    * @param void
    * @return string [html view of teacher dashboard] 
    * Desc : This method fetches the information required on dashboard and returns the teacher dashboard
    */

    public function render_teacher_dashboard()
    {
        return view('teacher_dashboard');
    }

    /**
    * 
    * @method render_student_dashboard() 
    * 
    * @param void
    * @return string [html view of student dashboard] 
    * Desc : This method fetches the information required on dashboard and returns the student dashboard
    */

    public function render_student_dashboard()
    {
        return view('student_dashboard');
    }

    /**
    * 
    * @method profile() 
    * 
    * @param void
    * @return string [html view of profile page] 
    * Desc : This method fetches the information required on profile page and returns its view
    */

    public function profile()
    {
        try {
            $usertypes = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->where('users.id', Auth::id())
            ->select('user_type')->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        foreach ($usertypes as $usertype) {

            if ($usertype->user_type === 'Teacher') {
                try {
                    $rates = $this->user->where('id', Auth::id())
                            ->select('rate')
                            ->get();

                    $currencies = $this->currency->get();
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                return view('profile', [
                    'usertype' => $usertype->user_type,
                    'rates' => $rates,
                    'currencies' => $currencies
                ]);
            }

            else if ($usertype->user_type === 'Admin') {
                try {
                    $tax = $this->tax->where('name', 'GST')
                        ->select('percentage')
                        ->first();

                    $currencies = $this->currency->get();
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
                

                return view('profile', [
                    'usertype' => $usertype->user_type,
                    'tax' => $tax['percentage'],
                    'currencies' => $currencies
                ]);
            }

            else {
                return view('profile', [
                    'usertype' => $usertype->user_type
                ]);
            }
        }
    }

    /**
    * 
    * @method verify_mail() 
    * 
    * @param string (email verification code)
    * Desc : This method verifies the user's email id
    */

    public function verify_mail($code)
    {
        $hash = base64_decode($code);
        try {
            $results = $this->user->where([
                ['email_verification_code', $hash],
                ['email_verification_status', 0]
            ])->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        if($results->count()) {
            try {
                $this->user->where([
                    ['email_verification_code', $hash],
                    ['email_verification_status', 0]
                ])->update(['email_verification_status' => 1]);
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
            echo '<div>Your account has been activated, you can now <a href="/">login</a></div>';
        }
        else {
            echo '<div>The url is either invalid or you already have activated your account.</div>';
        }
    }

    /**
    * 
    * @method update_mail() 
    * 
    * @param string (verification code and email id)
    * Desc : This method verifies the user's mail on update request
    */

    public function update_mail($hash, $email)
    {
        $hash = base64_decode($hash);
        $email = base64_decode($email);
        try {
            $result = $this->user->where([
                ['email_verification_code', $hash],
                ['email_verification_status', 0]
            ])->select('id')->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        if($result->count()) {
            foreach ($result as $res) {
                try {
                    $this->user->where([
                        ['id', $res->id],
                        ['email_verification_status', 0]
                    ])->update(['email_verification_status' => 1, 'email' => $email]);
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
            echo '<div>Your email has been updated, you can continue';
        }
        else {
            echo '<div>The url is either invalid or you already have activated your account.</div>';
        }
    }

    /**
    * 
    * @method send_password_mail() 
    * 
    * @param Request object
    * Desc : This method sends a mail to the user to reset password
    */

    public function send_password_mail(Request $request)
    {
        if($request->filled('username')) {
            try {
                $result = $this->user->where('username', $request->input('username'))
                        ->select('id')
                        ->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            if($result->count()) {
                $unique = uniqid();
                try {
                    $this->user->where('username', $request->input('username'))
                    ->update(['token' => $unique]);

                    $results = $this->user->where('username', $request->input('username'))
                            ->select('email', 'token')
                            ->get();
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                foreach ($results as $result) {
                    try {
                        Mail::to($result->email)->send(new ForgotPassword($result->token));
                    }
                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    echo "Please reset your password by clicking the link that has been sent to your email.";
                }
            }
        }
    }

    /**
    * 
    * @method reset_password_form() 
    * 
    * @param string (token to identify the user), integer (expiry time of the link)
    * @return string [html view of password reset form page] 
    * Desc : This method returns the view of password reset form page
    */

    public function reset_password_form($token, $expiry_time)
    {
        $token = base64_decode($token);
        session(['token' => $token]);
        $expiry_time = base64_decode($expiry_time);
        $current_time = time();
        if ($current_time > $expiry_time) {
            try {
                $this->user->where('token', $token)->update(['token' => NULL]);
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            echo "The link has expired";
        }
        else {
            return view('reset_password_form');
        }
    }

    /**
    * 
    * @method reset_password() 
    * 
    * @param Request object
    * Desc : This method resets the password
    */

    public function reset_password(Request $request)
    {
        if($request->filled('password')) {
            try {
                $result = $this->user->where([
                    ['token', session('token')],
                    ['user_reg_status', 1]
                ])->select('username')->get();
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            if($result->count()) {
                $password = Hash::make($request->input('password'));
                try{
                    $this->user->where('token', session('token'))->update(['password' => $password]);
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                echo '<div>Your password has been reset, you can now <a href="/"> login</a></div>';
            }
            else {
                echo '<div>Your request has not been accepted by the admin yet</div>';
            }
        }
    }

    /**
    * 
    * @method task_management() 
    * 
    * @param void
    * @return string [html view of task_management page]
    * Desc : This method returns the view of task_management page
    */

    public function task_management()
    {
        try {
            $teachers = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                        ->where('user_type', 'Teacher')
                        ->select('firstname', 'users.id')
                        ->get();

            $classes = $this->clas->select('class')->distinct()->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('task_management', [
            'classes' => $classes,
            'teachers' => $teachers
        ]);
    }

    /**
    * 
    * @method add_timetable() 
    * 
    * @param Request object
    * @return string 
    * Desc : This method adds task for users if it has not been added earlier
    */

    public function add_timetable(Request $request)
    {
        if ($request->filled('class') && $request->filled('subject') && $request->filled('start_date') && $request->filled('end_date')) {
            $date_format = $request->input('date_format');
            $start_date  = $request->input('start_date');
            $end_date    = $request->input('end_date');
            switch ($date_format) {
                case "yyyy/mm/dd":
                    $start_date = Carbon::createFromFormat("Y/m/d" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("Y/m/d" , $end_date)->timestamp;
                    break;
                case "yyyy.mm.dd":
                    $start_date = Carbon::createFromFormat("Y.m.d" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("Y.m.d" , $end_date)->timestamp;
                    break;
                case "yyyy-mm-dd":
                    $start_date = Carbon::createFromFormat("Y-m-d" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("Y-m-d" , $end_date)->timestamp;
                    break;
                case "dd/mm/yyyy":
                    $start_date = Carbon::createFromFormat("d/m/Y" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("d/m/Y" , $end_date)->timestamp;
                    break;
                case "dd-mm-yyyy":
                    $start_date = Carbon::createFromFormat("d-m-Y" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("d-m-Y" , $end_date)->timestamp;
                    break;
                case "dd.mm.yyyy":
                    $start_date = Carbon::createFromFormat("d.m.Y" , $start_date)->timestamp;
                    $end_date   = Carbon::createFromFormat("d.m.Y" , $end_date)->timestamp;
                    break;
            }
            
            $start_date = getdate($start_date);
            $start_date = $start_date['year'].'-'.$start_date['mon'].'-'.$start_date['mday'];
            $start_date = strtotime($start_date);

            $end_date   = getdate($end_date);
            $end_date   = $end_date['year'].'-'.$end_date['mon'].'-'.$end_date['mday'];
            $end_date   = strtotime($end_date);
            $length     = count($request->input('subject'));

            try {
                for ($i = 0; $i < $length; $i++) {
                    $subject_id = $request->input('subject')[$i];
                    for($a = $start_date; $a <= $end_date; $a = $a + 86400) {
                        $result = $this->task->where([
                            ['subject_id', $subject_id],
                            ['class', $request->input('class')],
                            ['start_date', '<=', $a],
                            ['end_date', '>=', $a]
                        ])->select()->get();

                        if ($result->count()) {
                            return("The task has already been added fro these dates");
                        }

                        else {
                            DB::beginTransaction();
                            $task_id = $this->task->insertGetId([
                                'subject_id' => $subject_id,
                                'class' => $request->input('class'),
                                'start_date' => $start_date,
                                'end_date' => $end_date
                            ]);

                            $result = $this->clas->where([
                                ['class', $request->input('class')],
                                ['subject_id', $subject_id]
                            ])->select('teacher_id')
                            ->distinct()
                            ->get();

                            foreach ($result as $key => $value) {
                                for ($z = $start_date; $z <= $end_date; $z = $z + 86400) {
                                    $this->teacher_task->insert([
                                        'task_id' => $task_id,
                                        'teacher_id' => $value['teacher_id'],
                                        'on_date' => $z
                                    ]);
                                }
                            }

                            $result = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')->where([
                                ['user_type', 'Student'],
                                ['class', $request->class]
                            ])->select('users.id')
                            ->get();

                            foreach ($result as $key => $value) {
                                for ($z = $start_date; $z <= $end_date; $z = $z + 86400) {
                                    $this->student_task->insert([
                                        'task_id' => $task_id,
                                        'student_id' => $value['id'],
                                        'on_date' => $z
                                    ]);
                                }
                            }
                            return("Successfully added");
                            DB::commit();
                        }
                        
                    }
                }
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
                DB::rollBack();
            }
        }
    }

    /**
    * 
    * @method fetch_subjects() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the subjects corresponding to a specific class
    */

    public function fetch_subjects(Request $request)
    {
        if ($request->filled('class_id')) {
            try {
                $result = $this->subject->join('class', 'subjects.id', '=', 'class.subject_id')
                        ->where('class.class', $request->input('class_id'))
                        ->select('subjects.id', 'name')
                        ->get();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
            return(json_encode($result));
        }
    }

    /**
    * 
    * @method logout() 
    * 
    * @param void
    * @return view of login page 
    * Desc : This method performs logout functionality for a user and redirects to the login page
    */

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
