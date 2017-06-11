<?php
namespace App\Classes\Sender;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;

class Email
{
    public $message;
    public $subject;
    public $config;
    public $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->config = config('monitor');
        $this->mailer = $mailer;
    }

    public function send()
    {
        $this->mailer->raw($this->message, function (Message $message) {
            $message
                ->subject($this->subject)
                ->from($this->config['from']);
            foreach ($this->config['to'] as $to) {
                $message->to($to);
            }
        });
    }

}