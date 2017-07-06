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
        $monitorRep->updateTimes();
        //获取执行集合
        $monitors = $monitorRep->getExeMonitor();
        foreach ($monitors as $monitor) {
            $result=$monitorRep->handle($monitor);
            if(!$result){
                $this->info('异常');
            }else{
                $this->info('正常');
            }
        }
    }
}
