<?php

namespace app\common\model\system;

use app\deshang\base\BaseModel;

class SysArticleModel extends BaseModel
{


    /**
     * 模型名称
     * @var string
     */
    protected $name = 'sys_article';


    // 发布时间 获取器
    public function getPublishTimeAttr($value, $data)
    {
        return $this->formatTime($data['publish_time']);
    }


}