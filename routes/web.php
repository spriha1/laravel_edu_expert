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
