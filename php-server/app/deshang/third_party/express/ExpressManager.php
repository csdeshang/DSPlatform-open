<?php

namespace app\deshang\third_party\express;

use app\deshang\base\BaseDriverManager;

// 快递查询管理类
class ExpressManager extends BaseDriverManager
{
    protected $namespace = 'app\deshang\third_party\express\providers';

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return 'Kuaidiniao';
    }
} 