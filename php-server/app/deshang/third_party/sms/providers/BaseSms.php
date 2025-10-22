<?php

namespace app\deshang\third_party\sms\providers;


// 短信驱动接口
abstract class BaseSms
{

    abstract public function send(string $to, string $message): bool;


    
}