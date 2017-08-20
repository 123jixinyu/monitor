<?php

namespace App\Http\Controllers;

use App\Entities\UserMonitor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Message;
use Validator;
use Auth;
use App;
use Mail;
use Cache;

class MonitorController extends Controller
{
    /**
     * 显示监控列表
     * @param Request $request
     * @param UserMonitor $userMonitor
     * @return $this
     */
    public function index(Request $request, UserMonitor $userMonitor)
    {
        return view('monitor.index')->with([
            'monitors' => $userMonitor->where('user_id', Auth::user()->id)->paginate()
        ]);
    }

    /**
     * 保存用户的监控信息
     * @param Request $request
     * @param UserMonitor $userMonitor
     * @return string
     */
    public function save(Request $request, UserMonitor $userMonitor)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'exists:user_monitors,id',
            'monitor_id' => 'required|exists:monitor_types,id',
            'group_id' => 'required|exists:sender_groups,id',
            'host' => 'required|ip',
            'port' => 'required|integer|between:1,65535',
            'timeout' => 'required|integer|between:1,5',
            'times' => 'required|integer|between:1,5',
            'is_open' => 'required|in:0,1',
            'freq' => 'required',
            'remark' => 'between:0,50'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        if (!in_array(array_get($params, 'freq'), config('monitor.freq'))) {
            return api_response('400', '参数错误');
        }
        $attributes = $request->all();
        $attributes['user_id'] = Auth::user()->id;
        if (isset($attributes['id'])) {
            $userMonitor = UserMonitor::find($attributes['id']);
        }
        $userMonitor->user_id = $attributes['user_id'];
        $userMonitor->monitor_id = $attributes['monitor_id'];
        $userMonitor->group_id = $attributes['group_id'];
        $userMonitor->host = $attributes['host'];
        $userMonitor->port = $attributes['port'];
        $userMonitor->timeout = $attributes['timeout'];
        $userMonitor->times = $attributes['times'];
        $userMonitor->is_open = $attributes['is_open'];
        $userMonitor->remark = $attributes['remark'];
        $userMonitor->freq = $attributes['freq'];
        $userMonitor->save();
        return api_response('200', 'success', $userMonitor);
    }

    /**
     * 获取用户监控详情
     * @param Request $request
     * @param UserMonitor $userMonitor
     * @return string|void
     */
    public function detail(Request $request, UserMonitor $userMonitor)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_monitors,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', 'parameter_err');
        }
        $id = $request->input('id');
        return api_response('200', 'success', UserMonitor::find($id));
    }

    /**
     * 删除用户监控信息
     * @param Request $request
     * @return string
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_monitors,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', 'parameter_err');
        }
        $id = $request->input('id');
        UserMonitor::where('user_id', Auth::user()->id)->where('id', $id)->delete();
        return api_response('200', 'success');
    }

    /**
     * 监控开关
     * @param Request $request
     * @return string
     */
    public function openHandle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_monitors,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', 'parameter_err');
        }
        $id = $request->input('id');
        $userMonitor = UserMonitor::where('user_id', Auth::user()->id)->where('id', $id)->first();
        if (!$userMonitor) {
            return api_response('400', 'parameter_err');
        }
        $userMonitor->is_open = $userMonitor->is_open ? 0 : 1;
        $userMonitor->save();
        return api_response('200', 'success');
    }

    /**
     * 发送验证码操作
     * @param Request $request
     * @return string
     */
    public function sendEmailCode(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'email' => 'required|email'
        ], [
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $email=array_get($params,'email');
        $code = rand(100000, 999999);
        Cache::put(Auth::user()->id.'_'.$email.'_'.App\Repository\MonitorRepository::EMAIL_VALIDATOR_KEY, $code, 1);
        Mail::send('emails.code', ['code'=>$code], function (Message $message)use($email) {
            $message->to($email)->subject('邮箱验证');
        });
        return api_response('200', 'success');
    }
}
