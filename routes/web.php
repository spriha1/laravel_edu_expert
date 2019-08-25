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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/register', function () {
//     return view('register');
// });

// Route::get('/forgot_password', function () {
//     return view('forgot_password');
// });

Route::get('/', 'ProjectController@home');
Route::get('/register', 'ProjectController@register');
Route::get('/forgot_password', 'ProjectController@forgot_password');
Route::post('/register', 'AjaxController@register');
Route::post('/login', 'ProjectController@login');
Route::get('/logout', 'ProjectController@logout');

Route::post('/add_goals', 'AjaxController@add_goals');
Route::post('/update_goals', 'AjaxController@update_goals');
Route::post('/display_goals', 'AjaxController@display_goals');
Route::post('/remove_goals', 'AjaxController@remove_goals');

Route::get('/pending_requests', 'ProjectController@pending_requests');
Route::post('/pending_requests', 'ProjectController@post_pending_requests');

Route::get('/regd_users', 'ProjectController@regd_users');
Route::post('/regd_users', 'ProjectController@post_regd_users');

Route::get('/add_users/{id}', 'ProjectController@add_users');
Route::get('/remove_users/{id}', 'ProjectController@remove_users');
Route::get('/block_users/{id}', 'ProjectController@block_users');
Route::get('/unblock_users/{id}', 'ProjectController@unblock_users');

Route::get('/admin_dashboard', 'ProjectController@render_admin_dashboard');
Route::get('/teacher_dashboard', 'ProjectController@render_teacher_dashboard');
Route::get('/student_dashboard', 'ProjectController@render_student_dashboard');


// Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
