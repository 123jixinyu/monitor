<?php
namespace App\Classes\Sender;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;

class Email
{
    public $message;
    public $subject;
    public $emails = [];
    public $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send()
    {
        $this->mailer->raw($this->message, function (Message $message) {
            $message
                ->subject($this->subject)
                ->from(config('mail.from.address'));
            foreach ($this->emails as $to) {
                $message->to($to);
            }
        });
    }

}