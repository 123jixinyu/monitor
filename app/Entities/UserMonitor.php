<?php
namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class  UserMonitor extends Model
{
    protected $table = 'user_monitors';
    protected $guarded = [];

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
}