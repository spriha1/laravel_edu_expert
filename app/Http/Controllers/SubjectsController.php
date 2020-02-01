<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Log;
use App\Subject;
use App\Repositories\Subject\SubjectInterface as SubjectInterface;
use Exception;

class SubjectsController extends Controller
{
    protected $subject;

    public function __construct(SubjectInterface $subject)
    {
        $this->subject = $subject;
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
        $result = $this->subject->add_subject($request);
        return $result;
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
        $this->subject->remove_subject($request);

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
        $result = $this->subject->display_subjects();
        return($result);
    }
}
