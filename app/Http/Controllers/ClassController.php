<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Subject, Clas, User, Task, TeacherTask};

class ClassController extends Controller
{
    protected $subject, $clas, $user, $task, $teacher_task;

    public function __construct()
    {
        $this->user = new User;
        $this->subject = new Subject;
        $this->clas = new Clas;
        $this->task = new Task;
        $this->teacher_task = new TeacherTask;
    }

    public function render_view()
    {
        try {
            $subjects = $this->subject->select()->get();
            return view('manage_class', [
                'subjects' => $subjects
            ]);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    	
    }

    public function display_class()
    {
        try {
            $classes = $this->clas->select('class')->distinct()->get();
            return(json_encode($classes));
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function fetch_teachers(Request $request)
    {
    	if ($request->filled('subject_id')) {
            try {
                $teachers = $this->user->join('teacher_subjects', 'users.id', '=', 'teacher_subjects.teacher_id')->where('subject_id', $request->input('subject_id'))->select('users.id', 'firstname')->get();
                return(json_encode($teachers));
            }
            catch (Exception $e) {
                Log::error($e->getMessage());
            }
    	}
    }

    public function add_class(Request $request)
    {
    	if ($request->filled('class') && $request->filled('subjects')) {
    		$length = count($request->input('subjects'));
    		for ($i = 0; $i < $length; $i++) {
                try {
                    $id = $this->clas->insertGetId([
                        'class' => $request->input('class'),
                        'subject_id' => $request->input('subjects')[$i],
                        'teacher_id' => $request->input($request->input('subjects')[$i])
                    ]);
                }
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
    		}
            try{
                $result = $this->clas->where('id', $id)->select('class')->get();
            }
    		catch (Exception $e) {
                Log::error($e->getMessage());
            }
    		return(json_encode($result));
    	}
    }

    public function remove_class(Request $request)
    {
    	if ($request->filled('class_id'))
    	{
            try {
                $this->clas->where('class', $request->input('class_id'))->delete();
            }
    		catch (Exception $e) {
                Log::error($e->getMessage());
            }
    	}
    }

    public function fetch_class_details(Request $request)
    {
    	if ($request->filled('class')) {
            try {
                $result = $this->user->join('class', 'users.id', '=', 'class.teacher_id')->join('subjects', 'class.subject_id' , '=', 'subjects.id')->where('class.class', $request->input('class'))->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name')->get();
            }
    		catch (Exception $e) {
                Log::error($e->getMessage());
            }
    		return(json_encode($result));
    	}
    }

    public function remove_class_subject(Request $request)
    {
    	if ($request->filled('class_id') && $request->filled('subject_id'))
    	{
            try {
                $this->clas->where([
                    ['class', $request->input('class_id')],
                    ['subject_id', $request->input('subject_id')]
                ])->delete();
            }
    		catch (Exception $e) {
                Log::error($e->getMessage());
            }
    	}
    }

    public function add_class_subject(Request $request)
    {
    	if ($request->filled('class') && $request->filled('subjects')) {
    		$length = count($request->input('subjects'));
    		for ($i = 0; $i < $length; $i++) {
                try {
                    $id = $this->clas->insertGetId([
                        'class' => $request->input('class'),
                        'subject_id' => $request->input('subjects')[$i],
                        'teacher_id' => $request->input($request->input('subjects')[$i])
                    ]);
                    $result = $this->user->join('class', 'users.id', '=', 'class.teacher_id')->join('subjects', 'class.subject_id', '=', 'subjects.id')->where([['class.id', $id],['class.class', $request->input('class')]])->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name')->get();
                }
    			catch (Exception $e) {
                    Log::error($e->getMessage());
                }
    			return(json_encode($result));
    		}
    	}
    }

    public function update_teacher(Request $request)
    {
    	if ($request->filled('subject_id') && $request->filled('class_id') && $request->filled('teacher_id')) {
            try {
                $this->clas->where([
                    ['subject_id', $request->input('subject_id')],
                    ['class', $request->input('class_id')]
                ])->update(['teacher_id' => $request->input('teacher_id')]);
                $result = $this->user->where('id', $request->input('teacher_id'))->select('firstname')->get();
                $results = $this->task->where([
                    ['subject_id', $request->input('subject_id')],
                    ['class', $request->input('class_id')]
                ])->select('task_id')->get();
                foreach ($results as $result) {
                    $task_id = $result->task_id;
                }
                $this->teacher_task->where('task_id', $task_id)->update(['teacher_id' => $request->input('teacher_id')]);
            }
    		catch (Exception $e) {
                Log::error($e->getMessage());
            }
    		return(json_encode($result));
    	}
    }
}
