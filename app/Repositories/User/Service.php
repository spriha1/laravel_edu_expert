<?php

namespace App\Repositories\User;

use App\{User, UserType, Subject, SharedTimesheet, StripeDetail, Currency, Tax};
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;

class Service implements UserInterface
{
	protected $user, $user_type, $subject, $shared_timesheet, $stripe_detail, $currency, $tax;

    public function __construct()
    {
        $this->user             = new User;
        $this->user_type        = new UserType;
        $this->subject          = new Subject;
        $this->shared_timesheet = new SharedTimesheet;
        $this->stripe_detail    = new StripeDetail;
        $this->currency         = new Currency;
        $this->tax              = new Tax;
    }

    /**
    * 
    * @method login() 
    * 
    * @param Request object
    * @return string [html view of dashboard]
    * Desc : This method authenticates a user and redirects to the respective dashboard
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

        
                $user_type = $this->user_type->where('id', Auth::user()->user_type_id)->first();
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
    * @return string [html view of registration page]  
    * Desc : This method returns the registration page
    */

    public function register()
    {
        try {
            $values = [];
            $values['user_types'] = $this->user_type->where('user_type', '!=', "Admin")->get();
            $values['subjects']   = $this->subject->all();
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
    * @return string [html view of pending_requests page] 
    * Desc : This method returns the pending_requests page
    */

    public function pending_requests()
    {
        try {
            $values = [];
            $values['user_types'] = $this->user_type
                ->where('user_type', '!=', 'Admin')
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
            $values['user_types'] = $this->user_type->where('user_type', '!=', 'Admin')
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

    public function get_regd_users()
    {
        try {
            $select_data = ['firstname', 'lastname', 'email', 'username', 'users.id', 'block_status'];
            $values = $this->user
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->where([
                ['user_reg_status', 1],
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
    * @method regd_users() 
    * 
    * @param void
    * @return string [html view of regd_users page] 
    * Desc : This method returns the regd_users page
    */

    public function get_pending_requests()
    {
        try {
            $select_data = ['firstname', 'lastname', 'email', 'username', 'users.id', 'block_status'];
            $values = $this->user
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
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
    * @param Request object
    * @return string [html view of pending_requests page] 
    * Desc : This method fetches the users of a specific type with pending requests and returns the pending_requests page
    */

    public function post_pending_requests($user_type)
    {
        try {
            $values = [];
            $values['user_types'] = $this->user_type
                ->where('user_type', '!=', 'Admin')
                ->select('user_type')
                ->get();

            $values['results'] = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
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

    /**
    * 
    * @method post_regd_users() 
    * 
    * @param Request object
    * @return string [html view of regd_users page] 
    * Desc : This method fetches the registered users of a specific type and returns the regd_users page
    */

    public function post_regd_users($user_type)
    {
        try {
            $values = [];
            $values['user_types'] = $this->user_type
                ->where('user_type', '!=', 'Admin')
                ->select('user_type')
                ->get();

            $values['results'] = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where([
                    ['user_reg_status', 1],
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
    * @return view of the calling page 
    * Desc : This method removes a user from the system
    */

    public function remove_users($id)
    {
        try {
            $this->user->where('id', $id)->delete();
            return true;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
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
            return true;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
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
    * @return string [html view of admin dashboard] 
    * Desc : This method fetches the information required on dashboard and returns the admin dashboard
    */

    public function render_admin_dashboard()
    {
        try {
            $count = [];
            $count['regd_users'] = $this->user->where('user_reg_status', 1)->count();

            $count['pending_users'] = $this->user->where([
                    ['user_reg_status', 0],
                    ['email_verification_status', 1]
                ])->count();
        
            $count['shared_timesheets'] = $this->shared_timesheet->where('to_id', Auth::id())->count();
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
    * @return string [html view of teacher dashboard] 
    * Desc : This method checks if the URL contains the query parameter for authorization code generated by stripe and if it contains that, this method connects the teacher's stripe account to the admin's stripe account and also fetches the information required on dashboard and returns the teacher dashboard
    */

    public function render_teacher_dashboard($code)
    {
        $result['check_query_string'] = 0;
        $result['check']              = 0;
        if ($code) {
            $result['check_query_string'] = 1;
            $this->stripe_detail->insert([
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
            $stripe_account_id = json_decode($result)->stripe_user_id;
            $this->stripe_detail->where('user_id', Auth::id())
            ->update([
                'stripe_account_id' => $stripe_account_id
            ]);
            //close connection
            curl_close($ch);
        }

        else {
            $result = $this->stripe_detail->where('user_id', Auth::id())->first();
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
    * @return string [html view of profile page] 
    * Desc : This method fetches the information required on profile page and returns its view
    */

    public function profile()
    {
        try {
            $values = [];
            $user_type = $this->user_type->where('id', Auth::user()->user_type_id)
                ->select('user_type')
                ->first();


            $result = $this->user
                ->join('currencies', 'users.currency_id', '=', 'currencies.id')
                ->where('users.id', Auth::id())
                ->select('currency_id','rate')
                ->first();

            if ($user_type['user_type'] !== 'Student') {
                $currencies = $this->currency->get();
                $values['currencies']  = $currencies;
                $values['currency_id'] = $result['currency_id'];
            }

            if ($user_type['user_type'] === 'Teacher') {
                $values['rates']       = $rates;
            }

            else if ($user_type['user_type'] === 'Admin') {
                $tax = $this->tax->where('name', 'GST')
                    ->select('percentage')
                    ->first();
                $values['tax']         = $tax['percentage'];
            }

            $values['usertype']    = $user_type['user_type'];

            return $values;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
