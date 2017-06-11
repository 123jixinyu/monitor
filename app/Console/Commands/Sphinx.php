<?php
namespace App\Console\Commands;

use App\Classes\Sender\Email;
use App\Classes\Sphinx\SphinxClient;
use Illuminate\Console\Command;
use Cache;

class Sphinx extends Command
{
    protected $name = 'monitor:sphinx';

    protected $description = '监控搜索引擎';

    protected $cacheKey;
    protected $cacheTime = 24 * 60;
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'monitor_sphinx';
        $this->config = config('monitor');
    }

    public function handle(Email $email)
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, 0, $this->cacheTime);
        }
        $sphinx = new SphinxClient();
        $sphinx->SphinxClient();
        if (!$sphinx->Status()) {
            //状态异常，缓存value+1,并判断是否满足发邮件的条件
            $cache = Cache::get($this->cacheKey);
            $new = $cache + 1;
            $times = $this->config['sphinx']['times'];
            if ($new % $times == 0) {
                $email->subject = 'sphinx搜索引擎异常';
                $email->message = '连续超过' . $times . '次无法连接';
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