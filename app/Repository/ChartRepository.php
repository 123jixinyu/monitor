<?php
namespace App\Repository;

use App\Entities\MonitorLog;
use App\Entities\SenderGroups;
use App\Entities\SenderPeople;
use App\Entities\UserMonitor;
use Auth;
use DB;

class ChartRepository
{
    public function getDataForWeek()
    {
        $begin = date('Y-m-d H:i:s', strtotime("-" . config('monitor.day') . "day"));
        return MonitorLog::where('created_at', '>', $begin)
            ->where('user_id', Auth::user()->id)
            ->where('status', UserMonitor::STATUS_EXCEPTION)
            ->groupBy('date')->get([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS date"),
                DB::raw('count(*) as count')
            ]);
    }

    /**
     * 获取稳定性
     * @return string
     */
    public function getStable()
    {
        $total = monitor_config('total_handle_times');
        if (!$total) {
            return '100%';
        }
        $err = monitor_config('err_times');
        return round((1 - $err / $total) * 100, 2) . '%';
    }

    /**
     * 获取报警数
     * @return mixed
     */
    public function getError()
    {
        return MonitorLog::where('user_id', Auth::user()->id)->where('status', UserMonitor::STATUS_EXCEPTION)->count();
    }

    /**
     * 获取监控数
     * @return mixed
     */
    public function getMonitors()
    {
        return UserMonitor::where('user_id', Auth::user()->id)->count();
    }

    /**
     * 获取通知成员数
     * @return mixed
     */
    public function getMembers()
    {
        return SenderPeople::whereIn('group_id', function ($query) {
            return $query->select('id')->from(app(SenderGroups::class)->getTable())->where('user_id', Auth::user()->id);
        })->count();
    }

}