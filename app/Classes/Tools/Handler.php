<?php
namespace App\Classes\Tools;

use App\Entities\UserMonitor;

class Handler
{
    /**
     * 执行监控
     * @param UserMonitor $userMonitor
     * @return bool|mixed
     * @throws \Exception
     */
    public function server(UserMonitor $userMonitor)
    {
        $type = $userMonitor->monitorType->name;
        if ($type && ($func = lcfirst($type))) {
            if (method_exists($this, $func)) {
                return call_user_func_array([$this, $func], [$userMonitor]);
            }
            return check_port($userMonitor->host, $userMonitor->port, $userMonitor->timeout);
        }
        throw new \Exception('监控类型不存在');
    }

    public function http(UserMonitor $userMonitor)
    {
        $code = ping_get_response_code($userMonitor->host, $userMonitor->timeout, false);
        return $code;
    }

    public function mySQL(UserMonitor $userMonitor)
    {
        $status = check_port($userMonitor->host, $userMonitor->port, $userMonitor->timeout);
        return $status;
    }
}