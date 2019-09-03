<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{UserType, SharedTimesheet, Holiday, TeacherTask, User, StudentTask};
class TimesheetController extends Controller
{
    public function add_shared_timesheets(Request $request)
    {
    	if ($request->filled('user_id') && $request->filled('date')) {
    		$date_format = $request->input('date_format');
        	$date = $request->input('date');
        	$date = date_to_timestamp($date_format, $date);

        	$results = UserType::join('users', 'users.user_type_id', '=', 'user_types.id')->where('users.id', $request->input('user_id'))->select('user_type')->get();
        	foreach($results as $result)
        	{
        		if ($result->user_type === 'Teacher') {
        			$users = UserType::join('users', 'users.user_type_id', '=', 'user_types.id')->where('user_types.user_type', 'Admin')->select('users.id')->get();
        			foreach ($users as $user)
        			{
        				$to_id = $user->id;
        			}
        		}
        		else {
                    $classes = User::where('id', $request->input('user_id'))->select('class')->get();
                    foreach($classes as $class)
                    {
                        $results = User::join('user_types', 'users.user_type_id', '=', 'user_types.id')
                        ->where([
                            ['user_types.user_type', 'Teacher'],
                            ['users.class', $class->class]
                        ])->select('users.id')->get();
                        foreach($results as $result)
                        {
                            $to_id = $result->id;
                        }
                    }
        		}
        	}
        	SharedTimesheet::insert([
        		'from_id' => $request->input('user_id'),
        		'to_id' => $to_id,
        		'of_date' => $date
        	]);
    	}
    }

    public function display_daily_timetable(Request $request)
    {
    	if ($request->filled('date') && $request->filled('user_id') && $request->filled('user_type')) {
    		$date_format = $request->input('date_format');
        	$date = $request->input('date');
        	$date = date_to_timestamp($date_format, $date);
			$dow = date('w', $date);
			$counter = 0;

			$holidays = Holiday::select()->get();
			foreach($holidays as $holiday)
			{
				if (!is_null($holiday->dow) && $dow == $holiday->dow) {
					$counter++;
				}
				else if (!is_null($holiday->start_date) && !is_null($holiday->end_date)) {
					if (($holiday->start_date <= $date) && ($holiday->end_date >= $date)) {
						$counter++;
					}
				}
			}
			if ($counter === 0) {
				if ($request->input('user_type') === 'teacher') {
					$result = TeacherTask::join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->where('teacher_id', $request->input('user_id'))
                    ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
					->select('task_id', 'class', 'name', 'total_time', 'on_date')->get();
				}
				else if ($request->input('user_type') === 'student') {
                    $result = StudentTask::join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                    ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                    ->where('student_id', $request->input('user_id'))
                    ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                    ->select('teacher_tasks.task_id', 'teacher.firstname', 'name', 'student_tasks.total_time', 'student_tasks.on_date')->get();
				}
				return(json_encode($result));
			}
    	}
    }

    public function display_timetable(Request $request)
    {
    	if ($request->filled('date') && $request->filled('user_id') && $request->filled('user_type')) {
    		$date_format = $request->input('date_format');
        	$date = $request->input('date');
        	$date = date_to_timestamp($date_format, $date);
        	$ts = $date;
			// calculate the number of days since Monday
			$dow = date('w', $ts);
			$offset = $dow - 1;
			if ($offset < 0) {
			    $offset = 6;
			}
			// calculate timestamp for the Monday
			$ts = $ts - $offset*86400;
			$week_dates = array();
			// loop from Monday till Sunday 
			for ($i = 0; $i < 7; $i++, $ts += 86400){
				array_push($week_dates, $ts);

			}
			
			$results = array();
			$res = array();
			$tasks = array();
			$arr = array();
			$week = $week_dates;
			$counter = 0;

			$holidays = Holiday::select()->get();
			$length = count($week_dates);
			for($i = 0; $i < $length; $i++) 
			{
				$dow = date('w', $week_dates[$i]);
				foreach ($holidays as $holiday)
				{
					if (!is_null($holiday->dow) && $dow == $holiday->dow) {
						$week_dates[$i] = 0;
					}
					else if (!is_null($holiday->start_date) && !is_null($holiday->end_date)) {
						if (($holiday->start_date <= $week_dates[$i]) && ($holiday->end_date >= $week_dates[$i])) {
							$week_dates[$i] = 0;
						}
					}
				}
			}
			if ($request->input('user_type') === 'teacher') {
				foreach ($week_dates as $date) {
					$result = TeacherTask::join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->where('teacher_id', $request->input('user_id'))
                    ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                    ->select('task_id', 'class', 'name')->get();
					foreach ($result as $key => $value) {
						array_push($tasks, $value['task_id']);
					}

				}
				$tasks = array_unique($tasks);
				foreach ($tasks as $task) {
					$result3 = TeacherTask::join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->where([
						['teacher_id', $request->input('user_id')],
						['teacher_tasks.task_id', '=', $task]
					])->select('task_id', 'class', 'name')->get();
					array_push($arr, $result3);
				}
				array_push($results, $arr);
				foreach ($tasks as $task) {
					$res = [];
					foreach ($week_dates as $date) {
						$result2 = TeacherTask::join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where([
							['teacher_id', $request->input('user_id')],
							['teacher_tasks.task_id', '=', $task]
						])
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('task_id', 'class', 'name')->get();
						array_push($res, $result2);
					}
					$results[$task] = $res;
				}
			}
			else if ($request->input('user_type') === 'student') {
                foreach ($week_dates as $date) {
                    $result = StudentTask::join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                    ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                    ->where('student_id', $request->input('user_id'))
                    ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                    ->select('student_tasks.task_id', 'teacher.firstname', 'name')->get();
                    foreach ($result as $key => $value) {
                        array_push($tasks, $value['task_id']);
                    }

                }
                $tasks = array_unique($tasks);
                foreach ($tasks as $task) {
                    $result3 = StudentTask::join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                    ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                    ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                    ->where([
                        ['student_id', $request->input('user_id')],
                        ['student_tasks.task_id', '=', $task]
                    ])->select('student_tasks.task_id', 'teacher.firstname', 'name')->get();
                    array_push($arr, $result3);
                }
                array_push($results, $arr);
                foreach ($tasks as $task) {
                    $res = [];
                    foreach ($week_dates as $date) {
                        $result2 = StudentTask::join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                        ->where([
                            ['student_id', $request->input('user_id')],
                            ['student_tasks.task_id', '=', $task]
                        ])
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('student_tasks.task_id', 'teacher.firstname', 'name')->get();
                        array_push($res, $result2);
                    }
                    $results[$task] = $res;
                }

			}
			$results['dates'] = $week_dates;
			$results['original_dates'] = $week;
			return(json_encode($results));
    	}
    }

