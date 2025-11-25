<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysArticleValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'cid' => 'require|integer|egt:0', // 分类ID，必须是整数，且大于等于0
        'title' => 'require|max:32', // 标题，必填，最大长度32
        'sort' => 'integer|egt:0', // 排序，必须是整数，且大于等于0
        'is_show' => 'boolean', // 是否显示，必须是布尔值
        'ids' => 'require|array|isAllNumbers', // ids 必须是数组且只能包含数字
    ];

    // 定义验证提示
    protected $message = [
        'cid.require' => '分类ID不能为空',
        'cid.integer' => '分类ID必须是整数',
        'cid.egt' => '分类ID必须大于等于0',
        'title.require' => '标题不能为空',
        'title.max' => '标题不能超过32个字符',
        'sort.integer' => '排序必须是整数',
        'sort.egt' => '排序必须大于等于0',
        'is_show.boolean' => '是否显示必须是布尔值',
        'ids.require' => 'ID列表不能为空',
        'ids.array' => 'ID列表必须是数组',
        'ids.isAllNumbers' => 'ID列表只能包含数字',
    ];

    // 定义场景
    protected $scene = [
        'create' => ['cid', 'title', 'sort', 'is_show'], // 创建场景
        'update' => ['cid', 'title', 'sort', 'is_show'], // 更新场景
        'info' => ['id'], // 获取信息场景
        'deleteBatch' => ['ids'], // 批量删除场景
    ];
}
