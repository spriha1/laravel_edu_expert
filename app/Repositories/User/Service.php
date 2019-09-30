<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use App\Mail\UpdateMail;
use App\Mail\ForgotPassword;
use App\Currency as Currency;
use App\SharedTimesheet as SharedTimesheet;
use App\StripeDetail as StripeDetail;
use App\Subject as Subject;
use App\Tax as Tax;
use App\User as User;
use App\UserType as UserType;
use Exception;

class Service implements UserInterface
{
    /**
    * 
    * @method login() 
    * 
    * @param String [username, password]
    * @return string [usertype of the logged in user or false if the user is not authenticated]
    * Desc : This method authenticates a user
    */

    public function login($username, $password)
    {
        try {
            if (Auth::attempt([
                'username'        => $username, 
                'password'        => $password, 
                'user_reg_status' => 1, 
                'block_status'    => 0
            ])) {
        
                $user_type = UserType::where('id', Auth::user()->user_type_id)
                    ->first();
                return $user_type['user_type'];
            }

            else {
                return false;
            }    
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        
    }

    /**
    * 
    * @method register() 
    * 
    * @param void
    * @return usertypes and subjects information required in the registration form
    * Desc : This method returns the information required on registration page
    */

    public function register()
    {
        try {
            $values = [];
            $values['user_types'] = UserType::where('user_type', '!=', "Admin")->get();
            $values['subjects']   = Subject::all();
            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method pending_requests() 
    * 
    * @param void
    * @return array of usertypes or false in case of exception 
    * Desc : This method returns the information required on the pending_requests page
    */

    public function pending_requests()
    {
        try {
            $values = [];
            $values['user_types'] = UserType::where('user_type', '!=', 'Admin')
                ->select('user_type')
                ->get();
        
            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
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
            $values = [];
            $values['user_types'] = UserType::where('user_type', '!=', 'Admin')
                        ->select('user_type')
                        ->get();

            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method get_regd_users() 
    * 
    * @param void
    * @return collection of all the regsitered users
    * Desc : This method returns information of all the registered users
    */

    public function get_regd_users($search)
    {
        try {
            $select_data = ['firstname', 'lastname', 'email', 'username', 'users.id', 'block_status'];
            $users = User::join('user_types', 'users.user_type_id', '=', 'user_types.id');
            $where = [
                ['user_reg_status', 1],
                ['user_type', '!=', 'Admin']
            ];

            if($search != -1) {
                $where = [
                    ['user_reg_status', 1],
                    ['user_type', $search]
                ];
            }

            $values = $users->where($where)
            ->select($select_data)
            ->get();

            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method get_pending_requests() 
    * 
    * @param void
    * @return collection of all the pending requests
    * Desc : This method returns information of all the pending requests
    */

    public function get_pending_requests()
    {
        try {
            $select_data = ['firstname', 'lastname', 'email', 'username', 'users.id', 'block_status'];
            $values = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->where([
                ['user_reg_status', 0],
                ['email_verification_status', 1],
                ['user_type', '!=', 'Admin']
            ])
            ->select($select_data);

            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method post_pending_requests() 
    * 
    * @param String[usertype]
    * @return Collection of user information for all the pending requests for a specific usertype
    * Desc : This method fetches the users of a specific type with pending requests and returns the same
    */

    public function post_pending_requests($user_type)
    {
        try {
            $values = [];
            $values['user_types'] = UserType::where('user_type', '!=', 'Admin')
                ->select('user_type')
                ->get();

            $values['results'] = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where([
                    ['user_reg_status', 0],
                    ['email_verification_status', 1],
                    ['user_type', $user_type]
                ])
                ->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')
                ->get();

            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    // /**
    // * 
    // * @method post_regd_users() 
    // * 
    // * @param Request object
    // * @return Collection of user information for all the registered users for a specific usertype
    // * Desc : This method fetches the registered users of a specific type and returns the same
    // */

    // public function post_regd_users($user_type)
    // {
    //     try {
    //         $values = [];
    //         $values['user_types'] = UserType::where('user_type', '!=', 'Admin')
    //             ->select('user_type')
    //             ->get();

    //         $values['results'] = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
    //             ->where([
    //                 ['user_reg_status', 1],
    //                 ['user_type', $user_type]
    //             ])
    //             ->select('users.id', 'firstname', 'lastname', 'email', 'username', 'block_status')
    //             ->get();

    //         return $values;
    //     }

    //     catch (Exception $e) {
    //         Log::error($e->getMessage());
    //         return false;
    //     }
    // }

    /**
    * 
    * @method add_users() 
    * 
    * @param integer (id of the user to be added)
    * @return boolean 
    * Desc : This method adds a user to the system when the admin accepts their pending request
    */

    public function add_users($id)
    {
        try {
            User::where('id', $id)->update(['user_reg_status' => 1]);
            return true;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method remove_users() 
    * 
    * @param integer (id of the user to be removed)
    * @return boolean 
    * Desc : This method removes a user from the system
    */

    public function remove_users($id)
    {
        try {
            User::where('id', $id)->delete();
            return true;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method change_user_type() 
    * 
    * @param integer (id of the user to be blocked/unblocked)
    * @return boolean 
    * Desc : This method blocks/unblocks a user in the system
    */

    public function change_user_type($id, $type)
    {
        try {
            $user = User::where('id', $id);
            if ($type == 'unblock') {
                $user->update(['block_status' => 0]);
            }

            else {
                $user->update(['block_status' => 1]);
            }

            return true;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method render_admin_dashboard() 
    * 
    * @param void
    * @return array that keeps count of the registered users and pending requests
    * Desc : This method fetches the information required on dashboard and returns the same
    */

    public function render_admin_dashboard()
    {
        try {
            $count = [];
            $count['regd_users'] = User::where('user_reg_status', 1)->count();

            $count['pending_users'] = User::where([
                    ['user_reg_status', 0],
                    ['email_verification_status', 1]
                ])->count();
        
            $count['shared_timesheets'] = SharedTimesheet::where('to_id', Auth::id())->count();
            return $count;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method render_teacher_dashboard() 
    * 
    * @param void
    * @return user details of the user that connects to stripe
    * Desc : This method checks if the URL contains the query parameter for authorization code generated by stripe and if it contains that, this method connects the teacher's stripe account to the admin's stripe account and also fetches the information required on dashboard and returns the same
    */

    public function render_teacher_dashboard($code)
    {
        $result['check_query_string'] = 0;
        $result['check']              = 0;
        if ($code) {
            $result['check_query_string'] = 1;
            StripeDetail::insert([
                'user_id' => Auth::id(),
                'code'    => $code
            ]);
            // Account Id of account to be connected
            $code      = $code;
            $stripe_sk = env('STRIPE_SECRET_KEY');
            $req_url   = 'https://connect.stripe.com/oauth/token';
            $fields    = array(
                'client_secret' => urlencode($stripe_sk),
                'code'          => urlencode($code),
                'grant_type'    => urlencode('authorization_code')
            );
            $fields_string = '';
            //url-ify the data for the POST
            foreach ($fields as $key => $value) { 
                $fields_string .= $key.'='.$value.'&'; 
            }

            rtrim($fields_string, '&');
            //open connection
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $req_url);
            //execute post
            $result            = curl_exec($ch);
            $http_info         = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($http_info == 200) {
                $stripe_account_id = json_decode($result)->stripe_user_id;

                StripeDetail::where('user_id', Auth::id())
                ->update([
                    'stripe_account_id' => $stripe_account_id
                ]);
            }
            //close connection
            curl_close($ch);
        }

        else {
            $result = StripeDetail::where('user_id', Auth::id())->first();
            if ($result) {
                $result['check'] = 1;
            }
        }

        return $result;
    }

    /**
    * 
    * @method profile() 
    * 
    * @param void
    * @return array 
    * Desc : This method fetches the information required on profile page and returns it
    */

    public function profile()
    {
        try {
            $values = [];
            $user_type = UserType::where('id', Auth::user()->user_type_id)
                ->select('user_type')
                ->first();

            $result = User::join('currencies', 'users.currency_id', '=', 'currencies.id')
                ->where('users.id', Auth::id())
                ->select('currency_id','rate')
                ->first();

            if ($user_type['user_type'] !== 'Student') {
                $currencies = Currency::get();
                $values['currencies']  = $currencies;
                $values['currency_id'] = $result['currency_id'];
            }

            if ($user_type['user_type'] === 'Teacher') {
                $values['rates'] = $rates;
            }

            else if ($user_type['user_type'] === 'Admin') {
                $tax = Tax::where('name', 'GST')
                    ->select('percentage')
                    ->first();
                $values['tax'] = $tax['percentage'];
            }

            $values['usertype'] = $user_type['user_type'];
            return $values;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method verify_mail() 
    * @return boolean
    * @param string (email verification code)
    * Desc : This method verifies the user's email id
    */

    public function verify_mail($code)
    {
        $msg  = false;
        $hash = base64_decode($code);
        try {
            $results = User::where([
                ['email_verification_code', $hash],
                ['email_verification_status', 0]
            ])->first();

            if($results) {
                User::where([
                    ['email_verification_code', $hash],
                    ['email_verification_status', 0]
                ])->update(['email_verification_status' => 1]);

                $msg = true;
            }
            return $msg;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
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
        $msg = false;

        try {
            $result = User::where([
                ['email_verification_code', $hash],
                ['email_verification_status', 0]
            ])->select('id')->first();
        
            if($result) {
                User::where([
                    ['id', $result['id']],
                    ['email_verification_status', 0]
                ])->update([
                    'email_verification_status' => 1, 
                    'email' => $email
                ]);

                $msg = true;
            }

            return $msg;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method send_password_mail() 
    * @return boolean
    * @param Request object
    * Desc : This method sends a mail to the user to reset password
    */

    public function send_password_mail($username)
    {
        $msg = false;
        try {
            $result = User::where('username', $username)
                ->select('id', 'email')
                ->first();

            if($result) {
                $unique = uniqid();
                User::where('username', $username)
                    ->update(['token' => $unique]);

                Mail::to($result['email'])->send(new ForgotPassword($unique));
                $msg = true;
            }
            return $msg;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
    * 
    * @method reset_password_form() 
    * 
    * @param string (token to identify the user), integer (expiry time of the link)
    * @return boolean
    * Desc : This method checks if the link sent to the user has expired or not
    */

    public function reset_password_form($token, $expiry_time)
    {
        $res = true;
        session(['token' => $token]);
        $current_time = time();
        if ($current_time > $expiry_time) {
            try {
                User::where('token', $token)->update(['token' => NULL]);
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
                return false;
            }

            $res = false;
        }

        return $res;
    }

    /**
    * 
    * @method reset_password() 
    * @return boolean
    * @param string [password]
    * Desc : This method resets the password
    */

    public function reset_password($password)
    {
        $msg = false;
        try {
            $result = User::where([
                ['token', session('token')],
                ['user_reg_status', 1]
            ])
            ->select('username')
            ->first();

            if($result) {
                $password = Hash::make($password);
                User::where('token', session('token'))->update(['password' => $password]);
                $msg = true;
            }

            return $msg;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
