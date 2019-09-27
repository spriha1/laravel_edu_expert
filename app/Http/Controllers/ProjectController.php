<?php

namespace App\Http\Controllers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\{User, UserType, Subject, Clas, Task, TeacherTask, StudentTask, TeacherRate, SharedTimesheet, Tax, Currency, StripeDetail};
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateMail;
use App\Mail\ForgotPassword;
use DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\User\UserInterface;
use Exception;

class ProjectController extends Controller
{
    protected $user_type, $teacher_subject, $holiday, $goal_plan, $user, $teacher_rate, $tax, $currency, $stripe_detail, $user_service;

    public function __construct(UserInterface $user_service)
    {
        $this->user_service     = $user_service;
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
        $this->stripe_detail    = new StripeDetail;
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
    * @method verify_mail() 
    * 
    * @param string (email verification code)
    * Desc : This method verifies the user's email id
    */

    public function verify_mail($code)
    {
        $res = $this->user_service->verify_mail($code);
        $msg = 'The url is either invalid or you already have activated your account.';
        if ($res) {
            $msg = 'Your account has been activated';
        }
        session()->flash('login_msg', $msg);
        return view('welcome');
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
        $hash  = base64_decode($hash);
        $email = base64_decode($email);
        $msg = 'The url is invalid';
        $res = $this->user_service->update_mail($hash, $email);
        if ($res) {
            $msg = 'Your email has been updated, you can continue';
        }
        session()->flash('profile_msg', $msg);
        return redirect('/profile');
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
        $res = $this->user_service->send_password_mail($request->input('username'));
        $msg = '';
        if ($res) {
            $msg = 'Please reset your password by clicking the link that has been sent to your email.';
        }
        session()->flash('reset_msg', $msg);
        return view('forgot_password');
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
        $expiry_time = base64_decode($expiry_time);
        $res = $this->user_service->reset_password_form($token, $expiry_time);
        if ($res) {
            return view('reset_password_form');
        }

        else {
            $msg = 'The link has expired';
            session()->flash('reset_msg', $msg);
            return view('forgot_password');
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
        $res = $this->user_service->reset_password($request->input('password'));
        if ($res) {
            $msg = 'Your password has been reset';
        }

        else {
            $msg = 'Your request has not been accepted by the admin yet';
        }

        session()->flash('login_msg', $msg);
        return view('welcome');
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
            $classes  = $this->clas->select('class')->distinct()->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('task_management', [
            'classes'  => $classes,
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
            $start_date  = date_to_timestamp($date_format, $start_date);
            $end_date    = date_to_timestamp($date_format, $end_date);
            $start_date  = getdate($start_date);
            $start_date  = $start_date['year'].'-'.$start_date['mon'].'-'.$start_date['mday'];
            $start_date  = strtotime($start_date);
            $end_date    = getdate($end_date);
            $end_date    = $end_date['year'].'-'.$end_date['mon'].'-'.$end_date['mday'];
            $end_date    = strtotime($end_date);
            $length      = count($request->input('subject'));
            try {
                DB::beginTransaction();
                for ($index = 0; $index < $length; $index++) {
                    $subject_id = $request->input('subject')[$index];
                    for($index2 = $start_date; $index2 <= $end_date; $index2 = $index2 + 86400) {
                        $result = $this->task->where([
                            ['subject_id', $subject_id],
                            ['class', $request->input('class')],
                            ['start_date', '<=', $index2],
                            ['end_date', '>=', $index2]
                        ])->select()->get();
                        if ($result->count()) {
                            return("The task has already been added for these dates");
                        }

                        else {
                            $task_id = $this->task->insertGetId([
                                'subject_id' => $subject_id,
                                'class'      => $request->input('class'),
                                'start_date' => $start_date,
                                'end_date'   => $end_date
                            ]);
                            $result = $this->clas->where([
                                ['class', $request->input('class')],
                                ['subject_id', $subject_id]
                            ])->select('teacher_id')
                            ->distinct()
                            ->get();
                            foreach ($result as $key => $value) {
                                for ($index3 = $start_date; $index3 <= $end_date; $index3 = $index3 + 86400) {
                                    $this->teacher_task->insert([
                                        'task_id' => $task_id,
                                        'teacher_id' => $value['teacher_id'],
                                        'on_date' => $index3
                                    ]);
                                }
                            }

                            $result = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')->where([
                                ['user_type', 'Student'],
                                ['class', $request->class]
                            ])->select('users.id')
                            ->get();
                            foreach ($result as $key => $value) {
                                for ($index3 = $start_date; $index3 <= $end_date; $index3 = $index3 + 86400) {
                                    $this->student_task->insert([
                                        'task_id' => $task_id,
                                        'student_id' => $value['id'],
                                        'on_date' => $index3
                                    ]);
                                }
                            }

                            return("Successfully added");
                        }
                    }
                }
                DB::commit();
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
