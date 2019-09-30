<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use App\Mail\UpdateMail;
use App\Mail\ForgotPassword;
use App\Repositories\User\UserInterface;
use App\Clas as Clas;
use App\StudentTask as StudentTask;
use App\Subject as Subject;
use App\Task as Task;
use App\TeacherTask as TeacherTask;
use App\User as User;
use Carbon\Carbon;
use DB;
use Exception;

class ProjectController extends Controller
{
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
    * Desc : This method returns the view of the login page with a message based on the result of the verification of  the user's email id
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
    * Desc : This method redirects to the profile page based on the result of the verification of the user's mail on update request
    */

    public function update_mail($hash, $email)
    {
        $hash  = base64_decode($hash);
        $email = base64_decode($email);
        $msg   = 'The url is invalid';
        $res   = $this->user_service->update_mail($hash, $email);

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
    * Desc : This method returns the view of the forgot password page
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
        $token       = base64_decode($token);
        $expiry_time = base64_decode($expiry_time);
        $res         = $this->user_service->reset_password_form($token, $expiry_time);

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
    * Desc : This method returns the view of the login page
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
            $teachers = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where('user_type', 'Teacher')
                ->select('firstname', 'users.id')
                ->get();

            $classes  = Clas::select('class')->distinct()->get();

            return view('task_management', [
                'classes'  => $classes,
                'teachers' => $teachers
            ]);
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
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
                        $result = Task::where([
                            ['subject_id', $subject_id],
                            ['class', $request->input('class')],
                            ['start_date', '<=', $index2],
                            ['end_date', '>=', $index2]
                        ])
                        ->select()
                        ->get();

                        if ($result->count()) {
                            return("The task has already been added for these dates");
                        }

                        else {
                            $task_id = Task::insertGetId([
                                'subject_id' => $subject_id,
                                'class'      => $request->input('class'),
                                'start_date' => $start_date,
                                'end_date'   => $end_date
                            ]);
                            $result = Clas::where([
                                ['class', $request->input('class')],
                                ['subject_id', $subject_id]
                            ])
                            ->select('teacher_id')
                            ->distinct()
                            ->get();

                            foreach ($result as $key => $value) {
                                for ($index3 = $start_date; $index3 <= $end_date; $index3 = $index3 + 86400) {
                                    TeacherTask::insert([
                                        'task_id' => $task_id,
                                        'teacher_id' => $value['teacher_id'],
                                        'on_date' => $index3
                                    ]);
                                }
                            }

                            $result = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')->where([
                                    ['user_type', 'Student'],
                                    ['class', $request->class]
                                ])
                                ->select('users.id')
                                ->get();

                            foreach ($result as $key => $value) {
                                for ($index3 = $start_date; $index3 <= $end_date; $index3 = $index3 + 86400) {
                                    StudentTask::insert([
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
                $result = Subject::join('class', 'subjects.id', '=', 'class.subject_id')
                    ->where('class.class', $request->input('class_id'))
                    ->select('subjects.id', 'name')
                    ->get();

                return(json_encode($result));
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
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
