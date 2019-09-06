<?php

namespace App\Http\Controllers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\{User, UserType, Subject, Clas, Task, TeacherTask, StudentTask, TeacherRate, SharedTimesheet};
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateMail;
use App\Mail\ForgotPassword;

class ProjectController extends Controller
{
    protected $user_type, $teacher_subject, $holiday, $goal_plan, $user, $teacher_rate;

    public function __construct()
    {
        $this->user = new User;
        $this->user_type = new UserType;
        $this->subject = new Subject;
        $this->clas = new Clas;
        $this->task = new Task;
        $this->teacher_task = new TeacherTask;
        $this->student_task = new StudentTask;
        $this->shared_timesheet = new SharedTimesheet;
        $this->teacher_rate = new TeacherRate;
    }

    public function home()
    {
        return view('welcome');
    }

    public function register()
    {
        $user_types = $this->user_type->where('user_type', '!=', "Admin")->get();
        $subjects = $this->subject->all();

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
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_reg_status' => 1, 'block_status' => 0])) {
                          
            $user_type = $this->user_type->where('id', Auth::user()->user_type_id)->get();
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
            // return redirect()->back()->withInput()->withFlashMessage('Wrong username/password combination.');
            // return redirect('/');
            $errors = new MessageBag(['password' => ['Username and/or password invalid.']]);
            // Auth::attempt()->save();
            return redirect()->back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }

