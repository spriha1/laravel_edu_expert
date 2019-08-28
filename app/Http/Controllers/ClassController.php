<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Clas;
use App\User;

class ClassController extends Controller
{
    public function render_view()
    {
    	$subjects = Subject::select()->get();
    	return view('manage_class', [
    		'subjects' => $subjects
    	]);
    }

    public function display_class()
    {
    	$classes = Clas::select('class')->distinct()->get();
    	return(json_encode($classes));
    }

    public function fetch_teachers(Request $request)
    {
    	if ($request->filled('subject_id')) {
    		$teachers = User::join('teacher_subjects', 'users.id', '=', 'teacher_subjects.teacher_id')->where('subject_id', $request->input('subject_id'))->select('users.id', 'firstname')->get();
    		return(json_encode($teachers));
    	}
    }

    public function add_class(Request $request)
    {
    	if ($request->filled('class') && $request->filled('subjects')) {
    		$length = count($request->input('subjects'));
    		for($i = 0; $i < $length; $i++)
    		{
    			$id = Clas::insertGetId([
    				'class' => $request->input('class'),
    				'subject_id' => $request->input('subjects')[$i],
    				'teacher_id' => $request->input($request->input('subjects')[$i])
    			]);    			
    		}
    		$result = Clas::where('id', $id)->select('class')->get();
    		return(json_encode($result));
    	}
    }

    public function remove_class(Request $request)
    {
    	if ($request->filled('class_id'))
    	{
    		Clas::where('class', $request->input('class_id'))->delete();
    	}
    }

    public function fetch_class_details(Request $request)
    {
    	if ($request->filled('class')) {
    		$result = User::join('class', 'users.id', '=', 'class.teacher_id')->join('subjects', 'class.subject_id' , '=', 'subjects.id')->where('class.class', $request->input('class')->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name'))->get();
    		return(json_encode($result));
    	}
    }

    public function remove_class_subject(Request $request)
    {
    	if ($request->filled('class_id') && $request->filled('subject_id'))
    	{
    		Clas::where([
    			['class', $request->input('class_id')],
    			['subject_id', $request->input('subject_id')]
    		])->delete();
    	}
    }

    public function add_class_subject(Request $request)
    {
    	if ($request->filled('class') && $request->filled('subjects')) {
    		$length = count($request->input('subjects'));
    		for($i = 0; $i < $length; $i++)
    		{
    			$id = Clas::insertGetId([
    				'class' => $request->input('class'),
    				'subject_id' => $request->input('subjects')[$i],
    				'teacher_id' => $request->input($request->input('subjects')[$i])
    			]);
    			$result = User::join('class', 'users.id', '=', 'class.teacher_id')->join('subjects', 'class.subject_id', '=', 'subjects.id')->where([['class.id', $id],['class.class', $request->input('class')]])->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name')->get();
    			return(json_encode($result));
    		}
    	}
    }

    public function update_teacher(Request $request)
    {
    	if ($request->filled('subject_id') && $request->filled('class_id') && $request->filled('teacher_id')) {
    		Clas::where([
    			['subject_id', $request->input('subject_id')],
    			['class', $request->input('class_id')]
    		])->update(['teacher_id' => $request->input('teacher_id')]);
    		$result = User::where('id', $request->input('teacher_id'))->select('firstname')->get();
    		return(json_encode($result));
    	}
    }
}
