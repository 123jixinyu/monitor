<?php
namespace App\Classes\Tools;

use Redis;

class RedisConnect
{
    /**
     * 尝试连接mysql
     * @return bool|string
     */
    public function tryConnect()
    {
        try {
            $redis = Redis::set('redis_connect_try', 'try');
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}