    public function teacher_timesheets()
    {
    	$results = SharedTimesheet::join('users', 'shared_timesheets.from_id', '=', 'users.id')->where('to_id', Auth::id())->select('from_id', 'of_date', 'firstname', 'username')->get();
    	return view('teacher_timesheets', [
    		'results' => $results
    	]);
    }

    public function student_timesheets()
    {
        $results = SharedTimesheet::join('users', 'shared_timesheets.from_id', '=', 'users.id')->where('to_id', Auth::id())->select('from_id', 'of_date', 'firstname', 'username')->get();
        return view('student_timesheets', [
            'results' => $results
        ]);
    }

    public function fetch_timesheet(Request $request)
    {
        if ($request->filled('from_id') && $request->filled('of_date') && $request->filled('user_type')) {
            if ($request->input('user_type') === 'teacher') {
                $result = TeacherTask::join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')->join('subjects', 'subjects.id', '=', 'tasks.subject_id')->where([
                    ['teacher_id', $request->input('from_id')],
                    ['start_date', '<=', $request->input('of_date')],
                    ['end_date', '>=', $request->input('of_date')]
                ])->select('name', 'class', 'total_time')->get();
            }
            else if ($request->input('user_type') === 'student') {
                $result = StudentTask::join('tasks', 'tasks.id', '=', 'student_tasks.task_id')->join('subjects', 'subjects.id', '=', 'tasks.subject_id')->where([
                    ['student_id', $request->input('from_id')],
                    ['start_date', '<=', $request->input('of_date')],
                    ['end_date', '>=', $request->input('of_date')]
                ])->select('name', 'total_time')->get();
            }
            return(json_encode($result));
        }
    }

    public function add_completion_time(Request $request)
    {
        if ($request->filled('task_id') && $request->filled('user_id') && $request->filled('time') && $request->filled('user_type')) {
            $date_format = $request->input('date_format');
            $date = $request->input('date');
            $date = date_to_timestamp($date_format, $date);
            if ($request->input('user_type') === 'teacher') {
                $results = TeacherTask::where([
                    ['task_id', $request->input('task_id')],
                    ['teacher_id', $request->input('user_id')]
                ])->select()->get();
                foreach($results as $result) 
                {
                    if ($result->on_date == $date) {
                        TeacherTask::where([
                            ['task_id', $request->input('task_id')],
                            ['teacher_id', $request->input('user_id')],
                            ['on_date', $date]
                        ])->update(['total_time' => $request->input('time')]);
                    }
                }
            }
            else if ($request->input('user_type') === 'student') {
                $results = StudentTask::where([
                    ['task_id', $request->input('task_id')],
                    ['student_id', $request->input('user_id')]
                ])->select()->get();
                foreach($results as $result) 
                {
                    if ($result->on_date == 0 && $result->total_time == 0) {
                        StudentTask::where([
                            ['task_id', $request->input('task_id')],
                            ['student_id', $request->input('user_id')]
                        ])->update(['total_time' => $request->input('time'), 'on_date' => $date]);
                    }
                    else if ($result->on_date == $date) {
                        StudentTask::where([
                            ['task_id', $request->input('task_id')],
                            ['student_id', $request->input('user_id')],
                            ['on_date', $date]
                        ])->update(['total_time' => $request->input('time')]);
                    }
                    else {
                        StudentTask::insert([
                            'task_id' => $request->input('task_id'),
                            'student_id' => $request->input('user_id'),
                            'on_date' => $date,
                            'total_time' => $request->input('time')
                        ]);
                    }
                }
            }
        }
    }

    public function update_completion_time(Request $request)
    {
        if ($request->filled('time') && $request->filled('date') && $request->filled('task_id') && $request->filled('user_id') && $request->filled('user_type')) {
            if ($request->input('user_type') === 'teacher') {
                TeacherTask::where([
                    ['task_id', $request->input('task_id')],
                    ['teacher_id', $request->input('user_id')]
                ])
                ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$request->input('date').'))')
                ->update([
                    'total_time' => $request->input('time')
                ]);
            }
            else if ($request->input('user_type') === 'student') {
                StudentTask::where([
                    ['task_id', $request->input('user_type')],
                    ['student_id', $request->input('user_id')],
                    ['on_date', $request->input('date')]
                ])->update([
                    'total_time' => $request->input('time')
                ]);
            }
        }
    }
}
