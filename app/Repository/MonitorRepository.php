<?php
namespace App\Repository;

use App\Classes\Sender\Email;
use App\Classes\Tools\Handler;
use App\Entities\MonitorLog;
use App\Entities\Status;
use App\Entities\UserMonitor;

class MonitorRepository
{
    public $times;//脚本执行次数

    public $status;//执行结果

    public $monitor;//当前执行的实例

    public $err_num = 0;//执行失败次数

    public $suc_num = 0;//执行成功次数

    public $sender;

    public function __construct(SenderRepository $sender)
    {
        $this->sender = $sender;
    }

    /**
     * 查询当前需要执行的集合
     * @return mixed
     */
    public function getExeMonitor()
    {
        //获取当前需要执行的监控速率
        $timesArray = array_filter(config('monitor.freq'), function ($val) {
            return $this->times % $val == 0;
        });
        return UserMonitor::where('is_open', UserMonitor::IS_OPEN_YES)->whereIn('freq', $timesArray)->get();
    }

    /**
     * 执行
     * @param UserMonitor $userMonitor
     * @return bool
     */
    public function handle(UserMonitor $userMonitor)
    {
        $this->monitor = $userMonitor;
        $this->status = call_user_func_array([new Handler(), 'server'], [$userMonitor]);
        $this->resultHandle();
        return $this->status;
    }

    /**
     * 更新脚本执行次数
     * @return mixed
     */
    public function updateTimes()
    {
        $status = Status::where('key', 'times')->first();
        $this->times = $status->value;
        if ($status->increment('value')) {
            $this->times = $status->value + 1;
        };
        return $this->times;
    }

    /**
     * 更新监控状态
     * @return mixed
     */
    public function updateStatus()
    {
        $this->monitor->status = $this->getStatus();
        return $this->monitor->save();
    }

    /**
     * 执行结果处理
     */
    public function resultHandle()
    {
        //更新监控状态
        $this->updateStatus();
        //更新成功和失败次数
        if ($this->status) {
            $this->suc_num += 1;
        } else {
            $this->err_num += 1;
        }
        //保存监控日志
        if (!$this->status) {
            $this->saveMonitorLog();
            $this->send();
        }


    }

    /**
     * 获取当前状态
     * @return int
     */
    public function getStatus()
    {
        $current = UserMonitor::STATUS_NORMAL;
        if (!$this->status) $current = UserMonitor::STATUS_EXCEPTION;
        return $current;
    }

    /**
     * 保存监控日志
     */
    public function saveMonitorLog()
    {

        $monitorLog = new MonitorLog();
        $monitorLog->user_monitor_id = $this->monitor->id;
        $monitorLog->monitor_id = $this->monitor->monitor_id;
        $monitorLog->monitor_type = $this->monitor->monitorType->name;
        $monitorLog->user_id = $this->monitor->user_id;
        $monitorLog->status = $this->getStatus();
        $monitorLog->save();

    }
    /**
     * 消息通知
     */
    public function send()
    {
        $count = MonitorLog::where('user_monitor_id', $this->monitor->id)->where('status', UserMonitor::STATUS_EXCEPTION)->count();
        if (($count + 1) % $this->monitor->times == 0) {
            $this->sender->userMonitor = $this->monitor;
            $this->sender->send();
        }
    }
}