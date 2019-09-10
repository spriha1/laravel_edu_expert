<?php

namespace App\Repositories\Subject;

use App\Subject;
use Illuminate\Support\Facades\Log;
use Exception;

class Eloquent implements Repository
{
	protected $subject;

    public function __construct()
    {
        $this->subject = new Subject;
    }

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

    public function remove_subject($request)
    {
        try{
            $this->subject->where('id', $request->input('subject_id'))->delete();
        }
    	catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

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
