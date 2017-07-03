<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;

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
}
