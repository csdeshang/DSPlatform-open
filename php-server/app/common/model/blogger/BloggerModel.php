<?php

namespace app\common\model\blogger;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\enum\blogger\BloggerEnum;

class BloggerModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'blogger';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 博主认证状态描述获取器
    public function getVerificationStatusDescAttr($value, $data)
    {
        return BloggerEnum::getVerificationStatusDesc($data['verification_status']);
    }

    // 博主认证类型描述获取器
    public function getVerificationTypeDescAttr($value, $data)
    {
        return BloggerEnum::getVerificationTypeDesc($data['verification_type']);
    }


    // 博主审核时间获取器
    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }



} 