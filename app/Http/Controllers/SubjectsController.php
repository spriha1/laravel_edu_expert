<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
Use Illuminate\Support\Facades\Log;
use Exception;
use App\Repositories\Subject\Repository as SubjectRepository;

class SubjectsController extends Controller
{
    protected $subject;

    // public function __construct(SubjectRepository $subject)
    // {
    //     $this->subject = $subject;
    // }

    // public function add_subject(Request $request)
    // {
    //     try {
    //         $result = $this->subject->add_subject($request);
    //         return $result;
    //     }
    // 	catch (Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }
    public function __construct()
    {
        $this->subject = new Subject;
    }


    /**
    * 
    * @method add_subject() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method adds a subject to the database and returns the same to display it in the view
    */
    
    public function add_subject(Request $request)
    {
        try {
            $id = $this->subject->insertGetId(['name' => $request->input('subject')]);

            $result = $this->subject->where('id', $id)
                    ->select()
                    ->get();
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }
        
        return(json_encode($result));
    }

    /**
    * 
    * @method remove_subject() 
    * 
    * @param Request object
    * Desc : This method deletes a subject from the database
    */

    public function remove_subject(Request $request)
    {
        try{
            $this->subject->where('id', $request->input('subject_id'))->delete();
        }
    	catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method display_subjects() 
    * 
    * @param void
    * @return json 
    * Desc : This method fetches and returns various subjects added to the database to display it in the view
    */

    public function display_subjects()
    {
        try {
            $result = $this->subject->select()->get();
            return(json_encode($result));
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
