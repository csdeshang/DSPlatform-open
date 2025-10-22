<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\model\video\VideoShortModel;

class VideoShareModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_share';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }



} 