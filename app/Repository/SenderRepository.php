<?php
namespace App\Repository;

use App\Classes\Sender\Email;
use App\Entities\SenderPeople;
use Illuminate\Support\Collection;

class SenderRepository
{
    public $userMonitor;
    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * 发送通知
     */
    public function send()
    {
        $notifies = $this->getPeopleNotify();
        foreach ($notifies as $key => $notify) {
            if ($key == SenderPeople::TYPE_EMAIL) {
                $emails = $notify->pluck('email')->all();
                $this->email->emails = $emails;
                $this->email->subject = '异常';
                $this->email->message = 'xxxx';
                $this->email->send();
            }
        }
    }

    /**
     * 获取通知人群
     * @return array|static
     */
    public function getPeopleNotify()
    {
        $notifies = $this->userMonitor->sendGroup->SenderPeople;
        if ($notifies && $notifies instanceof Collection && !$notifies->isEmpty()) {
            return $notifies->groupBy('type');
        }
        return [];
    }
}