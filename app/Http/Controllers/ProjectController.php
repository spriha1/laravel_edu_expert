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
    public function check_login_status()
    {
        if (session()->has('username')) {
            $user_type = \App\User::join('user_types', 'users.user_type_id', '=', 'user_types.id')->select('user_types.user_type')->get();
            foreach ($user_type as $value) {
                if ($value->user_type === 'Admin') {
                    return redirect('admin_dashboard');
                }
                else if ($value->user_type === 'Teacher') {
                    return redirect('teacher_dashboard');
                }
                else if ($value->user_type === 'Student') {
                    return redirect('student_dashboard');
                }
            }
        }
        else {
            return true;
        }
    }

    public function home()
    {
        if ($this->check_login_status())
        {
            return view('welcome');
        }
    }

    public function register()
    {
        if ($this->check_login_status())
        {
            $user_types = \App\UserType::where('user_type', '!=', "Admin")->get();
            $subjects = \App\Subject::all();

            return view('register', [
                'user_types' => $user_types, 
                'subjects' => $subjects
            ]);
        }
    }

    public function forgot_password()
    {
        if ($this->check_login_status()) {
            return view('forgot_password');
        }
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_reg_status' => 1, 'block_status' => 0])) {
            $user = \App\User::where('username', $request->username)->get();
            foreach ($user as $value) {                
                session(['firstname' => $value->firstname, 'username' => $value->username, 'id' => $value->id]);                
                $user_type = \App\UserType::where('id', $value->user_type_id)->get();
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
        }
        else {
            return redirect('/');
        }
    }

    public function pending_requests()
    {
        $user_types = \App\UserType::where('user_type', '!=', 'Admin')->select('user_type')->get();
        $results = \App\User::whereRaw("user_reg_status = 0 AND user_type_id NOT IN (SELECT id FROM user_types WHERE user_type = 'Admin')")->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        return view('pending_requests', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => ""
        ]);
    }

    public function post_pending_requests(Request $request)
    {
        $user_types = \App\UserType::where('user_type', '!=', 'Admin')->select('user_type')->get();

        $c = 0;
        $result = \App\UserType::where('user_type', '!=', 'Admin')->select('user_type')->get();
        foreach ($result as $key => $value) {
            if ($value['user_type'] === $request->input('user_type')) {
                $c++;
            }
        }
        if ($c > 0) {
            $results = \App\User::join('user_types', 'users.user_type_id', '=', 'user_types.id')->where(['user_reg_status', 0], ['user_type', $request->input('user_type')])->select('id', 'firstname', 'lastname', 'email', 'username', 'block_status')->get();
        }
        return view('pending_requests', [
            'user_types' => $user_types,
            'results' => $results,
            'search' => $request->input('user_type')
        ]);
    }

    public function add_users($id)
    {
        \App\User::where('id', $id)->update(['user_reg_status', 1]);
        return redirect(Request::server('HTTP_REFERER'));
    }

    public function remove_users($id)
    {
        \App\User::where('id', $id)->delete();
        return redirect(Request::server('HTTP_REFERER'));
    }

    public function block_users($id)
    {
        \App\User::where('id', $id)->update('block_status', 1);
        return redirect(Request::server('HTTP_REFERER'));
    }

    public function unblock_users($id)
    {
        \App\User::where('id', $id)->update('block_status', 0);
        return redirect(Request::server('HTTP_REFERER'));
    }

    public function regd_users()
    {

    }

    public function render_admin_dashboard()
    {
        $result = \App\User::where('username', session('username'))->select('date_format')->get();
        return view('admin_dashboard', [
            'result' => $result
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
        $results = \App\User::where('username', session('username'))->select('firstname', 'lastname', 'username', 'email', 'date_format')->get();
        return view('profile', [
            'results' => $results
        ]);
    }

    public function logout()
    {
        if (session()->has('username')) {
            // Auth::logout();
            session()->forget('username');
            session()->flush();
            return redirect('/');
        }
    }
}
