<?php

namespace app\common\model\video;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;


class VideoCommentModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'video_comment';

    protected $lazyFields = ['like_count', 'reply_count'];


    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 关联父评论
    public function parent()
    {
        return $this->hasOne(VideoCommentModel::class, 'id', 'parent_id');
    }

    // 关联子评论
    public function children()
    {
        return $this->hasMany(VideoCommentModel::class, 'parent_id', 'id');
    }
}
