<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;

class SubjectsController extends Controller
{
    public function add_subject(Request $request)
    {
    	$id = Subject::insertGetId(['name' => $request->input('subject')]);
    	$result = Subject::where('id', $id)->select()->get();
    	return(json_encode($result));
    }

    public function remove_subject(Request $request)
    {
    	Subject::where('id', $request->input('subject_id'))->delete();
    }

    public function display_subjects()
    {
    	$result = Subject::select()->get();
    	return(json_encode($result));
    }
}
