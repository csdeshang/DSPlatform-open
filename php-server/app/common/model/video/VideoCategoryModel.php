<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\enum\video\VideoCategoryEnum;

class VideoCategoryModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_category';

    /**
     * 是否显示描述获取器
     */
    public function getIsShowDescAttr($value, $data)
    {
        return VideoCategoryEnum::getShowDesc($data['is_show']);
    }

    /**
     * 分类类型描述获取器
     */
    public function getTypeDescAttr($value, $data)
    {
        return VideoCategoryEnum::getTypeDesc($data['type']);
    }


} 