    public function pending_requests()
    {
        $user_types = $this->user_type->where('user_type', '!=', 'Admin')->select('user_type')->get();
        $results = $this->user->whereRaw("user_reg_status = 0 AND email_verification_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        return view('pending_requests', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => ""
        ]);
    }

    public function post_pending_requests(Request $request)
    {
        $user_types = $this->user_type->where('user_type', '!=', 'Admin')->select('user_type')->get();

        $c = 0;
        foreach ($user_types as $key => $value) {
            if ($value['user_type'] === $request->input('user_type')) {
                $c++;
            }
        }
        if ($c > 0) {
            $results = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')->where([['user_reg_status', 0], ['user_type', $request->input('user_type')]])->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        }
        else {
            $results = $this->user->whereRaw("user_reg_status = 0 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        }
        return view('pending_requests', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => $request->input('user_type')
        ]);
    }

    public function add_users($id)
    {
        $this->user->where('id', $id)->update(['user_reg_status' => 1]);
        return redirect()->back();
    }

    public function remove_users($id)
    {
        $this->user->where('id', $id)->delete();
        return redirect()->back();
    }

    public function block_users($id)
    {
        $this->user->where('id', $id)->update(['block_status' => 1]);
        return redirect()->back();
    }

    public function unblock_users($id)
    {
        $this->user->where('id', $id)->update(['block_status' => 0]);
        return redirect()->back();
    }

    public function regd_users()
    {
        $user_types = $this->user_type->where('user_type', '!=', 'Admin')->select('user_type')->get();
        $results = $this->user->whereRaw("user_reg_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        return view('regd_users', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => ""
        ]);
    }

    public function post_regd_users(Request $request)
    {
        $user_types = $this->user_type->where('user_type', '!=', 'Admin')->select('user_type')->get();

        $c = 0;
        foreach ($user_types as $key => $value) {
            if ($value['user_type'] === $request->input('user_type')) {
                $c++;
            }
        }
        if ($c > 0) {
            $results = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')->where([['user_reg_status', 1], ['user_type', $request->input('user_type')]])->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        }
        else {
            $results = $this->user->whereRaw("user_reg_status = 1 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        }
        return view('regd_users', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => $request->input('user_type')
        ]);
    }

    public function render_admin_dashboard()
    {
        $regd_users = $this->user->where('user_reg_status', 1)->selectRaw('count(*) as total')->get();
        foreach ($regd_users as $regd_user) {
            $regd_user_count = $regd_user->total;
        }

        $pending_users = $this->user->where([
            ['user_reg_status', 0],
            ['email_verification_status', 1]
        ])->selectRaw('count(*) as total')->get();
        foreach ($pending_users as $pending_user) {
            $pending_user_count = $pending_user->total;
        }

        $shared_timesheets = $this->shared_timesheet->where('to_id', Auth::id())->selectRaw('count(*) as total')->get();
        foreach ($shared_timesheets as $shared_timesheet) {
            $shared_timesheet_count = $shared_timesheet->total;
        }

        return view('admin_dashboard', [
            'regd_user_count' => $regd_user_count,
            'pending_user_count' => $pending_user_count,
            'shared_timesheet_count' => $shared_timesheet_count
        ]);
    }

    public function render_teacher_dashboard()
    {
        return view('teacher_dashboard');
    }

    public function render_student_dashboard()
    {
        return view('student_dashboard');
    }

    public function profile()
    {
        $usertypes = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
        ->where('users.id', Auth::id())
        ->select('user_type')->get();

        foreach ($usertypes as $usertype) {
            if ($usertype->user_type === 'Teacher') {
                $rates = $this->teacher_rate->where('teacher_id', Auth::id())->select('rate')->get();
                return view('profile', [
                    'usertype' => $usertype->user_type,
                    'rates' => $rates
                ]);
            }
            return view('profile', [
                'usertype' => $usertype->user_type
            ]);
        }
    }

    public function verify_mail($code)
    {
        $hash = base64_decode($code);
        $results = $this->user->where([
            ['email_verification_code', $hash],
            ['email_verification_status', 0]
        ])->get();
        if($results->count()) {
            $this->user->where([
                ['email_verification_code', $hash],
                ['email_verification_status', 0]
            ])->update(['email_verification_status' => 1]);
            echo '<div>Your account has been activated, you can now <a href="/">login</a></div>';
        }
        else {
            echo '<div>The url is either invalid or you already have activated your account.</div>';
        }
    }

    public function update_mail($hash, $email)
    {
        $hash = base64_decode($hash);
        $email = base64_decode($email);
        $result = $this->user->where([
            ['email_verification_code', $hash],
            ['email_verification_status', 0]
        ])->select('id')->get();
        if($result->count()) {
            foreach ($result as $res) {
                $this->user->where([
                    ['id', $res->id],
                    ['email_verification_status', 0]
                ])->update(['email_verification_status' => 1, 'email' => $email]);
            }
            echo '<div>Your email has been updated, you can continue';
        }
        else {
            echo '<div>The url is either invalid or you already have activated your account.</div>';
        }
    }

    public function send_password_mail(Request $request)
    {
        if($request->filled('username')) {
            $result = $this->user->where('username', $request->input('username'))->select('id')->get();
            if($result->count()) {
                $unique = uniqid();
                $this->user->where('username', $request->input('username'))->update(['token' => $unique]);
                $results = $this->user->where('username', $request->input('username'))->select('email', 'token')->get();
                foreach ($results as $result) {
                    Mail::to($result->email)->send(new ForgotPassword($result->token));
                    echo "Please reset your password by clicking the link that has been sent to your email.";
                }
            }
        }
    }

    public function reset_password_form($token, $expiry_time)
    {
        $token = base64_decode($token);
        session(['token' => $token]);
        $expiry_time = base64_decode($expiry_time);
        $current_time = time();
        if ($current_time > $expiry_time) {
            $this->user->where('token', $token)->update(['token' => NULL]);
            echo "The link has expired";
        }
        else {
            return view('reset_password_form');
        }
    }

    public function reset_password(Request $request)
    {
        if($request->filled('password')) {
            $result = $this->user->where([
                ['token', session('token')],
                ['user_reg_status', 1]
            ])->select('username')->get();

            if($result->count()) {
                $password = Hash::make($request->input('password'));
                $this->user->where('token', session('token'))->update(['password' => $password]);
                echo '<div>Your password has been reset, you can now <a href="/"> login</a></div>';
            }
            else {
                echo '<div>Your request has not been accepted by the admin yet</div>';
            }
        }
    }

    public function task_management()
    {
        $teachers = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')->where('user_type', 'Teacher')->select('firstname', 'users.id')->get();
        $classes = $this->clas->select('class')->distinct()->get();
        return view('task_management', [
            'classes' => $classes,
            'teachers' => $teachers
        ]);
    }

    public function add_timetable(Request $request)
    {
        if ($request->filled('class') && $request->filled('subject') && $request->filled('start_date') && $request->filled('end_date')) {
            $date_format = $request->input('date_format');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            if ($date_format === "yyyy/mm/dd") {
                $start_date = Carbon::createFromFormat("Y/m/d" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("Y/m/d" , $end_date)->timestamp;
            }
            else if ($date_format === "yyyy.mm.dd") {
                $start_date = Carbon::createFromFormat("Y.m.d" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("Y.m.d" , $end_date)->timestamp;
            }
            else if ($date_format === "yyyy-mm-dd") {
                $start_date = Carbon::createFromFormat("Y-m-d" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("Y-m-d" , $end_date)->timestamp;
            }
            else if ($date_format === "dd/mm/yyyy") {
                $start_date = Carbon::createFromFormat("d/m/Y" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("d/m/Y" , $end_date)->timestamp;
            }
            else if ($date_format === "dd-mm-yyyy") {
                $start_date = Carbon::createFromFormat("d-m-Y" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("d-m-Y" , $end_date)->timestamp;
            }
            else if ($date_format === "dd.mm.yyyy") {
                $start_date = Carbon::createFromFormat("d.m.Y" , $start_date)->timestamp;
                $end_date = Carbon::createFromFormat("d.m.Y" , $end_date)->timestamp;
            }
            $start_date = getdate($start_date);
            $start_date = $start_date['year'].'-'.$start_date['mon'].'-'.$start_date['mday'];
            $start_date = strtotime($start_date);

            $end_date = getdate($end_date);
            $end_date = $end_date['year'].'-'.$end_date['mon'].'-'.$end_date['mday'];
            $end_date = strtotime($end_date);
            $length = count($request->input('subject'));
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
                        return("The task has already been added");
                    }
                    else {
                        $task_id = $this->task->insertGetId([
                            'subject_id' => $subject_id,
                            'class' => $request->input('class'),
                            'start_date' => $start_date,
                            'end_date' => $end_date
                        ]);
                        $result = $this->clas->where([
                            ['class', $request->input('class')],
                            ['subject_id', $subject_id]
                        ])->select('teacher_id')->distinct()->get();

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
                        ])->select('users.id')->get();
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
                    }
                }
            }
        }
    }

    public function fetch_subjects(Request $request)
    {
        if ($request->filled('class_id')) {
            $result = $this->subject->join('class', 'subjects.id', '=', 'class.subject_id')->where('class.class', $request->input('class_id'))->select('subjects.id', 'name')->get();
            return(json_encode($result));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
