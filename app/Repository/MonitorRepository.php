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

    const EMAIL_VALIDATOR_KEY='email_code_key';//邮箱验证码缓存key

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

        if (!$this->status) {
            //当前发生异常，并且等于告警次数，并且在一小时内不会重复发送警告（防止重复发送）
            $count = MonitorLog::where('user_monitor_id', $this->monitor->id)->where('status', UserMonitor::STATUS_EXCEPTION)->count();
            if (($count + 1) % $this->monitor->times == 0) {
                $log = MonitorLog::where('user_monitor_id', $this->monitor->id)
                    ->where('status', UserMonitor::STATUS_EXCEPTION)
                    ->where('is_send', MonitorLog::IS_SEND_YES)
                    ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 hours')))->first();
                if (!$log) {
                    $this->send(config('monitor.error_subject'), $this->getErrorEmailInfo());
                }
            }
            $this->saveMonitorLog();
        } else {
            //如果最后一次为异常，当前恢复正常，则发送恢复正常通知
            $log = MonitorLog::where('user_monitor_id', $this->monitor->id)
                ->orderBy('id', 'desc')->first();
            if ($log && $log->status == UserMonitor::STATUS_EXCEPTION) {
                //保存正常状态（下次将不会重复发送）
                $this->saveMonitorLog();
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
        $msg .= '站点监控:监控服务器(' . $this->monitor->host . '.' . $this->monitor->port . ')' . '发生异常。';
        return $msg;
    }

    public function getResumeEmailInfo()
    {
        $msg = '';
        $msg .= '站点监控:监控服务器(' . $this->monitor->host . '.' . $this->monitor->port . ')' . '已恢复正常';
        return $msg;
    }

    public function getMonitorCount($user)
    {
        return UserMonitor::where('user_id', $user->id)->count();
    }
}