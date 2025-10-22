<?php

namespace app\adminapi\controller\tblGoods\validate;

use app\deshang\base\BaseValidate;

class TblGoodsCategoryValidate extends BaseValidate
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
    ];

    // 定义场景
    protected $scene = [
        'create' => ['platform', 'pid', 'name', 'seo_keywords', 'seo_description', 'is_show', 'sort'],
        'update' => ['pid', 'name', 'seo_keywords', 'seo_description', 'is_show', 'sort'],
        'delete' => ['id' => 'require|integer'],
        'tree' => ['platform'],
    ];

}
