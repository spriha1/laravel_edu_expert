<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Support\Facades\Auth;
use App\{User, UserType};

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($service)
    {
        $user = Socialite::driver($service)->user();

        $token = $user->token;
        // $secret = $user->tokenSecret;

        $user = Socialite::driver($service)->userFromToken($token);

        $user = User::where([
            ['email', $user->getEmail()],
            ['user_reg_status', 1],
            ['block_status', 0]
        ])->first();

        $user_type = UserType::where('id', $user->user_type_id)->first();

        if ($user) {
            Auth::login($user);
            if ($user_type->user_type === 'Admin') {   
                return redirect('/admin_dashboard');                     
                // return redirect('/admin_dashboard');
            }

            else if ($user_type->user_type === 'Teacher') {
                return redirect('/teacher_dashboard');
            }
            
            else if ($user_type->user_type === 'Student') {
                return redirect('/student_dashboard');
            }
        }

        else {
            $errors = new MessageBag(['password' => ['Username and/or password invalid.']]);
            return redirect()->back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }
}
