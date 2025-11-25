<?php

namespace app\adminapi\controller\technician\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\technician\TechnicianEnum;

/**
 * 管理端师傅验证器
 */
class TechnicianValidate extends BaseValidate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        // 基础字段
        'id' => 'require|integer|gt:0',
        'name' => 'require|chsAlphaNum|length:2,50',
        'mobile' => 'require|mobile',
        'gender' => 'in:0,1,2',
        'certificate_info' => 'max:500',
        'work_years' => 'integer|between:0,50',
        'description' => 'max:1000',
        'technician_fee_rate' => 'float|between:0,100',
        'is_enabled' => 'in:0,1',
        'apply_status' => 'checkApplyStatus',
        'technician_status' => 'checkTechnicianStatus',
        'apply_remark' => 'max:500',
        'audit_remark' => 'max:500',
        // 店铺绑定相关字段
        'store_id' => 'require|integer|gt:0',
    ];

    /**
     * 验证消息
     * @var array
     */
    protected $message = [
        // 基础字段消息
        'id.require' => '师傅ID不能为空',
        'id.integer' => '师傅ID必须为整数',
        'id.gt' => '师傅ID必须大于0',
        'name.require' => '师傅姓名不能为空',
        'name.chsAlphaNum' => '师傅姓名只能包含中文、字母和数字',
        'name.length' => '师傅姓名长度必须在2-50个字符之间',
        'mobile.require' => '手机号不能为空',
        'mobile.mobile' => '手机号格式不正确',
        'gender.in' => '性别值错误，0未知 1男 2女',
        'certificate_info.max' => '证书信息不能超过500个字符',
        'work_years.integer' => '工作年限必须为整数',
        'work_years.between' => '工作年限必须在0-50年之间',
        'description.max' => '师傅描述不能超过1000个字符',
        'technician_fee_rate.float' => '师傅费率必须为数值',
        'technician_fee_rate.between' => '师傅费率必须在0-100之间',
        'is_enabled.in' => '启用状态值错误，0禁用 1启用',
        'apply_status.checkApplyStatus' => '申请状态值错误，0待审核 1已通过 2已拒绝',
        'technician_status.checkTechnicianStatus' => '师傅状态值错误，0休息 1工作中 2忙碌',
        'apply_remark.max' => '申请备注不能超过500个字符',
        'audit_remark.max' => '审核备注不能超过500个字符',
        // 店铺绑定相关消息
        'store_id.require' => '店铺ID不能为空',
        'store_id.integer' => '店铺ID必须是整数',
        'store_id.gt' => '店铺ID必须大于0',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        // 分页查询场景
        'pages' => ['apply_status', 'technician_status', 'is_enabled'],

        // 更新师傅信息场景
        'update' => [
            'id',
            'name',
            'mobile',
            'gender',
            'certificate_info',
            'work_years',
            'description',
            'technician_fee_rate',
            'technician_status',
            'is_enabled',
            'apply_status',
            'apply_remark',
            'audit_remark'
        ],

        // 获取详情场景
        'info' => ['id'],

        // 更新店铺绑定场景
        'updateBindStore' => ['id', 'store_id'],
    ];

    /**
     * 验证申请状态
     * @param string $value
     * @param string $rule
     * @param array $data
     * @return bool
     */
    public function checkApplyStatus($value, $rule, $data)
    {
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, TechnicianEnum::getApplyStatusDict());
    }

    /**
     * 验证师傅状态
     * @param string $value
     * @param string $rule
     * @param array $data
     * @return bool
     */
    public function checkTechnicianStatus($value, $rule, $data)
    {
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, TechnicianEnum::getTechnicianStatusDict());
    }
}
