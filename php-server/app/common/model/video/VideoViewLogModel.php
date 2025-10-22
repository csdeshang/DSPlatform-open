<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\model\blogger\BloggerModel;

class VideoViewLogModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_view_log';

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * 关联博主
     */
    public function blogger()
    {
        return $this->hasOne(BloggerModel::class, 'id', 'blogger_id');
    }

    /**
     * 关联短视频
     */
    public function videoShort()
    {
        return $this->hasOne(VideoShortModel::class, 'id', 'content_id');
    }
} 