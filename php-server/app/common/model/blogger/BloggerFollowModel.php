<?php

namespace app\common\model\blogger;

use app\deshang\base\BaseModel;
use app\common\model\blogger\BloggerModel;
use app\common\model\user\UserModel;

class BloggerFollowModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'blogger_follow';

    /**
     * 关联博主
     */
    public function blogger()
    {
        return $this->hasOne(BloggerModel::class, 'id', 'blogger_id');
    }

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }
} 