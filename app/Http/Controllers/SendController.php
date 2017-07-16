<?php
namespace App\Http\Controllers;

use App\Entities\SenderGroups;
use App\Entities\SenderPeople;
use App\Entities\UserMonitor;
use Illuminate\Http\Request;
use Auth;
use Validator;

class SendController extends Controller
{
    public function index()
    {
        $group = SenderGroups::where('user_id', Auth::user()->id)->get();
        return view('group.index', [
            'user_groups' => $group
        ]);
    }

    /**
     * 获取该用户的通知组
     * @param Request $request
     * @return string
     */
    public function getGroup(Request $request)
    {
        $user = Auth::user();
        return api_response('200', 'success', SenderGroups::where('user_id', $user->id)->get());
    }

    /**
     * 保存用户通知组
     * @param Request $request
     * @param SenderGroups $senderGroups
     * @return string
     */
    public function saveGroup(Request $request, SenderGroups $senderGroups)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'exists:sender_groups,id',
            'group' => 'required'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $user = Auth::user();
        $id = array_get($params, 'id');
        if ($id) {
            $senderGroups = SenderGroups::where('user_id', $user->id)->where('id', $id)->first();
        } else {
            $senderGroups->user_id = $user->id;
        }
        $senderGroups->name = array_get($params, 'group');
        $senderGroups->save();
        return api_response('200', 'success');
    }

    /**
     * 获取分组详情
     * @param Request $request
     * @return string
     */
    public function getGroupDetail(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:sender_groups,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $user_id = Auth::user()->id;
        $id = array_get($params, 'id');
        return api_response('200', 'success', SenderGroups::where('user_id', $user_id)->where('id', $id)->first());
    }

    /**
     * 删除分组
     * @param Request $request
     * @return string
     */
    public function delGroup(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:sender_groups,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $user_id = Auth::user()->id;
        $id = array_get($params, 'id');
        //如果有分组在使用则禁止删除
        $userMonitors = UserMonitor::where('user_id', $user_id)->where('group_id', $id)->get();
        if (count($userMonitors) > 0) {
            return api_response('500', '该分组正在被使用中，无法删除');
        }
        //删除分组
        $delGroup = SenderGroups::where('user_id', $user_id)->where('id', $id)->delete();
        if ($delGroup) {
            //删除分组成员
            SenderPeople::where('group_id')->delete();
            return api_response('200', 'success');
        } else {
            return api_response('500', '删除失败');
        }
    }

    /**
     * 保存通知组成员信息
     * @param Request $request
     * @param SenderPeople $senderPeople
     * @return string
     */
    public function saveMember(Request $request, SenderPeople $senderPeople)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'group_id' => 'required|exists:sender_groups,id',
            'id' => 'exists:sender_peoples,id',
            'name' => 'required|between:1,5',
            'type' => 'required|in:1,2,3',
            'phone' => 'required',
            'email' => 'required|email',
            'remark' => 'between:1,50',
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        //TODO 验证手机号

        //检查是否是该用户的请求
        $senderGroups = SenderGroups::where('user_id', Auth::user()->id)->where('id', array_get($params, 'group_id'))->first();
        if (!$senderGroups) {
            return api_response('400', '非法请求');
        }
        $id = array_get($params, 'id');
        if ($id) {
            $senderPeople = SenderPeople::find($id);
        }
        $senderPeople->group_id = array_get($params, 'group_id');
        $senderPeople->name = array_get($params, 'name');
        $senderPeople->type = array_get($params, 'type');
        $senderPeople->phone = array_get($params, 'phone');
        $senderPeople->email = array_get($params, 'email');
        $senderPeople->remark = array_get($params, 'remark');
        $senderPeople->save();
        return api_response('200', 'success');
    }

    /**
     * 获取会员详情
     * @param Request $request
     * @return string
     */
    public function getMemberDetail(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:sender_peoples,id',
        ]);
        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        //验证id是否非法
        $senderPeople = SenderPeople::find(array_get($params, 'id'));
        if (!SenderGroups::where('user_id', Auth::user()->id)->where('id', $senderPeople->group_id)->first()) {
            return api_response('500', '非法请求');
        }
        return api_response('200', 'success', $senderPeople);
    }

    /**
     * 删除分组中的会员
     * @param Request $request
     * @return string
     */
    public function delMember(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'id' => 'required|exists:sender_peoples,id'
        ]);
        if ($validator->fails()) {
            return api_response('400', '非法请求');
        }
        SenderPeople::find(array_get($params, 'id'))->delete();
        return api_response('200', 'success');
    }
}
