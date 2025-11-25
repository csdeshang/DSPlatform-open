<?php

namespace app\adminapi\controller\system\validate;

use app\deshang\base\BaseValidate;

class SysErrorLogsValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // ID，必须是整数，且大于0
        'controller' => 'max:64', // 控制器名称，最大长度64
        'root' => 'max:64', // 根目录，最大长度64
        'ip' => 'max:32', // IP地址，最大长度32
        'code' => 'max:10', // 错误代码，最大长度10
        'include_exception_class' => 'array', // 包含异常类名，必须是数组
        'exclude_exception_class' => 'array', // 排除异常类名，必须是数组
        'duration_min' => 'integer|egt:0', // 最小请求耗时，必须是整数，且大于等于0
        'duration_max' => 'integer|egt:0', // 最大请求耗时，必须是整数，且大于等于0
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => 'ID不能为空',
        'id.integer' => 'ID必须是整数',
        'id.gt' => 'ID必须大于0',
        'controller.max' => '控制器名称不能超过64个字符',
        'root.max' => '根目录不能超过64个字符',
        'ip.max' => 'IP地址不能超过32个字符',
        'code.max' => '错误代码不能超过10个字符',
        'include_exception_class.array' => '包含异常类名必须是数组',
        'exclude_exception_class.array' => '排除异常类名必须是数组',
        'duration_min.integer' => '最小请求耗时必须是整数',
        'duration_min.egt' => '最小请求耗时必须大于等于0',
        'duration_max.integer' => '最大请求耗时必须是整数',
        'duration_max.egt' => '最大请求耗时必须大于等于0',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['controller', 'root', 'ip', 'code', 'include_exception_class', 'exclude_exception_class', 'duration_min', 'duration_max'], // 分页查询场景
        'info' => ['id'], // 获取详情场景
    ];
}

