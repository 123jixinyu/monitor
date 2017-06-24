<?php

if (!function_exists('ping_get_response_code')) {
    /**
     * ping url获取响应code
     * @param $url
     * @param int $timeout
     * @param bool $allowRedirects
     * @return int|mixed
     */
    function ping_get_response_code($url,$timeout=0,$allowRedirects=true)
    {
        $ping=new \App\Classes\Tools\Ping();
        if($timeout)$ping->setTimeout($timeout);
        if(!$allowRedirects)$ping->setTimeout($allowRedirects);
        return $ping->check($url);
    }
}

if (!function_exists('try_connect_mysql')) {
    /**
     * 尝试连接mysql
     * @return bool|string
     */
    function try_connect_mysql()
    {
        try {
            DB::connection();
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}

if (!function_exists('try_connect_sphinx')) {
    /**
     * 尝试连接sphinx
     * @param string $host
     * @param string $port
     * @return bool
     */
    function try_connect_sphinx($host='127.0.0.1',$port='9312')
    {
        $sphinx = new \App\Classes\Tools\SphinxClient();
        $sphinx->SphinxClient();
        $sphinx->SetServer($host,$port);
        $status=$sphinx->Status();
        if (!$status) {
            return false;
        }
        return true;
    }
}


