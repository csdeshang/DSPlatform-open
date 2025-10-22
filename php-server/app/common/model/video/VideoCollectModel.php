<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;


class VideoCollectModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_collect';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


} 