<?php
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class  MonitorLog extends Model
{
    protected $table = 'monitor_logs';
    protected $guarded = [];

    const IS_SEND_YES = 1;//发送
    const IS_SEND_NO = 0;//不发送

    public function getSend()
    {
        return array_get([
            self::IS_SEND_NO => '不通知',
            self::IS_SEND_YES => '通知',
        ], $this->is_send);
    }

    public function monitorType()
    {
        return $this->belongsTo(MonitorType::class, 'monitor_id', 'id');
    }

    public function userMonitor()
    {
        return $this->belongsTo(UserMonitor::class, 'user_monitor_id', 'id');
    }
}