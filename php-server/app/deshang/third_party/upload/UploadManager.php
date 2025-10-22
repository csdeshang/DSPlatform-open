<?php

namespace app\deshang\third_party\upload;

use app\deshang\base\BaseDriverManager;

class UploadManager extends BaseDriverManager
{

    protected $namespace = 'app\deshang\third_party\upload\providers'; 

    /**
     * 获取默认驱动名称
     * @return string 默认驱动名称
     */
    protected function getDefaultDriverName(): string
    {
        return config('upload.default', 'Aliyun');
    }

    
    
}



