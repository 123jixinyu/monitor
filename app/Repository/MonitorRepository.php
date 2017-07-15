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

    public $is_send = 0;//是否发送消息

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
        $status = Status::where('user_id', $this->monitor->user_id)->first();
        if (!$status) {
            $status = new Status();
            $status->user_id = $this->monitor->user_id;
            $status->times = 0;
            $status->suc_times = 0;
            $status->err_times = 0;

        }
        $status->times += 1;
        $status->suc_times += $this->status ? 1 : 0;
        $status->err_times += $this->status ? 0 : 1;
        return $status->save();
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
        //更新总成功和失败次数
        if ($this->status) {
            $this->suc_num += 1;
        } else {
            $this->err_num += 1;
        }
        //更新用户执行次数
        $this->updateTimes();

        //查询报警信息
        $no_time = time() - ($this->monitor->freq * $this->monitor->times * 60 + $this->monitor->freq / 2 * 60);
        $no_time = date('Y-m-d H:i:s', $no_time);
        $log = MonitorLog::where('user_monitor_id', $this->monitor->id)
            ->where('status', UserMonitor::STATUS_EXCEPTION)
            ->where('created_at', '>', $no_time)->first();
        if (!$this->status) {
            $count = MonitorLog::where('user_monitor_id', $this->monitor->id)->where('status', UserMonitor::STATUS_EXCEPTION)->count();
            if (($count + 1) % $this->monitor->times == 0) {
                //不连续发送警告
                if (!$log) {
                    $this->send('monitor.error_subject', $this->getErrorEmailInfo());
                }
            }
            $this->saveMonitorLog();
        } else {
            //恢复通知
            if ($log) {
                $this->send(config('monitor.resume_subject'), $this->getResumeEmailInfo());
            }
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
        $monitorLog->host = $this->monitor->host;
        $monitorLog->port = $this->monitor->port;
        $monitorLog->group_name = $this->monitor->sendGroup->name;
        $monitorLog->is_send = $this->is_send;
        $monitorLog->save();

    }

    /**
     * 消息通知
     */
    public function send($subject, $message)
    {

        $this->is_send = MonitorLog::IS_SEND_YES;
        $this->sender->userMonitor = $this->monitor;
        $this->sender->send($subject, $message);
    }

    public function getErrorEmailInfo()
    {
        $msg = '';
        $msg.= '站点监控:监控服务器('.$this->monitor->host.'.'.$this->monitor->port.')'.'发生异常。';
        return $msg;
    }

    public function getResumeEmailInfo(){
        $msg = '';
        $msg.= '站点监控:监控服务器('.$this->monitor->host.'.'.$this->monitor->port.')'.'已恢复正常';
        return $msg;
    }
}