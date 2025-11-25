<?php

namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\user\UserBehaviorEnum;

class UserBehaviorLogValidate extends BaseValidate
{
    protected $rule = [
        // 搜索条件
        'username' => 'max:32',
        'behavior_type' => 'checkBehaviorType',
        'behavior_status' => 'checkBehaviorStatus',
        'risk_level' => 'checkRiskLevel',
        'ip_address' => 'max:50',
        // 通用验证
        'id' => 'require|integer|gt:0',
    ];

    protected $message = [
        // 搜索条件
        'username.max' => '用户名长度不能超过32个字符',
        'behavior_type.checkBehaviorType' => '行为类型值无效',
        'behavior_status.checkBehaviorStatus' => '行为状态值无效',
        'risk_level.checkRiskLevel' => '风险等级值无效',
        'ip_address.max' => 'IP地址长度不能超过50个字符',
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须为整数',
        'id.gt' => 'ID必须大于0',
    ];

    protected $scene = [
        // 分页查询
        'pages' => ['username', 'behavior_type', 'behavior_status', 'risk_level', 'ip_address'],
        // 获取详情
        'info' => ['id'],
        // 删除
        'delete' => ['id'],
    ];

    // 验证行为类型
    public function checkBehaviorType($value, $rule, $data){
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, UserBehaviorEnum::getBehaviorTypeDict());
    }

    // 验证行为状态
    public function checkBehaviorStatus($value, $rule, $data){
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, UserBehaviorEnum::getBehaviorStatusDict());
    }

    // 验证风险等级
    public function checkRiskLevel($value, $rule, $data){
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, UserBehaviorEnum::getRiskLevelDict());
    }



}
