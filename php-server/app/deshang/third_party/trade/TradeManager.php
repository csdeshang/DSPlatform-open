<?php

namespace app\deshang\third_party\trade;

use app\deshang\base\BaseDriverManager;


// 交易管理类
class TradeManager extends BaseDriverManager
{

    protected $namespace = 'app\deshang\third_party\trade\providers'; 

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return config('trade.default', 'Yansongda');
    }



}
