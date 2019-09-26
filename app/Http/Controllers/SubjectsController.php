<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
Use Illuminate\Support\Facades\Log;
use Exception;
use App\Repositories\Subject\SubjectInterface as SubjectInterface;

class SubjectsController extends Controller
{
    protected $subject;

    public function __construct(SubjectInterface $subject)
    {
        $this->subject = $subject;
    }

    public function add_subject(Request $request)
    {
        try {
            $result = $this->subject->add_subject($request);
            return $result;
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function remove_subject(Request $request)
    {
        try{
            $this->subject->remove_subject($request);
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function display_subjects()
    {
        try {
            $result = $this->subject->display_subjects();
            return($result);
        }
        
        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
