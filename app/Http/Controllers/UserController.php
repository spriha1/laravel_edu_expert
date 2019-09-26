<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use App\Repositories\User\UserInterface as UserInterface;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $user_type = $this->user->login($data['username'], $data['password']);
        if ($user_type) {
            if ($user_type === 'Admin') {
                $view = 'admin_dashboard';
            }

            else if ($user_type === 'Teacher') {
                $view = 'teacher_dashboard';
            }

            else if ($user_type === 'Student') {
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
    * @return string [html view of registration page]  
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
    * Desc : This method returns the regd_users page
    */

    public function get_regd_users()
    {
        $values = $this->user->get_regd_users();
        if ($values) {
            $datatable = Datatables::of($values)
            ->addColumn('action', function($values) {
                $status = '<div class="col-sm-6"><a href="unblock_users/'. $values->id .'"><button class="btn btn-success">Unblock</button></a></div>';
                if ($values->block_status == 0) {
                    $status = '<div class="col-sm-6"><a href="block_users/'. $values->id .'"><button class="btn btn-success">Block</button></a></div>';
                }
                return '<div class="col-sm-6"><a href="remove_users/'. $values->id .'"><button class="btn btn-success">Remove</button></a></div>'. $status;
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
    * @method get_regd_users() 
    * 
    * @param void
    * @return string [html view of regd_users page] 
    * Desc : This method returns the regd_users page
    */

    public function get_pending_requests()
    {
        $values = $this->user->get_pending_requests();
        if ($values) {
            $datatable = Datatables::of($values)
            ->addColumn('action', function($values) {
                $status = '<div class="col-sm-6"><a href="unblock_users/'. $values->id .'"><button class="btn btn-success">Unblock</button></a></div>';
                if ($values->block_status == 0) {
                    $status = '<div class="col-sm-6"><a href="block_users/'. $values->id .'"><button class="btn btn-success">Block</button></a></div>';
                }
                return '<div class="col-sm-6"><a href="remove_users/'. $values->id .'"><button class="btn btn-success">Remove</button></a></div><div class="col-sm-6"><a href="/add_users/'. $values->id .'"><button class="btn btn-success">Add</button></a></div>'. $status;
            })
            ->rawColumns(['action']);

            // dd($datatable->make(true));
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
    * Desc : This method fetches the users of a specific type with pending requests and returns the pending_requests page
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
        if ($request->input('user_type') != -1) {
            $values = $this->user->post_regd_users($request->input('user_type'));
            if ($values) {
                return view('regd_users', [
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
    * @method add_users() 
    * 
    * @param integer (id of the user to be added)
    * @return view of the calling page 
    * Desc : This method adds a user to the system when the admin accepts their pending request
    */

    public function add_users($id)
    {
        $result = $this->user->add_users($id);
        if ($result) {
            return redirect()->back();
        }

        else {
            session()->flash('error', 'The user could not be added');
            return redirect()->back();
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
        $result = $this->user->remove_users($id);
        if ($result) {
            return redirect()->back();
        }

        else {
            session()->flash('error', 'The user could not be removed');
            return redirect()->back();
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
        $result = $this->user->block_users($id);
        if ($result) {
            return redirect()->back();
        }

        else {
            session()->flash('error', 'The user could not be blocked');
            return redirect()->back();
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
        $result = $this->user->unblock_users($id);
        if ($result) {
            return redirect()->back();
        }

        else {
            session()->flash('error', 'The user could not be unblocked');
            return redirect()->back();
        }
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
        $values = $this->user->profile();

        $values['currencies'] = $values['currencies']->mapWithKeys(function ($items) {
            return [$items['id'] => $items['code']];
        });

        $data = ['usertype'    => $values['usertype'],
                'currencies'  => $values['currencies'], 
                'currency_id' => $values['currency_id']
            ];
        if ($values) {
            if ($values['usertype'] === 'Teacher') {
                return view('profile', array_merge($data,['rates' => $values['rates']]));
            }

            else if ($values['usertype'] === 'Admin') {
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
