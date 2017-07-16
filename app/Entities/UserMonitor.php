<?php
namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class  UserMonitor extends Model
{
    protected $table = 'user_monitors';
    protected $guarded = [];

    //状态
    const STATUS_NORMAL = 0;//正常
    const STATUS_EXCEPTION = 1;//异常

    //是否开启
    const IS_OPEN_NO=0;//未开启
    const IS_OPEN_YES=1;//已开启

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function monitorType()
    {
        return $this->belongsTo(MonitorType::class, 'monitor_id', 'id');
    }

    public function sendGroup()
    {
        return $this->belongsTo(SenderGroups::class, 'group_id', 'id');
    }

    public function getStatus()
    {
        return array_get([
            self::STATUS_NORMAL => '正常',
            self::STATUS_EXCEPTION => '异常',
        ], $this->status);
    }

    public function getOpen()
    {
        return array_get([
            self::IS_OPEN_NO => '未开启',
            self::IS_OPEN_YES => '已开启',
        ], $this->is_open);
    }

    public function getSwitch(){
        return array_get([
            self::IS_OPEN_NO => '开启',
            self::IS_OPEN_YES => '关闭',
        ], $this->is_open);
    }
}