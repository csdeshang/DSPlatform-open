<?php

namespace app\adminapi\controller\tblGoods\validate;

use app\deshang\base\BaseValidate;

class TblGoodsBrandValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'platform' => 'require|max:50|checkPlatform', // 平台名称，必填，最大长度50，使用自定义验证方法
        'pid' => 'integer',
        'name' => 'require|max:100', // 品牌名称，必填，最大长度100
        'description' => 'max:255', // 品牌描述，最大长度255
        'logo' => 'url', // logo链接，必须是有效的URL
        'sort' => 'integer|egt:0', // 排序，必须是整数，且大于等于0
        'is_recommend' => 'boolean', // 是否推荐，必须是布尔值
        'is_show' => 'boolean', // 是否显示，必须是布尔值
        'id' => 'require|integer', // 品牌ID，必填，必须是整数
    ];

    // 定义验证提示
    protected $message = [
        'platform.require' => '平台名称不能为空',
        'platform.max' => '平台名称不能超过50个字符',
        'platform.checkPlatform' => '平台名称无效,请确认是否安装', // 自定义验证错误提示
        'pid.integer' => '父级品牌ID必须是整数',
        'name.require' => '品牌名称不能为空',
        'name.max' => '品牌名称不能超过100个字符',
        'description.max' => '品牌描述不能超过255个字符',
        'logo.url' => 'logo链接格式不正确',
        'sort.integer' => '排序必须是整数',
        'sort.egt' => '排序必须大于等于0',
        'is_recommend.boolean' => '是否推荐必须是布尔值',
        'is_show.boolean' => '是否显示必须是布尔值',
        'id.require' => '品牌ID不能为空',
        'id.integer' => '品牌ID必须是整数',
    ];

    // 定义场景
    protected $scene = [
        'tree' => ['platform'], 
        'list' => ['platform'], 
        'create' => ['platform', 'pid', 'name', 'description', 'logo', 'sort', 'is_recommend', 'is_show'], // 创建场景
        'update' => ['id', 'pid', 'name', 'description', 'logo', 'sort', 'is_recommend', 'is_show'], // 更新场景
        'delete' => ['id'], 

    ];


}
