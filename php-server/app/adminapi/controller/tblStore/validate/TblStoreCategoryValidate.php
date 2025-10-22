<?php

namespace app\adminapi\controller\tblStore\validate;

use app\deshang\base\BaseValidate;

class TblStoreCategoryValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'platform' => 'require|max:50|checkPlatform',
        'pid' => 'integer',
        'name' => 'require|max:100',
        'seo_keywords' => 'max:255',
        'seo_description' => 'max:255',
        'is_show' => 'boolean',
        'sort' => 'integer|between:0,255',
        'service_fee_rate' => 'float|between:0,100',
    ];

    // 定义验证提示
    protected $message = [
        'platform.require' => '平台名称不能为空',
        'platform.max' => '平台名称不能超过50个字符',
        'platform.checkPlatform' => '平台名称无效',
        'pid.integer' => '父级分类ID必须是整数',
        'name.require' => '分类名称不能为空',
        'name.max' => '分类名称不能超过100个字符',
        'seo_keywords.max' => 'SEO关键词不能超过255个字符',
        'seo_description.max' => 'SEO描述不能超过255个字符',
        'is_show.boolean' => '是否显示必须是布尔值',
        'sort.integer' => '排序必须是整数',
        'sort.between' => '排序值必须在0到255之间',
        'service_fee_rate.float' => '服务费率必须是浮点数',
        'service_fee_rate.between' => '服务费率必须在0到100之间',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['platform', 'pid', 'name', 'seo_keywords', 'seo_description', 'is_show', 'sort', 'service_fee_rate'],
        'update' => ['pid', 'name', 'seo_keywords', 'seo_description', 'is_show', 'sort', 'service_fee_rate'],
        'delete' => ['id' => 'require|integer'],
        'tree' => ['platform'],
    ];

}
