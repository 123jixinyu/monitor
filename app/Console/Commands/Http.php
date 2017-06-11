<?php
namespace App\Console\Commands;

use App\Classes\Sender\Email;
use Illuminate\Console\Command;
use Cache;

class Http extends Command
{
    protected $name = 'monitor:http';

    protected $description = '监控http';

    protected $cacheKey;

    protected $cacheTime = 24 * 60;

    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'monitor_http';
        $this->config = config('monitor');
    }

    public function handle(Email $email, \App\Classes\Http\Http $http)
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, 0, $this->cacheTime);
        }
        $result = $http->ping();
        if (!empty($result)) {
            //状态异常，缓存value+1,并判断是否满足发邮件的条件
            $cache = Cache::get($this->cacheKey);
            $new = $cache + 1;
            $times = $this->config['http']['times'];
            if ($new % $times == 0) {
                $email->subject = '服务器存在异常';
                $email->message = $this->setMessage($result);
                $email->send();
            }
            Cache::put($this->cacheKey, $new, $this->cacheTime);
        } else {
            //状态正常，则重置
            Cache::put($this->cacheKey, 0, $this->cacheTime);
        }
        $this->info(Cache::get($this->cacheKey));
    }

    public function setMessage($info)
    {
        $message = '';
        array_walk($info, function (&$item, $key) use (&$message) {
            $message .= $item['url'] . '=>' . '状态:' . $item['desc'] . PHP_EOL;
        });
        return $message;
    }
}