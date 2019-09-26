<?php

namespace App\Repositories\Subject;

use App\Subject;
use Illuminate\Support\Facades\Log;
use Exception;

class Service implements SubjectInterface
{
	protected $subject;

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

	public function add_subject($request)
    {
        try {
            $id = $this->subject->insertGetId(['name' => $request->input('subject')]);
            $result = $this->subject->where('id', $id)->select()->get();
            return(json_encode($result));
        }
    	catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * 
    * @method remove_subject() 
    * 
    * @param Request object
    * Desc : This method deletes a subject from the database
    */

    public function remove_subject($request)
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
