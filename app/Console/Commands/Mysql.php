<?php
namespace App\Console\Commands;

use App\Classes\Mysql\MysqlConnect;
use App\Classes\Sender\Email;
use Illuminate\Console\Command;
use Cache;

class Mysql extends Command
{
    protected $name = 'monitor:mysql';

    protected $description = '监控mysql';

    protected $cacheKey;

    protected $cacheTime = 24 * 60;

    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'monitor_mysql';
        $this->config = config('monitor');
    }

    public function handle(Email $email)
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, 0, $this->cacheTime);
        }
        $mysql = new MysqlConnect();
        $info = $mysql->tryConnect();
        if ($info !== true) {
            //状态异常，缓存value+1,并判断是否满足发邮件的条件
            $cache = Cache::get($this->cacheKey);
            $new = $cache + 1;
            $times = $this->config['mysql']['times'];
            if ($new % $times == 0) {
                $email->subject = 'mysql存在异常';
                $email->message = $info;
                $email->send();
            }
            Cache::put($this->cacheKey, $new, $this->cacheTime);
        } else {
            //状态正常，则重置
            Cache::put($this->cacheKey, 0, $this->cacheTime);
        }
        $this->info(Cache::get($this->cacheKey));
    }
}