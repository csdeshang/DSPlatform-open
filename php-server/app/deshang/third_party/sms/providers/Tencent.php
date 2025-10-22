<?php

namespace app\deshang\third_party\sms\providers;

use app\deshang\third_party\sms\providers\BaseSms;

class Tencent extends BaseSms
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send(string $to, string $message): bool
    {
        // 模拟发送短信
        echo "使用腾讯云发送短信到 {$to}: {$message}\n";
        return true;
    }
}