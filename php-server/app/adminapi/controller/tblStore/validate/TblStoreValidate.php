<?php

namespace app\adminapi\controller\tblStore\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\store\TblStoreEnum;

class TblStoreValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|min:1', // 店铺ID，必填，必须是大于0的整数
        'store_name' => 'require|max:32|chsDash', // 店铺名称，必填，最大长度32，只能包含汉字、字母、数字、下划线和破折号
        'store_business_status' => 'require|in:0,1', // 店铺营业状态，必须是0或1
        'service_fee_rate' => 'require|float|min:0|max:100', // 服务费率，浮点数，介于0到100%之间
        'is_enabled' => 'require|in:0,1', // 是否启用，必须是0或1
        'is_recommend' => 'require|in:0,1', // 是否推荐，必须是0或1
        'sort' => 'integer|min:0', // 排序，整数，最小值0
        'store_introduction' => 'max:500', // 店铺介绍，最大500个字符
        'contact_name' => 'max:32', // 联系人姓名，最大长度32
        'contact_phone' => 'mobile', // 联系电话，必须是有效的手机号
        'address' => 'max:255', // 店铺地址，最大长度255
        'seo_title' => 'max:100', // SEO标题，最大长度100
        'seo_keywords' => 'max:255', // SEO关键词，最大长度255
        'seo_description' => 'max:500', // SEO描述，最大长度500
        'category_id' => 'integer|min:0', // 店铺分类ID，整数，最小值0
        'apply_status' => 'require|checkApplyStatus', // 申请状态，必须是0,1,2中的一个
        'audit_remark' => 'max:500', // 审核备注，最大长度500
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '店铺ID不能为空',
        'id.integer' => '店铺ID必须是整数',
        'id.min' => '店铺ID必须大于0',
        'store_name.require' => '店铺名称不能为空',
        'store_name.max' => '店铺名称不能超过32个字符',
        'store_name.chsDash' => '店铺名称只能包含汉字、字母、数字、下划线和破折号',
        'store_business_status.require' => '营业状态不能为空',
        'store_business_status.in' => '营业状态值无效（0:休息 1:营业）',
        'service_fee_rate.require' => '服务费率不能为空',
        'service_fee_rate.float' => '服务费率必须是数字',
        'service_fee_rate.min' => '服务费率不能小于0',
        'service_fee_rate.max' => '服务费率不能超过100%',
        'is_enabled.require' => '启用状态不能为空',
        'is_enabled.in' => '启用状态值无效（0:禁用 1:启用）',
        'is_recommend.require' => '推荐状态不能为空',
        'is_recommend.in' => '推荐状态值无效（0:不推荐 1:推荐）',
        'sort.integer' => '排序必须是整数',
        'sort.min' => '排序不能小于0',
        'store_introduction.max' => '店铺介绍不能超过500个字符',
        'contact_name.max' => '联系人姓名不能超过32个字符',
        'contact_phone.mobile' => '联系电话格式不正确',
        'address.max' => '店铺地址不能超过255个字符',
        'seo_title.max' => 'SEO标题不能超过100个字符',
        'seo_keywords.max' => 'SEO关键词不能超过255个字符',
        'seo_description.max' => 'SEO描述不能超过500个字符',
        'category_id.integer' => '店铺分类ID必须是整数',
        'category_id.min' => '店铺分类ID不能小于0',
        'apply_status.require' => '申请状态不能为空',
        'apply_status.checkApplyStatus' => '申请状态值无效（0:待审核 1:审核通过 2:审核拒绝）',
        'audit_remark.max' => '审核备注不能超过500个字符',
    ];

    // 定义场景
    protected $scene = [
        'update' => ['id', 'store_name', 'store_business_status', 'service_fee_rate', 'is_enabled', 'is_recommend', 'sort', 'store_introduction', 'contact_name', 'contact_phone', 'address', 'seo_title', 'seo_keywords', 'seo_description', 'category_id', 'apply_status', 'audit_remark'],
        'audit' => ['id', 'apply_status', 'audit_remark'],
    ];


    public function checkApplyStatus($value, $rule, $data)
    {
        return array_key_exists($value, TblStoreEnum::getApplyStatusDict());
    }



}
