<?php
namespace App\Http\Controllers;

use App\Entities\SenderGroups;
use App\Entities\SenderPeople;
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
}
