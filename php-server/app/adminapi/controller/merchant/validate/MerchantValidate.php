<?php

namespace app\adminapi\controller\merchant\validate;

use app\deshang\base\BaseValidate;

class MerchantValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // 商户ID，必填，必须是大于0的整数
        'user_id' => 'integer|gt:0', // 用户ID，必须是大于0的整数
        'name' => 'require|max:100|chsDash', // 商户名称，必填，最大长度100，可包含汉字、字母、数字、下划线和破折号
        'contact_name' => 'max:50|chs', // 联系人姓名，最大长度50，只能是汉字
        'contact_phone' => 'mobile', // 联系电话，必须是有效的手机号
        'contact_address' => 'max:255|chsDash', // 联系地址，最大长度255，可包含汉字、字母、数字、下划线和破折号
        'is_enabled' => 'require|in:0,1', // 是否启用，必填，必须是0或1
        'sort' => 'integer|between:0,9999', // 排序，必须是0到9999之间的整数
        'is_allow_payment' => 'in:0,1', // 是否允许支付，必须是0或1
        'allowed_store_count' => 'integer|egt:0', // 允许店铺数量，必须是大于等于0的整数
        'apply_status' => 'in:0,1,2,3', // 申请状态，必须是0,1,2,3其中之一
        'audit_remark' => 'max:255|chsDash', // 审核备注，最大长度255，可包含汉字、字母、数字、下划线和破折号
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '商户ID不能为空',
        'id.integer' => '商户ID必须是整数',
        'id.gt' => '商户ID必须大于0',
        'user_id.integer' => '用户ID必须是整数',
        'user_id.gt' => '用户ID必须大于0',
        'name.require' => '商户名称不能为空',
        'name.max' => '商户名称不能超过100个字符',
        'name.chsDash' => '商户名称只能包含汉字、字母、数字、下划线和破折号',
        'contact_name.max' => '联系人姓名不能超过50个字符',
        'contact_name.chs' => '联系人姓名只能是汉字',
        'contact_phone.mobile' => '联系电话格式不正确',
        'contact_address.max' => '联系地址不能超过255个字符',
        'contact_address.chsDash' => '联系地址只能包含汉字、字母、数字、下划线和破折号',
        'is_enabled.require' => '是否启用不能为空',
        'is_enabled.in' => '是否启用必须是0或1',
        'sort.integer' => '排序必须是整数',
        'sort.between' => '排序必须在0到9999之间',
        'is_allow_payment.in' => '是否允许支付必须是0或1',
        'allowed_store_count.integer' => '允许店铺数量必须是整数',
        'allowed_store_count.egt' => '允许店铺数量必须大于等于0',
        'apply_status.in' => '申请状态必须是0,1,2,3其中之一',
        'audit_remark.max' => '审核备注不能超过255个字符',
        'audit_remark.chsDash' => '审核备注只能包含汉字、字母、数字、下划线和破折号',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['name', 'contact_name', 'apply_status'], // 分页查询场景
        'info' => ['id'], // 获取详情场景
        'create' => ['user_id', 'name', 'contact_name', 'contact_phone', 'contact_address', 'is_enabled', 'sort'], // 创建场景
        'update' => ['id', 'name', 'contact_name', 'contact_phone', 'contact_address', 'is_enabled', 'sort', 'is_allow_payment', 'allowed_store_count', 'apply_status', 'audit_remark'], // 更新场景
        'audit' => ['id', 'apply_status', 'audit_remark'], // 审核场景
    ];


}
