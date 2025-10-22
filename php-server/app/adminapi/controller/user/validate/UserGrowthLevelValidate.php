<?php
namespace app\adminapi\controller\user\validate;

use app\deshang\base\BaseValidate;

// 用户成长等级验证
class UserGrowthLevelValidate extends BaseValidate
{
    
    protected $rule = [
        'level_name' => 'require|max:255',
        'min_growth' => 'require|integer|egt:0',
        'description' => 'max:255'
    ];

    protected $message = [
        'level_name.require' => '等级名称不能为空',
        'level_name.max' => '等级名称不能超过255个字符',
        'min_growth.require' => '最小成长值不能为空',
        'min_growth.integer' => '最小成长值必须为整数',
        'min_growth.egt' => '最小成长值必须大于等于0',
        'description.max' => '描述不能超过255个字符',
    ];

    protected $scene = [
        'create' => ['level_name', 'min_growth', 'description'],
        'update' => ['id', 'level_name', 'min_growth', 'description']
    ];

}