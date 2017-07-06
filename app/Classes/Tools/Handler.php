<?php
namespace App\Classes\Tools;

use App\Entities\UserMonitor;

class Handler
{
    public function http(UserMonitor $userMonitor)
    {
        return ping_get_response_code($userMonitor->host, $userMonitor->timeout, false);
    }
}