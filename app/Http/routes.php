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
    App::setLocale('zh');

    Route::get('/', 'IndexController@index')->name('index');
    Route::get('monitor_index', 'MonitorController@index')->name('monitor_index');
    Route::get('group_index', 'SendController@index')->name('monitor_index');
    Route::get('chart_index', 'ChartController@index')->name('chart_index');
    Route::get('system_index','SystemController@index')->name('system_index');
    Route::get('terminal_index','TerminalController@index')->name('terminal_index');
    Route::get('password_index','UserController@passwordIndex')->name('password_index');
    Route::get('member_index','UserController@memberIndex')->name('member_index');


    //用户监控相关路由
    Route::get('get_monitor_types', 'MonitorTypeController@getMonitorTypes')->name('get_monitor_types');
    Route::post('save_user_monitor', 'MonitorController@save')->name('save_user_monitor');
    Route::get('get_user_monitor_detail', 'MonitorController@detail')->name('get_user_monitor_detail');
    Route::post('del_user_monitor', 'MonitorController@delete')->name('del_user_monitor');
    Route::post('open_handle', 'MonitorController@openHandle')->name('open_handle');

    //通知组相关路由
    Route::get('get_groups', 'SendController@getGroup')->name('get_groups');
    Route::get('get_group_detail', 'SendController@getGroupDetail')->name('get_group_detail');
    Route::post('save_group', 'SendController@saveGroup')->name('save_group');
    Route::post('del_group', 'SendController@delGroup')->name('del_group');
    
    Route::post('save_member', 'SendController@saveMember')->name('save_member');
    Route::get('get_member_detail', 'SendController@getMemberDetail')->name('get_member_detail');
    Route::post('del_member', 'SendController@delMember')->name('del_member');
    
    //会员相关路由
    Route::get('user_setting','UserController@index')->name('user_setting');
    Route::post('upload_avatar','UserController@uploadAvatar')->name('upload_avatar');
    Route::post('save_user','UserController@save_user')->name('save_user');
    Route::get('user_confirm', 'UserController@confirm')->name('user_confirm');
    Route::post('save_pwd','UserController@savePwd')->name('save_pwd');

    //报表相关路由

    //系统信息相关路由
    Route::get('get_real_time_info', 'SystemController@getRealTimeInfo')->name('get_real_time_info');
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