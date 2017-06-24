<?php
namespace App\Classes\Tools;
class Ping extends \Karlmonson\Ping\Ping
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function setAllowRedirects($allowRedirects)
    {
        $this->allowRedirects = $allowRedirects;
    }
}