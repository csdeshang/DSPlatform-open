<?php

namespace app\adminapi\controller\pointsGoods\validate;

use app\deshang\base\BaseValidate;

class PointsGoodsCategoryValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // 分类ID，必填，必须是大于0的整数
        'pid' => 'integer|egt:0', // 父级分类ID，必须是大于等于0的整数
        'name' => 'require|max:32|chsDash', // 分类名称，必填，最大长度32，可包含汉字、字母、数字、下划线和破折号
        'image' => 'max:255', // 分类图片，最大长度255
        'is_show' => 'in:0,1', // 是否显示，必须是0或1
        'sort' => 'integer|between:0,9999', // 排序权重，必须是0到9999之间的整数
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '分类ID不能为空',
        'id.integer' => '分类ID必须是整数',
        'id.gt' => '分类ID必须大于0',
        'pid.integer' => '父级分类ID必须是整数',
        'pid.egt' => '父级分类ID必须大于等于0',
        'name.require' => '分类名称不能为空',
        'name.max' => '分类名称不能超过32个字符',
        'name.chsDash' => '分类名称只能包含汉字、字母、数字、下划线和破折号',
        'image.max' => '分类图片不能超过255个字符',
        'is_show.in' => '是否显示必须是0或1',
        'sort.integer' => '排序权重必须是整数',
        'sort.between' => '排序权重必须在0到9999之间',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['pid', 'name', 'image', 'is_show', 'sort'], // 创建场景
        'update' => ['id', 'pid', 'name', 'image', 'is_show', 'sort'], // 更新场景
        'delete' => ['id'], // 删除场景
        'info' => ['id'], // 获取详情场景
        'tree' => [], // 获取树形结构场景
    ];
}
