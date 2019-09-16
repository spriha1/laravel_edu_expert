<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{UserType, SharedTimesheet, Holiday, TeacherTask, User, StudentTask, Tax, Currency};
use Illuminate\Support\Facades\Log;
use Exception;

class TimesheetController extends Controller
{
    protected $user_type, $shared_timesheet, $holiday, $teacher_task, $user, $student_task, $tax, $currency;

    public function __construct()
    {
        $this->user_type        = new UserType;
        $this->shared_timesheet = new SharedTimesheet;
        $this->holiday          = new Holiday;
        $this->teacher_task     = new TeacherTask;
        $this->user             = new User;
        $this->student_task     = new StudentTask;
        $this->tax              = new Tax;
        $this->currency         = new Currency;
    }

    /**
    * 
    * @method add_shared_timesheets() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method adds a record in the database table when the user shares their timesheet
    */

    public function add_shared_timesheets(Request $request)
    {
        if ($request->filled('user_id') && $request->filled('date')) {
            $date_format = $request->input('date_format');
            $date        = $request->input('date');
            $date        = date_to_timestamp($date_format, $date);
            try {
                $results = $this->user_type
                            ->join('users', 'users.user_type_id', '=', 'user_types.id')
                            ->where('users.id', $request->input('user_id'))
                            ->select('user_type')
                            ->get();
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }

            foreach ($results as $result) {
                if ($result->user_type === 'Teacher') {
                    try {
                        $users = $this->user_type
                                ->join('users', 'users.user_type_id', '=', 'user_types.id')
                                ->where('user_types.user_type', 'Admin')
                                ->select('users.id')
                                ->first();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    $to_id = $users['id'];
                }

                else if ($result->user_type === 'Student') {
                    try{
                        $classes = $this->user->where('id', $request->input('user_id'))
                                ->select('class')
                                ->first();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    try {
                        $results = $this->user->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                        ->where([
                            ['user_types.user_type', 'Teacher'],
                            ['users.class', $classes['class']]
                        ])->select('users.id')->first();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    $to_id = $results['id'];
                }
            }

            try {
                $this->shared_timesheet->insert([
                    'from_id' => $request->input('user_id'),
                    'to_id'   => $to_id,
                    'of_date' => $date
                ]);
            }

            catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    /**
    * 
    * @method display_daily_timetable() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the task details for a user on a particular date
    */

    public function display_daily_timetable(Request $request)
    {
        if ($request->filled('date') && $request->filled('user_id') && $request->filled('user_type')) {
            $date_format = $request->input('date_format');
            $date        = $request->input('date');
            $date        = date_to_timestamp($date_format, $date);
            $dow         = date('w', $date);
            $counter     = 0;
            $holidays    = $this->holiday->select()->get();
            foreach ($holidays as $holiday) {
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
                    try {
                        $result = $this->teacher_task->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where('teacher_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('task_id', 'class', 'name', 'total_time', 'on_date')->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }
                }

                else if ($request->input('user_type') === 'student') {
                    try {
                        $result = $this->student_task->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                        ->where('student_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(student_tasks.on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('teacher_tasks.task_id', 'teacher.firstname', 'name', 'student_tasks.total_time', 'student_tasks.on_date')->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }
                }

                return(json_encode($result));
            }
        }
    }

    /**
    * 
    * @method display_timetable() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method fetches and returns the task details for a user on a particular week
    */

    public function display_timetable(Request $request)
    {
        if ($request->filled('date') && $request->filled('user_id') && $request->filled('user_type')) {
            $date_format = $request->input('date_format');
            $date        = $request->input('date');
            $date        = date_to_timestamp($date_format, $date);
            $ts          = $date;
            // calculate the number of days since Monday
            $dow         = date('w', $ts);
            $offset      = $dow - 1;
            if ($offset < 0) {
                $offset = 6;
            }

            // calculate timestamp for the Monday
            $ts         = $ts - $offset * 86400;
            $week_dates = array();
            // loop from Monday till Sunday 
            for ($index = 0; $index < 7; $index++, $ts += 86400) {
                array_push($week_dates, $ts);
            }

            $results  = array();
            $res      = array();
            $tasks    = array();
            $arr      = array();
            $week     = $week_dates;
            $counter  = 0;
            $holidays = $this->holiday->select()->get();
            $length   = count($week_dates);
            for ($index = 0; $index < $length; $index++) {
                $dow = date('w', $week_dates[$index]);
                foreach ($holidays as $holiday) {
                    if (!is_null($holiday->dow) && $dow == $holiday->dow) {
                        $week_dates[$index] = 0;
                    }

                    else if (!is_null($holiday->start_date) && !is_null($holiday->end_date)) {
                        if (($holiday->start_date <= $week_dates[$index]) && ($holiday->end_date >= $week_dates[$index])) {
                            $week_dates[$index] = 0;
                        }
                    }
                }
            }

            if ($request->input('user_type') === 'teacher') {
                foreach ($week_dates as $date) {
                    try {
                        $result = $this->teacher_task
                        ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where('teacher_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('task_id', 'class', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    foreach ($result as $key => $value) {
                        array_push($tasks, $value['task_id']);
                    }
                }

                $tasks = array_unique($tasks);
                foreach ($tasks as $task) {
                    try {
                        $result3 = $this->teacher_task
                        ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where([
                            ['teacher_id', $request->input('user_id')],
                            ['teacher_tasks.task_id', '=', $task]
                        ])->select('task_id', 'class', 'name')->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    array_push($arr, $result3);
                }
                
                array_push($results, $arr);
                foreach ($tasks as $task) {
                    $res = [];
                    foreach ($week_dates as $date) {
                        try {
                            $result2 = $this->teacher_task
                            ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                            ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                            ->where([
                                ['teacher_id', $request->input('user_id')],
                                ['teacher_tasks.task_id', '=', $task]
                            ])
                            ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                            ->select('task_id', 'class', 'name', 'on_date', 'total_time')
                            ->get();
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }

                        array_push($res, $result2);
                    }
                    $results[$task] = $res;
                }
            }

            else if ($request->input('user_type') === 'student') {
                foreach ($week_dates as $date) {
                    try {
                        $result = $this->student_task
                        ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                        ->where('student_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('student_tasks.task_id', 'teacher.firstname', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    foreach ($result as $key => $value) {
                        array_push($tasks, $value['task_id']);
                    }
                }
                $tasks = array_unique($tasks);
                foreach ($tasks as $task) {
                    try {
                        $result3 = $this->student_task
                        ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                        ->where([
                            ['student_id', $request->input('user_id')],
                            ['student_tasks.task_id', '=', $task]
                        ])->select('student_tasks.task_id', 'teacher.firstname', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    array_push($arr, $result3);
                }

                array_push($results, $arr);
                foreach ($tasks as $task) {
                    $res = [];
                    foreach ($week_dates as $date) {
                        try {
                            $result2 = $this->student_task
                            ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                            ->join('teacher_tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                            ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                            ->join('users as teacher', 'teacher.id', '=', 'teacher_tasks.teacher_id')
                            ->where([
                                ['student_id', $request->input('user_id')],
                                ['student_tasks.task_id', '=', $task]
                            ])
                            ->whereRaw('DATE(FROM_UNIXTIME(student_tasks.on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                            ->select('student_tasks.task_id', 'teacher.firstname', 'name', 'student_tasks.on_date', 'student_tasks.total_time')
                            ->get();
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }

                        array_push($res, $result2);
                    }
                    $results[$task] = $res;
                }
            }
            $results['dates']          = $week_dates;
            $results['original_dates'] = $week;
            return(json_encode($results));
        }
    }

    /**
    * 
    * @method teacher_timesheets() 
    * 
    * @param void
    * @return string [html view of teacher_timesheets page] 
    * Desc : This method returns the view of teacher_timesheets page
    */

    public function teacher_timesheets()
    {
        try {
            $results = $this->shared_timesheet
            ->join('users', 'shared_timesheets.from_id', '=', 'users.id')
            ->where('to_id', Auth::id())
            ->select('from_id', 'of_date', 'firstname', 'username')
            ->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('teacher_timesheets', [
            'results' => $results
        ]);
    }

    /**
    * 
    * @method student_timesheets() 
    * 
    * @param void
    * @return string [html view of student_timesheets page] 
    * Desc : This method returns the view of student_timesheets page
    */

    public function student_timesheets()
    {
        try {
            $results = $this->shared_timesheet
            ->join('users', 'shared_timesheets.from_id', '=', 'users.id')
            ->where('to_id', Auth::id())
            ->select('from_id', 'of_date', 'firstname', 'username')
            ->get();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('student_timesheets', [
            'results' => $results
        ]);
    }

    /**
    * 
    * @method timesheets() 
    * 
    * @param void
    * @return string [html view of timesheets page] 
    * Desc : This method returns the view of timesheets page
    */

    public function timesheets()
    {
        try {
            $users = $this->user
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->where('user_type', '=', 'Teacher')
            ->select('firstname', 'email', 'users.id', 'user_type', 'rate')
            ->get();
            $tax = $this->tax->where('name', 'GST')->select('percentage')->first();
            $currency = $this->user
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->join('currencies', 'users.currency_id', '=', 'currencies.id')
            ->where('user_type', 'Admin')->first();
        }

        catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return view('timesheets', [
            'users'    => $users,
            'tax'      => $tax['percentage'],
            'currency' => $currency
        ]);
    }

    /**
    * 
    * @method post_timesheets() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method returns the weekly timesheet deatils for different users on a particulatr date
    */

    public function post_timesheets(Request $request)
    {
        if ($request->filled('date') && $request->filled('user_id') && $request->filled('user_type')) {
            $date_format = $request->input('date_format');
            $date        = $request->input('date');
            $date        = date_to_timestamp($date_format, $date);
            $ts          = $date;
            // calculate the number of days since Monday
            $dow = date('w', $ts);
            $offset = $dow - 1;
            if ($offset < 0) {
                $offset = 6;
            }

            // calculate timestamp for the Monday
            $ts         = $ts - $offset*86400;
            $week_dates = array();
            // loop from Monday till Sunday 
            for ($index = 0; $index < 7; $index++, $ts += 86400) {
                array_push($week_dates, $ts);
            }

            $results  = array();
            $res      = array();
            $tasks    = array();
            $arr      = array();
            $week     = $week_dates;
            $counter  = 0;
            $holidays = $this->holiday->select()->get();
            $length   = count($week_dates);
            for ($index = 0; $index < $length; $index++) {
                $dow = date('w', $week_dates[$index]);
                foreach ($holidays as $holiday) {
                    if (!is_null($holiday->dow) && $dow == $holiday->dow) {
                        $week_dates[$index] = 0;
                    }

                    else if (!is_null($holiday->start_date) && !is_null($holiday->end_date)) {
                        if (($holiday->start_date <= $week_dates[$index]) && ($holiday->end_date >= $week_dates[$index])) {
                            $week_dates[$index] = 0;
                        }
                    }
                }
            }

            if ($request->input('user_type') === 'Teacher') {
                foreach ($week_dates as $date) {
                    try {
                        $result = $this->teacher_task
                        ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where('teacher_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('task_id', 'class', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    foreach ($result as $key => $value) {
                        array_push($tasks, $value['task_id']);
                    }
                }

                $tasks = array_unique($tasks);
                foreach ($tasks as $task) {
                    try {
                        $result3 = $this->teacher_task
                        ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where([
                            ['teacher_id', $request->input('user_id')],
                            ['teacher_tasks.task_id', '=', $task]
                        ])->select('task_id', 'class', 'name')->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    array_push($arr, $result3);
                }

                array_push($results, $arr);
                foreach ($tasks as $task) {
                    $res = [];
                    foreach ($week_dates as $date) {
                        try {
                            $result2 = $this->teacher_task
                            ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                            ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                            ->where([
                                ['teacher_id', $request->input('user_id')],
                                ['teacher_tasks.task_id', '=', $task]
                            ])
                            ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                            ->select('task_id', 'class', 'name', 'on_date', 'total_time')
                            ->get();
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }

                        array_push($res, $result2);
                    }
                    $results[$task] = $res;
                }
            }

            else if ($request->input('user_type') === 'Student') {
                foreach ($week_dates as $date) {
                    try {
                        $result = $this->student_task
                        ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where('student_id', $request->input('user_id'))
                        ->whereRaw('DATE(FROM_UNIXTIME(start_date)) <= DATE(FROM_UNIXTIME('.$date.')) && DATE(FROM_UNIXTIME(end_date)) >= DATE(FROM_UNIXTIME('.$date.'))')
                        ->select('task_id', 'class', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    foreach ($result as $key => $value) {
                        array_push($tasks, $value['task_id']);
                    }
                }

                $tasks = array_unique($tasks);
                foreach ($tasks as $task) {
                    try {
                        $result3 = $this->student_task
                        ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                        ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                        ->where([
                            ['student_id', $request->input('user_id')],
                            ['student_tasks.task_id', '=', $task]
                        ])->select('task_id', 'class', 'name')
                        ->get();
                    }

                    catch (Exception $e) {
                        Log::error($e->getMessage());
                    }

                    array_push($arr, $result3);
                }

                array_push($results, $arr);
                foreach ($tasks as $task) {
                    $res = [];
                    foreach ($week_dates as $date) {
                        try {
                            $result2 = $this->student_task
                            ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                            ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
                            ->where([
                                ['student_id', $request->input('user_id')],
                                ['student_tasks.task_id', '=', $task]
                            ])
                            ->whereRaw('DATE(FROM_UNIXTIME(student_tasks.on_date)) = DATE(FROM_UNIXTIME('.$date.'))')
                            ->select('task_id', 'class', 'name', 'on_date', 'total_time')
                            ->get();
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }
                        array_push($res, $result2);
                    }
                    $results[$task] = $res;
                }
            }

            $results['dates']          = $week_dates;
            $results['original_dates'] = $week;
            return(json_encode($results));
        }
    }

    /**
    * 
    * @method fetch_timesheets() 
    * 
    * @param Request object
    * @return json 
    * Desc : This method returns the weekly timesheet deatils for a particular user on a particulatr date
    */

    public function fetch_timesheet(Request $request)
    {
        if ($request->filled('from_id') && $request->filled('of_date') && $request->filled('user_type')) {
            if ($request->input('user_type') === 'teacher') {
                try {
                    $result = $this->teacher_task
                    ->join('tasks', 'tasks.id', '=', 'teacher_tasks.task_id')
                    ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
                    ->where([
                        ['teacher_id', $request->input('from_id')],
                        ['start_date', '<=', $request->input('of_date')],
                        ['end_date', '>=', $request->input('of_date')]
                    ])->select('name', 'class', 'total_time')
                    ->get();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            else if ($request->input('user_type') === 'student') {
                try {
                    $result = $this->student_task
                    ->join('tasks', 'tasks.id', '=', 'student_tasks.task_id')
                    ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
                    ->where([
                        ['student_id', $request->input('from_id')],
                        ['start_date', '<=', $request->input('of_date')],
                        ['end_date', '>=', $request->input('of_date')]
                    ])->select('name', 'total_time')
                    ->get();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
            return(json_encode($result));
        }
    }

    /**
    * 
    * @method add_completion_time() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method adds the total time spent by a user on the assigned task
    */

    public function add_completion_time(Request $request)
    {
        if ($request->filled('task_id') && $request->filled('user_id') && $request->filled('time') && $request->filled('user_type')) {
            $date_format = $request->input('date_format');
            $date        = $request->input('date');
            $date        = date_to_timestamp($date_format, $date);
            if ($request->input('user_type') === 'teacher') {
                try {
                    $results = $this->teacher_task->where([
                        ['task_id', $request->input('task_id')],
                        ['teacher_id', $request->input('user_id')]
                    ])->select()->get();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                foreach ($results as $result) {
                    if ($result->on_date == $date) {
                        try {
                            $this->teacher_task->where([
                                ['task_id', $request->input('task_id')],
                                ['teacher_id', $request->input('user_id')],
                                ['on_date', $date]
                            ])->update(['total_time' => $request->input('time')]);
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }
                    }
                }
            }

            else if ($request->input('user_type') === 'student') {
                try {
                    $results = $this->student_task->where([
                        ['task_id', $request->input('task_id')],
                        ['student_id', $request->input('user_id')]
                    ])->select()->get();
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                foreach ($results as $result) {
                    if ($result->on_date == 0 && $result->total_time == 0) {
                        try {
                            $this->student_task->where([
                                ['task_id', $request->input('task_id')],
                                ['student_id', $request->input('user_id')]
                            ])->update(['total_time' => $request->input('time'), 'on_date' => $date]);
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }
                    }

                    else if ($result->on_date == $date) {
                        try {
                            $this->student_task->where([
                                ['task_id', $request->input('task_id')],
                                ['student_id', $request->input('user_id')],
                                ['on_date', $date]
                            ])->update(['total_time' => $request->input('time')]);
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }
                    }

                    else {
                        try {
                            $this->student_task->insert([
                                'task_id'    => $request->input('task_id'),
                                'student_id' => $request->input('user_id'),
                                'on_date'    => $date,
                                'total_time' => $request->input('time')
                            ]);
                        }

                        catch (Exception $e) {
                            Log::error($e->getMessage());
                        }
                    }
                }
            }
        }
    }

    /**
    * 
    * @method update_completion_time() 
    * 
    * @param Request object
    * @return void 
    * Desc : This method updates the total time spent by a user on the assigned task
    */

    public function update_completion_time(Request $request)
    {
        if ($request->filled('time') && $request->filled('date') && $request->filled('task_id') && $request->filled('user_id') && $request->filled('user_type')) {
            if ($request->input('user_type') === 'teacher') {
                try {
                    $this->teacher_task->where([
                        ['task_id', $request->input('task_id')],
                        ['teacher_id', $request->input('user_id')]
                    ])
                    ->whereRaw('DATE(FROM_UNIXTIME(on_date)) = DATE(FROM_UNIXTIME('.$request->input('date').'))')
                    ->update([
                        'total_time' => $request->input('time')
                    ]);
                }

                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            else if ($request->input('user_type') === 'student') {
                try {
                    $this->student_task->where([
                        ['task_id', $request->input('user_type')],
                        ['student_id', $request->input('user_id')],
                        ['on_date', $request->input('date')]
                    ])->update([
                        'total_time' => $request->input('time')
                    ]);
                }
                
                catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
