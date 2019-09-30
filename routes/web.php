<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('CheckLoginStatus')->group(function() {
    Route::get('/', 'ProjectController@home');
    Route::get('/register', 'UserController@register');
    Route::get('/forgot_password', 'ProjectController@forgot_password');
});

Route::get('/login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('/login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/fetch_info', 'AjaxController@fetch_info');
Route::post('/register', 'AjaxController@register');
Route::post('/login', 'UserController@login');
Route::get('/logout', 'ProjectController@logout');
Route::get('/verify_mail/{code}', 'ProjectController@verify_mail');
Route::get('/update_mail/{hash}/{email}', 'ProjectController@update_mail');
Route::get('/reset_password_form/{token}/{expiry_time}', 'ProjectController@reset_password_form');
Route::post('/reset_password', 'ProjectController@reset_password');
Route::post('/forgot_password', 'ProjectController@send_password_mail');

Route::middleware('auth')->group(function () {
    Route::middleware('CheckAdmin')->group(function() {
        Route::get('/admin_dashboard', 'DashboardController@render_admin_dashboard');
        Route::view('/system_management', 'system_management');
        Route::view('/manage_subjects', 'manage_subjects');
        Route::post('/add_subject', 'SubjectsController@add_subject');
        Route::post('/remove_subject', 'SubjectsController@remove_subject');
        Route::get('/display_subjects', 'SubjectsController@display_subjects');
        Route::view('/holiday', 'holiday');
        Route::post('/add_holiday', 'AjaxController@add_holiday');
        Route::get('/pending_requests', 'UserController@pending_requests');
        Route::post('/get_pending_requests', 'UserController@get_pending_requests');
        Route::post('/pending_requests', 'UserController@post_pending_requests');
        Route::get('/regd_users', 'UserController@regd_users');
        Route::post('/get_regd_users', 'UserController@get_regd_users');
        Route::post('/regd_users', 'UserController@post_regd_users');
        Route::get('/manage_class', 'ClassController@render_view');
        Route::get('/display_class', 'ClassController@display_class');
        Route::post('/fetch_teachers', 'ClassController@fetch_teachers');
        Route::post('/add_class', 'ClassController@add_class');
        Route::post('/remove_class', 'ClassController@remove_class');
        Route::post('/fetch_class_details', 'ClassController@fetch_class_details');
        Route::post('/remove_class_subject', 'ClassController@remove_class_subject');
        Route::post('/add_class_subject', 'ClassController@add_class_subject');
        Route::post('/update_teacher', 'ClassController@update_teacher');
        Route::get('/task_management', 'ProjectController@task_management');
        Route::post('/add_timetable', 'ProjectController@add_timetable');
        Route::post('/fetch_teacher_class', 'AjaxController@fetch_teacher_class');
        Route::post('/fetch_teacher_class_subjects', 'AjaxController@fetch_teacher_class_subjects');
        Route::get('/add_users/{id}', 'UserController@add_users');
        Route::get('/remove_users/{id}', 'UserController@remove_users');
        Route::get('/change_user_type/{id}/{type}', 'UserController@change_user_type');
        Route::post('/update_currency', 'CurrencyController@update_currency');
        Route::post('/convert_currency', 'CurrencyController@convert_currency');
        Route::get('/fetch_currency', 'CurrencyController@fetch_currency');
        Route::post('/stripe_payment', 'StripePaymentController@stripe');
        Route::post('/post_stripe_payment', 'StripePaymentController@post_stripe');
        Route::post('/post_timesheets', 'TimesheetController@post_timesheets');
        Route::get('/timesheets', 'TimesheetController@timesheets');
        Route::get('/stripe_account_details', 'StripePaymentController@stripe_account_details');
        Route::post('/send_mails', 'FilesController@send_mails');
    });

    Route::middleware('CheckTeacher')->group(function() {
        Route::get('/teacher_dashboard', 'DashboardController@render_teacher_dashboard');
        Route::view('/daily_teacher_timetable', 'daily_teacher_timetable');
        Route::view('/weekly_teacher_timetable', 'weekly_teacher_timetable');
    });

    Route::middleware('CheckStudent')->group(function() {
        Route::get('/student_dashboard', 'DashboardController@render_student_dashboard');
        Route::view('/daily_student_timetable', 'daily_student_timetable');
        Route::view('/weekly_student_timetable', 'weekly_student_timetable');
    });

    Route::get('/profile', 'UserController@profile');
    Route::post('/update_profile', 'AjaxController@update_profile');
    Route::post('/fetch_subjects', 'ProjectController@fetch_subjects');
    Route::post('/add_shared_timesheets', 'TimesheetController@add_shared_timesheets');
    Route::post('/display_daily_timetable', 'TimesheetController@display_daily_timetable');
    Route::post('/display_timetable', 'TimesheetController@display_timetable');
    Route::get('/teacher_timesheets', 'TimesheetController@teacher_timesheets');
    Route::get('/student_timesheets', 'TimesheetController@student_timesheets');
    Route::post('/fetch_timesheet', 'TimesheetController@fetch_timesheet');
    Route::post('/add_completion_time', 'TimesheetController@add_completion_time');
    Route::post('/update_completion_time', 'TimesheetController@update_completion_time');
    Route::post('/add_goals', 'AjaxController@add_goals');
    Route::post('/update_goals', 'AjaxController@update_goals');
    Route::post('/display_goals', 'AjaxController@display_goals');
    Route::post('/remove_goals', 'AjaxController@remove_goals');
    Route::post('/update_request_status', 'AjaxController@update_request_status');
    Route::get('/fetch_request_status', 'AjaxController@fetch_request_status');
    Route::post('/accept_request_status', 'AjaxController@accept_request_status');
    Route::post('/reject_request_status', 'AjaxController@reject_request_status');
    Route::get('/upload', 'FilesController@upload');
    Route::post('/post_upload', 'FilesController@post_upload');
});

