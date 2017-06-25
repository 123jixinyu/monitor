<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', function () {return view('index');});
Route::get('login', function () {return view('auth.login');});
Route::get('register', function () {return view('auth.register');});
Route::get('reset', function () {return view('auth.reset')->with([
    'token'=>'xx'
]);});
Route::get('logout', function(){return 'wait dev';})->name('monitor_logout');
