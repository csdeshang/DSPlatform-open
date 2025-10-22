<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\model\blogger\BloggerModel;
use app\common\enum\video\VideoShortEnum;

class VideoShortModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_short';

    // 关联博主
    public function blogger()
    {
        return $this->hasOne(BloggerModel::class, 'id', 'blogger_id');
    }



    // 短视频审核状态描述获取器
    public function getAuditStatusDescAttr($value, $data)
    {
        return VideoShortEnum::getAuditStatusDesc($data['audit_status']);
    }


    // 内容类型描述获取器
    public function getTypeDescAttr($value, $data)
    {
        return VideoShortEnum::getTypeDesc($data['type']);
        
    }

    // 短视频发布时间获取器
    public function getPublishAtAttr($value, $data)
    {
        return $this->formatTime($data['publish_at']);
    }

    // 短视频审核时间获取器
    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }



} 