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
Route::get('/admin_dashboard', 'ProjectController@admin_dashboard');
Route::get('/teacher_dashboard', 'ProjectController@teacher_dashboard');
Route::get('/student_dashboard', 'ProjectController@student_dashboard');


// Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
