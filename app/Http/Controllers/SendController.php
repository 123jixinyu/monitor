<?php
namespace App\Http\Controllers;

use App\Entities\SenderGroups;
use Illuminate\Http\Request;
use Auth;

class SendController extends Controller
{
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
}
