<?php

namespace App\Repository;

class SystemRepository {

    public function __construct() {
        error_reporting(0);
        @header("content-Type: text/html; charset=utf-8"); //语言强制
    }


    /**
     * 格式化采集信息
     * @param $sysInfo
     * @return array
     */
    public function formatSystemInfo($sysInfo) {

        $format = [];

        $format['server_tag'] = $sysInfo['win_n'] != '' ? $sysInfo['win_n'] : '';//服务器标识

        $format['now']     = $sysInfo['now'];//服务器当前时间
        $format['uptime']  = $sysInfo['uptime'];//服务器已运行时间
        $format['num']     = $sysInfo['cpu']['num'];//cpu核数量
        $format['model']   = $sysInfo['cpu']['model'];//cpu模型
        $format['loadAvg'] = $sysInfo['loadAvg'];//系统平均负载

        $format['disk_total_space'] = $sysInfo['disk_total_space'].'GB';//总存储空间
        $format['disk_free_space']  = $sysInfo['disk_free_space'].'GB';//剩余空间
        $format['disk_percent']  = round(($sysInfo['disk_total_space']-$sysInfo['disk_free_space'])/$sysInfo['disk_total_space'],2)*100;//使用率

        $format['memTotal'] = $sysInfo['memTotal'].'GB';//总内存
        $format['memFree']  = $sysInfo['memFree'].'GB';//剩余内存

        $format['mem_percent']=round(($sysInfo['memTotal']-$sysInfo['memFree'])/$sysInfo['memTotal'],2)*100;//内存使用率

        $format['memCached']        = $sysInfo['memCached'].'GB';//cache化内存
        $format['memCachedPercent'] = $sysInfo['memCachedPercent'].'%';//cache化内存使用率

        $format['memRealUsed'] = $sysInfo['memRealUsed'].'GB';//真实内存使用
        $format['memRealFree'] = $sysInfo['memRealFree'].'GB';//真实内存剩余
        $format['memRealPercent'] = round($sysInfo['memRealUsed']/($sysInfo['memRealFree']+$sysInfo['memRealUsed']),2)*100;//真实内存使用率

        $format['net'] = $sysInfo['net'];//网络流量使用情况

        return $format;
    }

    /**
     * 获取网络流量
     * @return array
     */
    public function getNetworkFlow() {
        $data = [];
        $strs = @file("/proc/net/dev");
        for ($i = 2; $i < count($strs); $i++) {
            preg_match_all("/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info);
            $netName  = $info[1][0];
            $netInput = round(round($info[2][0] / 1024 / 1024, 5) / 1024, 5);
            $netOut   = round(round($info[10][0] / 1024 / 1024, 5) / 1024, 5);
            $data[]   = [
                'net'   => $netName,
                'input' => $netInput,
                'out'   => $netOut,
            ];
        }
        return $data;
    }

    /**
     * 获取内存使用状况
     * @return string
     */
    public function memory_usage() {
        $memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
        return $memory;
    }

    /**
     * 格式化存储单位
     *
     * @param $size
     *
     * @return string
     */
    public function formatsize($size) {
        $danwei  = array(' B ', ' K ', ' M ', ' G ', ' T ');
        $allsize = array();
        $i       = 0;

        for ($i = 0; $i < 4; $i++) {
            if (floor($size / pow(1024, $i)) == 0) {
                break;
            }
        }

        for ($l = $i - 1; $l >= 0; $l--) {
            $allsize1[$l] = floor($size / pow(1024, $l));
            $allsize[$l]  = $allsize1[$l] - $allsize1[$l + 1] * 1024;
        }

        $len = count($allsize);

        $fsize = '';
        for ($j = $len - 1; $j >= 0; $j--) {
            $strlen = 4 - strlen($allsize[$j]);
            if ($strlen == 1) {
                $allsize[$j] = "<font color='#FFFFFF'>0</font>" . $allsize[$j];
            } elseif ($strlen == 2) {
                $allsize[$j] = "<font color='#FFFFFF'>00</font>" . $allsize[$j];
            } elseif ($strlen == 3) {
                $allsize[$j] = "<font color='#FFFFFF'>000</font>" . $allsize[$j];
            }

            $fsize = $fsize . $allsize[$j] . $danwei[$j];
        }
        return $fsize;
    }

    /**
     * 检测PHP设置参数
     *
     * @param $varName
     *
     * @return string
     */
    function show($varName) {
        switch ($result = get_cfg_var($varName)) {
            case 0:
                return '<font color="red">×</font>';
                break;

            case 1:
                return '<font color="green">√</font>';
                break;

            default:
                return $result;
                break;
        }
    }

    // 检测函数支持
    public function isfun($funName = '') {
        if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) {
            return '错误';
        }
        return (false !== function_exists($funName)) ? '<font color="green">√</font>' : '<font color="red">×</font>';
    }

