<?php

namespace App\Console\Commands;

use App\Entities\UserMonitor;
use App\Repository\MonitorRepository;
use Illuminate\Console\Command;

class Monitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'monitor the server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MonitorRepository $monitorRep)
    {
        //更新脚本执行次数
        $times = $monitorRep->updateTimes();
        $this->info('已执行次数', $times);
        //获取执行集合
        $monitors = $monitorRep->getExeMonitor();
        $this->info('当前执行集合数量', count($monitors));
        $monitorRep->updateTimes('total_handle_times', count($monitors));

        $this->info('开始执行');
        foreach ($monitors as $key => $monitor) {
            $monitorRep->handle($monitor);
        }
        $this->info('执行异常数量', $monitorRep->err_num);
        $monitorRep->updateTimes('err_times', $monitorRep->err_num);
        $this->info('执行成功数量', $monitorRep->suc_num);
        $monitorRep->updateTimes('suc_times', $monitorRep->suc_num);
        $this->info('执行完毕');
    }

    /**
     * 写入信息
     * @param string $title
     * @param string $msg
     */
    public function info($title, $msg = '')
    {
        $content = date('Y-m-d H:i:s', time()) . '====' .
            "<info>$title</info>";
        if ($msg !== '') $content .= "<info>:$msg</info>";
        $this->output->writeln($content);
    }
}
