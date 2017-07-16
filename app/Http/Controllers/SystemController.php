<?php

namespace App\Http\Controllers;

use App\Repository\SystemRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SystemRepository $systemRepository)
    {
        //获取系统信息
        $sysInfo = $systemRepository->getSystemInfo();
        $sysInfo['disk_total_space'] = round(@disk_total_space(".") / (1024 * 1024 * 1024), 3);
        $sysInfo['disk_free_space'] = round(@disk_free_space(".") / (1024 * 1024 * 1024), 3);
        $sysInfo['memTotal'] = round($sysInfo['memTotal'] / 1024, 3);
        $sysInfo['memFree'] = round($sysInfo['memFree'] / 1024, 3);
        $sysInfo['memCached'] = round($sysInfo['memCached'] / 1024, 3);
        $sysInfo['memRealUsed'] = round($sysInfo['memRealUsed'] / 1024, 3);
        $sysInfo['memRealFree'] = round($sysInfo['memRealFree'] / 1024, 3);
        $sysInfo['now'] = date('Y-m-d H:i:s');
        //获取操作系统
        $os = explode(" ", php_uname());
        return view('system.index')->with(
            [
                'sysInfo' => $sysInfo,
                'os' => $os
            ]
        );
    }

    public function getRealTimeInfo(SystemRepository $systemRepository)
    {
        //获取系统信息
        $sysInfo = $systemRepository->getSystemInfo();
        $sysInfo['disk_total_space'] = round(@disk_total_space(".") / (1024 * 1024 * 1024), 3);
        $sysInfo['disk_free_space'] = round(@disk_free_space(".") / (1024 * 1024 * 1024), 3);
        $sysInfo['memTotal'] = round($sysInfo['memTotal'] / 1024, 3);
        $sysInfo['memFree'] = round($sysInfo['memFree'] / 1024, 3);
        $sysInfo['memCached'] = round($sysInfo['memCached'] / 1024, 3);
        $sysInfo['memRealUsed'] = round($sysInfo['memRealUsed'] / 1024, 3);
        $sysInfo['memRealFree'] = round($sysInfo['memRealFree'] / 1024, 3);
        $sysInfo['now'] = date('Y-m-d H:i:s', time());
        return api_response('200', 'success', ['sysInfo' => $sysInfo]);
    }
}
