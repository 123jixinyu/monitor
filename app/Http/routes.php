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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'IndexController@index')->name('index');
    
    Route::get('get_monitor_types','MonitorTypeController@getMonitorTypes')->name('get_monitor_types');
});

// 认证路由...
Route::get('auth/login', 'Auth\AuthController@getLogin')->name('auth_login');
Route::post('auth/login', 'Auth\AuthController@postLogin')->name('post_auth_login');
Route::get('auth/logout', 'Auth\AuthController@getLogout')->name('auth_logout');
// 注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister')->name('auth_register');
Route::post('auth/register', 'Auth\AuthController@postRegister')->name('post_auth_register');

// 密码重置链接请求路由...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
// 密码重置路由...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');