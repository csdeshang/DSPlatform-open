<?php

namespace app\deshang\service\store\validate;

use app\deshang\base\BaseValidate;
use app\common\dao\store\TblStoreDao;
use app\common\dao\user\UserDao;

class TblStoreAuthUserValidate extends BaseValidate
{
    protected $rule = [
        'store_id' => 'require|integer|gt:0|checkStoreExists', // 店铺ID验证
        'user_id' => 'require|integer|gt:0|checkUserExists',   // 用户ID验证
    ];

    protected $message = [
        'store_id.require' => '店铺ID不能为空',
        'store_id.integer' => '店铺ID必须是整数',
        'store_id.gt' => '店铺ID必须大于0',
        'store_id.checkStoreExists' => '店铺不存在',
        'user_id.require' => '用户ID不能为空',
        'user_id.integer' => '用户ID必须是整数',
        'user_id.gt' => '用户ID必须大于0',
        'user_id.checkUserExists' => '用户不存在',
    ];

    protected $scene = [
        'create' => ['store_id', 'user_id'], // 创建场景
        'update' => ['store_id', 'user_id'], // 更新场景
    ];

    // 自定义验证规则：检查店铺是否存在
    protected function checkStoreExists($value, $rule, $data = [])
    {
        $store = (new TblStoreDao())->getStoreInfoById($value);
        return !empty($store); // 店铺存在返回true，不存在返回false
    }

    // 自定义验证规则：检查用户是否存在
    protected function checkUserExists($value, $rule, $data = [])
    {
        $user = (new UserDao())->getUserInfoById($value);
        return !empty($user); // 用户存在返回true，不存在返回false
    }
} 