    public function isfun1($funName = '') {
        if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) {
            return '错误';
        }
        return (false !== function_exists($funName)) ? '√' : '×';
    }

    public function getSystemInfo() {
        // 根据不同系统取得CPU相关信息
        $sysReShow = '';
        $sysInfo   = '';
        switch (PHP_OS) {
            case "Linux":
                $sysReShow = (false !== ($sysInfo = $this->sys_linux())) ? "show" : "none";
                break;

            case "FreeBSD":
                $sysReShow = (false !== ($sysInfo = $this->sys_freebsd())) ? "show" : "none";
                break;
            case 'Windows':
                $sysReShow = (false !== ($sysInfo = $this->sys_windows())) ? "show" : "none";
                break;
            default:
                break;
        }
        return $sysReShow == 'none' ? '' : $sysInfo;
    }

    /**
     * linux系统探测
     * @return bool
     */
    public function sys_linux() {
        // CPU
        if (false === ($str = @file("/proc/cpuinfo"))) {
            return false;
        }
        $str = implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
        @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
        @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
        if (false !== is_array($model[1])) {
            $res['cpu']['num'] = sizeof($model[1]);
            /*
            for($i = 0; $i < $res['cpu']['num']; $i++)
            {
            $res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';
            $res['cpu']['mhz'][] = $mhz[1][$i];
            $res['cpu']['cache'][] = $cache[1][$i];
            $res['cpu']['bogomips'][] = $bogomips[1][$i];
            }*/
            if ($res['cpu']['num'] == 1) {
                $x1 = '';
            } else {
                $x1 = ' ×' . $res['cpu']['num'];
            }
            $mhz[1][0]             = ' | 频率:' . $mhz[1][0];
            $cache[1][0]           = ' | 二级缓存:' . $cache[1][0];
            $bogomips[1][0]        = ' | Bogomips:' . $bogomips[1][0];
            $res['cpu']['model'][] = $model[1][0] . $mhz[1][0] . $cache[1][0] . $bogomips[1][0] . $x1;
            if (false !== is_array($res['cpu']['model'])) {
                $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
            }
            if (false !== is_array($res['cpu']['mhz'])) {
                $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
            }
            if (false !== is_array($res['cpu']['cache'])) {
                $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
            }
            if (false !== is_array($res['cpu']['bogomips'])) {
                $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);
            }
        }

        // NETWORK

        // UPTIME
        if (false === ($str = @file("/proc/uptime"))) {
            return false;
        }
        $str   = explode(" ", implode("", $str));
        $str   = trim($str[0]);
        $min   = $str / 60;
        $hours = $min / 60;
        $days  = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min   = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) {
            $res['uptime'] = $days . "天";
        }
        if ($hours !== 0) {
            $res['uptime'] .= $hours . "小时";
        }
        $res['uptime'] .= $min . "分钟";

        // MEMORY
        if (false === ($str = @file("/proc/meminfo"))) {
            return false;
        }
        $str = implode("", $str);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
        preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);

        $res['memTotal']   = round($buf[1][0] / 1024, 2);
        $res['memFree']    = round($buf[2][0] / 1024, 2);
        $res['memBuffers'] = round($buffers[1][0] / 1024, 2);
        $res['memCached']  = round($buf[3][0] / 1024, 2);
        $res['memUsed']    = $res['memTotal'] - $res['memFree'];
        $res['memPercent'] = (floatval($res['memTotal']) != 0) ? round($res['memUsed'] / $res['memTotal'] * 100, 2) : 0;

        $res['memRealUsed']    = $res['memTotal'] - $res['memFree'] - $res['memCached'] - $res['memBuffers']; //真实内存使用
        $res['memRealFree']    = $res['memTotal'] - $res['memRealUsed']; //真实空闲
        $res['memRealPercent'] = (floatval($res['memTotal']) != 0)
            ? round($res['memRealUsed'] / $res['memTotal'] * 100, 2) : 0; //真实内存使用率

        $res['memCachedPercent'] = (floatval($res['memCached']) != 0)
            ? round($res['memCached'] / $res['memTotal'] * 100, 2) : 0; //Cached内存使用率

        $res['swapTotal']   = round($buf[4][0] / 1024, 2);
        $res['swapFree']    = round($buf[5][0] / 1024, 2);
        $res['swapUsed']    = round($res['swapTotal'] - $res['swapFree'], 2);
        $res['swapPercent'] = (floatval($res['swapTotal']) != 0) ? round($res['swapUsed'] / $res['swapTotal'] * 100, 2)
            : 0;

        // LOAD AVG
        if (false === ($str = @file("/proc/loadavg"))) {
            return false;
        }
        $str            = explode(" ", implode("", $str));
        $str            = array_chunk($str, 4);
        $res['loadAvg'] = implode(" ", $str[0]);

        return $res;
    }

    /**
     * windows系统探测
     * @return mixed
     */
    public function sys_windows() {
        if (PHP_VERSION >= 5) {
            $objLocator = new COM("WbemScripting.SWbemLocator");
            $wmi        = $objLocator->ConnectServer();
            $prop       = $wmi->get("Win32_PnPEntity");
        } else {
            return false;
        }

        //CPU
        $cpuinfo           = GetWMI($wmi, "Win32_Processor", array("Name", "L2CacheSize", "NumberOfCores"));
        $res['cpu']['num'] = $cpuinfo[0]['NumberOfCores'];
        if (null == $res['cpu']['num']) {
            $res['cpu']['num'] = 1;
        }
        $cpuinfo[0]['L2CacheSize'] = ' (' . $cpuinfo[0]['L2CacheSize'] . ')';
        if ($res['cpu']['num'] == 1) {
            $x1 = '';
        } else {
            $x1 = ' ×' . $res['cpu']['num'];
        }
        $res['cpu']['model'] = $cpuinfo[0]['Name'] . $cpuinfo[0]['L2CacheSize'] . $x1;
        // SYSINFO
        $sysinfo                  = GetWMI($wmi, "Win32_OperatingSystem", array(
            'LastBootUpTime', 'TotalVisibleMemorySize', 'FreePhysicalMemory', 'Caption', 'CSDVersion', 'SerialNumber',
            'InstallDate'
        ));
        $sysinfo[0]['Caption']    = iconv('GBK', 'UTF-8', $sysinfo[0]['Caption']);
        $sysinfo[0]['CSDVersion'] = iconv('GBK', 'UTF-8', $sysinfo[0]['CSDVersion']);
        $res['win_n']             = $sysinfo[0]['Caption'] . " " . $sysinfo[0]['CSDVersion'] . " 序列号:{$sysinfo[0]['SerialNumber']} 于" . date('Y年m月d日H:i:s', strtotime(substr($sysinfo[0]['InstallDate'], 0, 14))) . "安装";
        //UPTIME
        $res['uptime'] = $sysinfo[0]['LastBootUpTime'];

        $sys_ticks = 3600 * 8 + time() - strtotime(substr($res['uptime'], 0, 14));
        $min       = $sys_ticks / 60;
        $hours     = $min / 60;
        $days      = floor($hours / 24);
        $hours     = floor($hours - ($days * 24));
        $min       = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) {
            $res['uptime'] = $days . "天";
        }
        if ($hours !== 0) {
            $res['uptime'] .= $hours . "小时";
        }
        $res['uptime'] .= $min . "分钟";

        //MEMORY
        $res['memTotal']   = round($sysinfo[0]['TotalVisibleMemorySize'] / 1024, 2);
        $res['memFree']    = round($sysinfo[0]['FreePhysicalMemory'] / 1024, 2);
        $res['memUsed']    = $res['memTotal'] - $res['memFree'];    //上面两行已经除以1024,这行不用再除了
        $res['memPercent'] = round($res['memUsed'] / $res['memTotal'] * 100, 2);

        $swapinfo = GetWMI($wmi, "Win32_PageFileUsage", array('AllocatedBaseSize', 'CurrentUsage'));

        // LoadPercentage
        $loadinfo       = GetWMI($wmi, "Win32_Processor", array("LoadPercentage"));
        $res['loadAvg'] = $loadinfo[0]['LoadPercentage'];

        return $res;
    }

    //FreeBSD系统探测
    public function sys_freebsd() {
        //CPU
        if (false === ($res['cpu']['num'] = get_key("hw.ncpu"))) {
            return false;
        }
        $res['cpu']['model'] = get_key("hw.model");
        //LOAD AVG
        if (false === ($res['loadAvg'] = get_key("vm.loadavg"))) {
            return false;
        }
        //UPTIME
        if (false === ($buf = get_key("kern.boottime"))) {
            return false;
        }
        $buf       = explode(' ', $buf);
        $sys_ticks = time() - intval($buf[3]);
        $min       = $sys_ticks / 60;
        $hours     = $min / 60;
        $days      = floor($hours / 24);
        $hours     = floor($hours - ($days * 24));
        $min       = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) {
            $res['uptime'] = $days . "天";
        }
        if ($hours !== 0) {
            $res['uptime'] .= $hours . "小时";
        }
        $res['uptime'] .= $min . "分钟";
        //MEMORY
        if (false === ($buf = get_key("hw.physmem"))) {
            return false;
        }
        $res['memTotal'] = round($buf / 1024 / 1024, 2);

        $str = get_key("vm.vmtotal");
        preg_match_all("/\nVirtual Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i", $str, $buff, PREG_SET_ORDER);
        preg_match_all("/\nReal Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i", $str, $buf, PREG_SET_ORDER);

        $res['memRealUsed'] = round($buf[0][2] / 1024, 2);
        $res['memCached']   = round($buff[0][2] / 1024, 2);
        $res['memUsed']     = round($buf[0][1] / 1024, 2) + $res['memCached'];
        $res['memFree']     = $res['memTotal'] - $res['memUsed'];
        $res['memPercent']  = (floatval($res['memTotal']) != 0) ? round($res['memUsed'] / $res['memTotal'] * 100, 2)
            : 0;

        $res['memRealPercent'] = (floatval($res['memTotal']) != 0)
            ? round($res['memRealUsed'] / $res['memTotal'] * 100, 2) : 0;

        return $res;
    }

    /**
     * 取得参数值 FreeBSD
     *
     * @param $keyName
     *
     * @return mixed
     */
    public function get_key($keyName) {
        return $this->do_command('sysctl', "-n $keyName");
    }

    /**
     * 确定执行文件位置 FreeBSD
     *
     * @param $commandName
     *
     * @return bool|string
     */
    public function find_command($commandName) {
        $path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
        foreach ($path as $p) {
            if (@is_executable("$p/$commandName")) {
                return "$p/$commandName";
            }
        }
        return false;
    }

    /**
     * 执行系统命令 FreeBSD
     *
     * @param $commandName
     * @param $args
     *
     * @return bool|string
     */
    public function do_command($commandName, $args) {
        $buffer = "";
        if (false === ($command = $this->find_command($commandName))) {
            return false;
        }
        if ($fp = @popen("$command $args", 'r')) {
            while (!@feof($fp)) {
                $buffer .= @fgets($fp, 4096);
            }
            return trim($buffer);
        }
        return false;
    }

    public function GetWMI($wmi, $strClass, $strValue = array()) {
        $arrData = array();

        $objWEBM    = $wmi->Get($strClass);
        $arrProp    = $objWEBM->Properties_;
        $arrWEBMCol = $objWEBM->Instances_();
        foreach ($arrWEBMCol as $objItem) {
            @reset($arrProp);
            $arrInstance = array();
            foreach ($arrProp as $propItem) {
                eval("\$value = \$objItem->" . $propItem->Name . ";");
                if (empty($strValue)) {
                    $arrInstance[$propItem->Name] = trim($value);
                } else {
                    if (in_array($propItem->Name, $strValue)) {
                        $arrInstance[$propItem->Name] = trim($value);
                    }
                }
            }
            $arrData[] = $arrInstance;
        }
        return $arrData;
    }

}
