<?php

namespace App\Http\Controllers;

use App\Entities\Status;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.setting');
    }

    /**
     * 头像上传
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|max:10000|mimes:jpg,jpeg,png'
        ]);
        if ($validator->fails()) {
            return back();
        }
        $avatar = $request->file('avatar');
        $directory = 'uploads/' . date('Y-m-d', time());
        $filename = uniqid();
        $path = $avatar->move($directory, $filename);
        $user = Auth::user();
        $user->avatar = $path;
        $user->save();
        return back();
    }

    /**
     * 保存用户信息
     * @param Request $request
     * @return string
     */
    public function save_user(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'experience' => 'between:1,50',
            'skills' => 'between:1,50'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $user = Auth::user();
        $user->experience = array_get($params, 'experience');
        $user->skills = array_get($params, 'skills');
        $user->save();
        return api_response('200', 'success');
    }

    /**
     * 确认报警信息
     * @param Request $request
     * @return string
     */
    public function confirm(Request $request)
    {
        $status = Status::where('user_id', Auth::user()->id)->first();
        if (!$status) {
            return api_response('200', 'success');
        }
        $status->confirm_times = $status->err_times;
        $status->save();
        return api_response('200', 'success');
    }

    /**
     * 显示修改密码页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordIndex()
    {
        return view('user.password');
    }

    /**
     * 保存密码
     * @param Request $request
     * @return string
     */
    public function savePwd(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'old' => 'required',
            'new_pwd' => 'required|between:6,16|confirmed',
        ], [
            'old.required' => '原密码必须填写',
            'new_pwd.required' => '新密码必须填写',
            'new_pwd.between' => '新密码长度必须在6～16位之间',
            'new_pwd.confirmed' => '重复密码不正确',
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $old = array_get($params, 'old');
        $new_pwd = array_get($params, 'new_pwd');
        if (!Hash::check($old, Auth::user()->password)) {
            return api_response('400', '原有密码错误');
        }
        $user = Auth::user();
        $user->password = Hash::make($new_pwd);
        $user->save();
        return api_response('200', 'success');
    }

    /**
     * 显示成员列表页
     * @return $this
     */
    public function memberIndex()
    {
        return view('user.member')->with([
            'members' => User::where('privilege', User::ADMIN_NO)->orderBy('created_at', 'desc')->paginate()
        ]);
    }

    /**
     * 设置登录状态
     * @param Request $request
     * @return string
     */
    public function setLoginStatus(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', '请求设置的成员不存在');
        }
        $user = Auth::user();
        if ($user->privilege != User::ADMIN_YES) {
            return api_response('400', '非法请求');
        }
        $id = array_get($params, 'id');
        $member = User::find($id);
        $member->login_status = $member->login_status == User::LOGIN_STATUS_NORMAL ? User::LOGIN_STATUS_FORBIDDEN : User::LOGIN_STATUS_NORMAL;
        $member->save();
        return api_response('200', 'success');
    }

    /**
     * 删除成员
     * @param Request $request
     * @return string
     */
    public function delMember(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', '请求设置的成员不存在');
        }
        $user = Auth::user();
        if ($user->privilege != User::ADMIN_YES) {
            return api_response('400', '非法请求');
        }
        $id = array_get($params, 'id');
        $member = User::destroy($id);
        return api_response('200', 'success');
    }
}
