<?php

namespace App\Http\Controllers;

use App\Entities\MonitorType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MonitorTypeController extends Controller
{
    /**
     * 获取全部监控对象信息
     * @param Request $request
     * @return string
     */
    public function getMonitorTypes(Request $request)
    {
        return api_response('200', '成功', MonitorType::all());
    }
}
