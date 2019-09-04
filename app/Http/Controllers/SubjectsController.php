<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;

class SubjectsController extends Controller
{
    protected $subject;

    public function __construct()
    {
        $this->subject = new Subject;
    }

    public function add_subject(Request $request)
    {
    	$id = $this->subject->insertGetId(['name' => $request->input('subject')]);
    	$result = $this->subject->where('id', $id)->select()->get();
    	return(json_encode($result));
    }

    public function remove_subject(Request $request)
    {
    	$this->subject->where('id', $request->input('subject_id'))->delete();
    }

    public function display_subjects()
    {
    	$result = $this->subject->select()->get();
    	return(json_encode($result));
    }
}
