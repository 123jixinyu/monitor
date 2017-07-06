<?php
namespace App\Repository;

use App\Classes\Tools\Handler;
use App\Entities\Status;
use App\Entities\UserMonitor;

class MonitorRepository
{
    public $times;//脚本执行次数

    /**
     * 查询当前需要执行的集合
     * @return mixed
     */
    public function getExeMonitor()
    {
        //获取当前需要执行的监控速率
        $timesArray = array_filter(config('monitor.freq'), function ($val) {
            return $val % $this->times;
        });
        return UserMonitor::where('is_open', UserMonitor::IS_OPEN_YES)->whereIn('times', $timesArray)->get();
    }

    /**
     * 执行
     * @param UserMonitor $userMonitor
     * @return bool
     */
    public function handle(UserMonitor $userMonitor)
    {
        $type = $userMonitor->monitorType()->name;
        return call_user_func_array([new Handler(), lcfirst($type)],[$userMonitor]);
    }

    /**
     * 更新脚本执行次数
     * @return mixed
     */
    public function updateTimes()
    {
        $status = Status::where('key', 'times')->first();
        $this->times = $status->values;
        if ($status->increment('values')) {
            $this->times = $status->values + 1;
        };
        return $this->times;
    }
}