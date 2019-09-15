<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Subject, Clas, User, Task, TeacherTask};
use Illuminate\Support\Facades\Log;
use Exception;

class ClassController extends Controller
{
    protected $subject, $clas, $user, $task, $teacher_task;

    public function __construct()
    {
        $this->user         = new User;
        $this->subject      = new Subject;
        $this->clas         = new Clas;
        $this->task         = new Task;
        $this->teacher_task = new TeacherTask;
    }

    /**
    * 
    * @method render_view() 
    * 
    * @param void
    * @return string [html view of manage_class page]
    * Desc : This method returns the view of manage_class page
    */

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

    /**
    * 
    * @method display_class() 
    * 
    * @param void
    * @return json 
    * Desc : This method fetches and displays the disinct classes from the database
    */

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

    /**
    * 
    * @method fetch_teachers() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the list of teachers corresponding to a particular subject
    */

    public function fetch_teachers(Request $request)
    {
        if ($request->filled('subject_id')) {
            try {
                $teachers = $this->user
                ->join('teacher_subjects', 'users.id', '=', 'teacher_subjects.teacher_id')
                ->where('subject_id', $request->input('subject_id'))
                ->select('users.id', 'firstname')
                ->get();
                return(json_encode($teachers));
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    /**
    * 
    * @method add_class() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method adds details of a class to the database and returns the id of the same
    */

    public function add_class(Request $request)
    {
        if ($request->filled('class') && $request->filled('subjects')) {
            $length = count($request->input('subjects'));
            for ($index = 0; $index < $length; $index++) {
                try {
                    $id = $this->clas->insertGetId([
                        'class'      => $request->input('class'),
                        'subject_id' => $request->input('subjects')[$index],
                        'teacher_id' => $request->input($request->input('subjects')[$index])
                    ]);
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            try{
                $result = $this->clas->where('id', $id)
                            ->select('class')
                            ->get();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return(json_encode($result));
        }
    }

    /**
    * 
    * @method remove_class() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method deletes a class from the database
    */

    public function remove_class(Request $request)
    {
        if ($request->filled('class_id'))
        {
            try {
                $this->clas->where('class', $request->input('class_id'))
                ->delete();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    /**
    * 
    * @method fetch_class_details() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the subjects and teachers associated with a class
    */

    public function fetch_class_details(Request $request)
    {
        if ($request->filled('class')) {
            try {
                $result = $this->user
                ->join('class', 'users.id', '=', 'class.teacher_id')
                ->join('subjects', 'class.subject_id' , '=', 'subjects.id')
                ->where('class.class', $request->input('class'))
                ->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name')
                ->get();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return(json_encode($result));
        }
    }

    /**
    * 
    * @method remove_class_subject() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method deletes the record for a specific class and subject
    */

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

    /**
    * 
    * @method add_class_subject() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method inserts a record for a specific class and subject
    */

    public function add_class_subject(Request $request)
    {
        if ($request->filled('class') && $request->filled('subjects')) {
            $length = count($request->input('subjects'));
            for ($index = 0; $index < $length; $index++) {
                try {
                    $id = $this->clas->insertGetId([
                        'class'      => $request->input('class'),
                        'subject_id' => $request->input('subjects')[$index],
                        'teacher_id' => $request->input($request->input('subjects')[$index])
                    ]);
                    $result = $this->user
                    ->join('class', 'users.id', '=', 'class.teacher_id')
                    ->join('subjects', 'class.subject_id', '=', 'subjects.id')
                    ->where([
                        ['class.id', $id],
                        ['class.class', $request->input('class')]
                    ])->select('users.id as userid', 'firstname', 'subjects.id as subjectid', 'class.class', 'name')
                    ->get();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                return(json_encode($result));
            }
        }
    }

    /**
    * 
    * @method update_teacher() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method updates a teacher for a class and subject and returns the id of the same
    */

    public function update_teacher(Request $request)
    {
        if ($request->filled('subject_id') && $request->filled('class_id') && $request->filled('teacher_id')) {
            try {
                $this->clas->where([
                    ['subject_id', $request->input('subject_id')],
                    ['class', $request->input('class_id')]
                ])->update(['teacher_id' => $request->input('teacher_id')]);
                $result = $this->user->where('id', $request->input('teacher_id'))
                            ->select('firstname')
                            ->get();
                $results = $this->task->where([
                    ['subject_id', $request->input('subject_id')],
                    ['class', $request->input('class_id')]
                ])->select('id')
                ->get();
                foreach ($results as $res) {
                    $task_id = $res->id;
                    $this->teacher_task->where('task_id', $task_id)
                    ->update(['teacher_id' => $request->input('teacher_id')]);
                }
            }
            
            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return(json_encode($result));
        }
    }
}
