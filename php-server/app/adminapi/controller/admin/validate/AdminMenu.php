<?php
namespace app\adminapi\controller\admin\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\admin\AdminMenuEnum;

class AdminMenu extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer',
        'pid' => 'integer|egt:0',
        'title' => 'require|max:32',
        'path' => 'max:128',
        'component' => 'max:128',
        'api_url' => 'max:128',
        'icon' => 'max:32',
        'is_show' => 'integer|in:0,1',
        'is_enabled' => 'integer|in:0,1',
        'type' => 'require|checkType',
        'sort' => 'integer|between:0,255',
    ];

    // 定义验证提示信息
    protected $message = [
        'id.require' => '菜单ID必填',
        'id.integer' => '菜单ID必须是数字',
        'pid.integer' => '上级菜单ID必须是数字',
        'pid.egt' => '上级菜单ID必须大于等于0',
        'title.require' => '菜单标题不能为空',
        'title.max' => '菜单标题长度不能超过32个字符',
        'path.max' => '菜单路径长度不能超过128个字符',
        'component.max' => '组件路径长度不能超过128个字符',
        'api_url.max' => 'API地址长度不能超过128个字符',
        'icon.max' => '菜单图标长度不能超过32个字符',
        'is_show.integer' => '显示状态必须是数字',
        'is_show.in' => '显示状态必须是0或1',
        'is_enabled.integer' => '启用状态必须是数字',
        'is_enabled.in' => '启用状态必须是0或1',
        'type.require' => '菜单类型不能为空',
        'type.checkType' => '菜单类型必须是menu,directory,button',
        'sort.integer' => '排序字段必须是整数',
        'sort.between' => '排序字段必须在0到255之间',
    ];

    // 定义验证场景
    protected $scene = [
        'create' => ['pid', 'title', 'path', 'component', 'api_url', 'icon', 'is_show', 'is_enabled', 'type', 'sort'],
        'update' => ['id', 'pid', 'title', 'path', 'component', 'api_url', 'icon', 'is_show', 'is_enabled', 'type', 'sort'],
        'info' => ['id'],
    ];

    public function checkType($value, $rule, $data)
    {
        return array_key_exists($value, AdminMenuEnum::getTypeDict());
    }




}