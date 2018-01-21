<?php

if (!function_exists('ping_get_response_code')) {
    /**
     * ping url获取响应code
     * @param $url
     * @param int $timeout
     * @param bool $allowRedirects
     * @return int|mixed
     */
    function ping_get_response_code($url, $timeout = 0, $allowRedirects = true)
    {
        $ping = new \App\Classes\Tools\Ping();
        if ($timeout) $ping->setTimeout($timeout);
        if (!$allowRedirects) $ping->setTimeout($allowRedirects);
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
    function try_connect_sphinx($host = '127.0.0.1', $port = '9312')
    {
        $sphinx = new \App\Classes\Tools\SphinxClient();
        $sphinx->SphinxClient();
        $sphinx->SetServer($host, $port);
        $status = $sphinx->Status();
        if (!$status) {
            return false;
        }
        return true;
    }
}

if (!function_exists('check_port')) {
    /**
     * 检测端口是否正常
     * @param $host
     * @param string $port
     * @param int $timeout
     * @return bool
     */
    function check_port($host, $port = '80', $timeout = 2)
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }
        return false;
    }
}

if (!function_exists('api_response')) {
    /**
     * api统一返回
     * @param $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    function api_response($code, $msg = '', $data = [])
    {
        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('user_avatar')) {
    /**
     * 获取头像
     * @return mixed|string
     */
    function user_avatar()
    {
        $avatar = Auth::user()->avatar;
        if (!$avatar) {
            $avatar = asset('assets/images/user2-160x160.jpg');
        }
        return $avatar;
    }
}
if (!function_exists('user_name')) {
    /**
     * 获取用户名
     * @return mixed|string
     */
    function user_name()
    {
        $user = Auth::user();
        $name = $user->name;
        if (!$name) {
            $name = $user->email;
        }
        return $name;
    }
}

if (!function_exists('chart_totals')) {
    /**
     * 获取报表统计
     * @return array
     */
    function chart_totals($key)
    {
        $chart = new \App\Repository\ChartRepository();
        switch ($key) {
            case 'stable':
                return $chart->getStable();
            case 'errors':
                return $chart->getError();
            case 'monitors':
                return $chart->getMonitors();
            case 'members';
                return $chart->getMembers();
            default:
                return '';
        }
    }
}


if (!function_exists('get_confirm_times')) {

    function get_confirm_times()
    {
        $status=\App\Entities\Status::where('user_id',Auth::user()->id)->first();
        if(!$status){
            return 0;
        }
        return $status->err_times-$status->confirm_times;
    }
}

if (!function_exists('ping_address')) {
    /**
     * ping指定ip地址
     * @param $ip
     * @return bool
     */
    function ping_address($ip)
    {
        $status = -1;
        if (strcasecmp(PHP_OS, 'WINNT') === 0) {
            // Windows 服务器下
            $pingresult = exec("ping -n 1 {$ip}", $outcome, $status);
        } elseif (strcasecmp(PHP_OS, 'Linux') === 0) {
            // Linux 服务器下
            $pingresult = exec("ping -c 1 {$ip}", $outcome, $status);
        }
        if (0 == $status) {
            return true;
        }
        return false;
    }
}
