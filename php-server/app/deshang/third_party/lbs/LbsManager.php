<?php

namespace app\deshang\third_party\lbs;

use app\deshang\base\BaseDriverManager;


// LBS 管理类
class LbsManager extends BaseDriverManager
{

    protected $namespace = 'app\deshang\third_party\lbs\providers'; 

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return 'Tencent';
        
    }



}
