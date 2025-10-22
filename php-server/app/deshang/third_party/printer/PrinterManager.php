<?php

namespace app\deshang\third_party\printer;

use app\deshang\base\BaseDriverManager;

/**
 * 小票打印机管理类
 */
class PrinterManager extends BaseDriverManager
{
    protected $namespace = 'app\deshang\third_party\printer\providers';

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return 'Feie';
    }
} 