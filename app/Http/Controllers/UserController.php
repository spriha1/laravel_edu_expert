<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;
use App\Repositories\User\UserInterface as UserInterface;
use App\User;
use Exception;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
    * 
    * @method login() 
    * 
    * @param Request object
    * @return string [html view of dashboard]
    * Desc : This method sends the username and password to the login method and redirects the user to the respective dashboard based on the response from the method
    */

    public function login(Request $request)
    {
        $data      = $request->all();
        $user_type = $this->user->login($data['username'], $data['password']);

        if ($user_type) {
            if ($user_type === config('default.Admin')) {
                $view = 'admin_dashboard';
            }

            else if ($user_type === config('default.Teacher')) {
                $view = 'teacher_dashboard';
            }

            else if ($user_type === config('default.Student')) {
                $view = 'student_dashboard';
            }

            return redirect($view);
        }

        else {
            $errors = new MessageBag(['password' => ['Username and/or password invalid.']]);    
            return redirect()->back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }

    /**
    * 
    * @method register() 
    * 
    * @param void
    * @return string [html view of registration page or default error page]  
    * Desc : This method returns the registration page
    */

    public function register()
    {
        $values = $this->user->register();

        $values['user_types'] = $values['user_types']->mapWithKeys(function ($items) {
            return [$items['user_type'] => $items['user_type']];
        });

        $values['subjects'] = $values['subjects']->mapWithKeys(function ($items) {
            return [$items['id'] => $items['name']];
        });

        if ($values) {
            return view('register', [
                'user_types' => $values['user_types']->all(), 
                'subjects'   => $values['subjects']->all()
            ]);
        }
        
        else {
            abort(404);
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
        $values = $this->user->pending_requests();

        if ($values) {
            return view('pending_requests', [
                'user_types' => $values['user_types'],
                'search'     => ""
            ]);
        }
        
        else {
            session()->flash('error', 'Error in getting pending requests.');
            return redirect()->back();
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
        $values = $this->user->regd_users();

        if ($values) {
            return view('regd_users', [
                'user_types' => $values['user_types'],
                'search'     => ""
            ]);
        }

        else {
            session()->flash('error', 'Error in getting registered users requests.');
            return redirect()->back();
        }
    }

    /**
    * 
    * @method get_regd_users() 
    * 
    * @param void
    * @return string [html view of regd_users page] 
    * Desc : This method returns the datatable view of the regd_users page
    */

    public function get_regd_users(Request $request)
    {   
        $search = $request->input('user_type');
        // dd($search);
        $values = $this->user->get_regd_users($search);
        
        if ($values) {
            $datatable = Datatables::of($values)
                ->addColumn('action', function($values) {
                    $status = '<div class="col-sm-6"><button class="btn btn-info change_status" user_id='. $values->id .' type=unblock>Unblock</button></div>';

                    if ($values->block_status == 0) {
                        $status = '<div class="col-sm-6"><button class="btn btn-warning change_status" user_id='. $values->id .' type=block>Block</button></div>';
                    }

                    return '<div class="col-sm-6"><button class="btn btn-danger remove" user_id='. $values->id .'>Remove</button></div>'. $status;
                })
                ->rawColumns(['action']);

            return $datatable->make(true);
        }

        else {
            session()->flash('error', 'Error in getting registered users requests.');
            return redirect()->back();
        }
    }

    /**
    * 
    * @method get_pending_requests() 
    * 
    * @param void
    * @return string [html view of regd_users page] 
    * Desc : This method returns the datatable view of the pending_requests page
    */

    public function get_pending_requests()
    {
        $values = $this->user->get_pending_requests();

        if ($values) {
            $datatable = Datatables::of($values)
                ->addColumn('action', function($values) {
                    $status = '<div class="col-sm-4"><button class="btn btn-info change_status" user_id='. $values->id .'>Unblock</button></div>';

                    if ($values->block_status == 0) {
                        $status = '<div class="col-sm-4"><button class="btn btn-warning change_status" user_id='. $values->id .'>Block</button></div>';
                    }

                    return '<div class="col-sm-4"><button class="btn btn-danger remove" user_id='. $values->id .'>Remove</button></div><div class="col-sm-4"><button class="btn btn-success add" user_id='. $values->id .'>Add</button></div>'. $status;
                })
                ->rawColumns(['action']);

            return $datatable->make(true);
        }

        else {
            session()->flash('error', 'Error in getting pending requests.');
            return redirect()->back();
        }
    }
    

    /**
    * 
    * @method post_pending_requests() 
    * 
    * @param Request object
    * @return string [html view of pending_requests page] 
    * Desc : This method returns the pending_requests page
    */

    public function post_pending_requests(Request $request)
    {
        if ($request->input('user_type') != -1) {
            $values = $this->user->post_pending_requests($request->input('user_type'));
            if ($values) {
                return view('pending_requests', [
                    'user_types' => $values['user_types'],
                    'results'    => $values['results'],
                    'search'     => $request->input('user_type')
                ]);
            }
        }

        session()->flash('error', 'Please select a valid user type');
        return redirect()->back();
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
        if ($request->input('search') != -1) {
            $values = $this->get_regd_users($request->input('search'));
        }
    }

    /**
    * 
    * @method add_users() 
    * 
    * @param integer (id of the user to be added)
    * @return view of the calling page 
    * Desc : This method calls the add_users method and returns the view or the calling page
    */

    public function add_users($id)
    {
        $result = $this->user->add_users($id);
        if ($result) {
            return response()->json(['success' => true]);
        }

        else {
            return response()->json(['success' => false]);
        }
    }

    /**
    * 
    * @method remove_users() 
    * 
    * @param integer (id of the user to be removed)
    * @return view of the calling page 
    * Desc : This method calls the remove_users method and returns the view or the calling page
    */

    public function remove_users($id)
    {
        $result = $this->user->remove_users($id);
        if ($result) {
            return response()->json(['success' => true]);
        }

        else {
            return response()->json(['success' => false]);
        }
    }

    /**
    * 
    * @method change_user_type() 
    * 
    * @param integer (id of the user to be blocked/unblocked)
    * @return view of the calling page 
    * Desc : This method calls the block/unblock_users method and returns the view or the calling page
    */

    public function change_user_type($id, $type)
    {
        $result = $this->user->change_user_type($id, $type);
        if ($result) {
            return response()->json(['success' => true]);
        }

        else {
            return response()->json(['success' => false]);
        }
    }

    /**
    * 
    * @method profile() 
    * 
    * @param void
    * @return string [html view of profile page or the default error page] 
    * Desc : This method returns view of the profile page
    */

    public function profile()
    {
        $values = $this->user->profile();
        if ($values['usertype'] !== config('default.Student')) {
            $values['currencies'] = $values['currencies']->mapWithKeys(function ($items) {
                return [$items['id'] => $items['code']];
            });

            $data = [
                'usertype'    => $values['usertype'],
                'currencies'   => $values['currencies'], 
                'currency_id'  => $values['currency_id']
            ];
        }

        if ($values) {
            if ($values['usertype'] === config('default.Teacher')) {
                return view('profile', array_merge($data,['rates' => $values['rates']]));
            }

            else if ($values['usertype'] === config('default.Admin')) {
                return view('profile', array_merge($data,['tax' => $values['tax']]));
            }

            else {
                return view('profile', [
                    'usertype' => $values['usertype']
                ]);
            }
        }
        
        else {
            abort(404);
        }
    }
}
