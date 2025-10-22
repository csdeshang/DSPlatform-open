<?php

namespace app\deshang\third_party\sms;

use app\deshang\base\BaseDriverManager;


// 短信管理类
class SmsManager extends BaseDriverManager
{

    protected $namespace = 'app\deshang\third_party\sms\providers'; 

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return 'Tencent';
    }



}
