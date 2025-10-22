<?php
namespace app\adminapi\controller\rider\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\rider\RiderFeeRuleEnum;

class RiderFeeRuleValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'rule_name' => 'require|string|max:255',
        'base_fee' => 'require|float|egt:0',
        'distance_fee_type' => 'require|integer|in:1,2',
        'weight_fee_type' => 'require|integer|in:0,1,2',
        'time_period_fee_type' => 'require|integer|in:0,1',
        'weather_fee_type' => 'require|integer|in:0,1',
        'distance_rules' => 'require|checkDistanceRules',
        'weight_rules' => 'checkWeightRules',
        'time_period_rules' => 'checkTimePeriodRules',
        'weather_rules' => 'checkWeatherRules',
        'rider_fee_rate' => 'require|float|egt:0|elt:100',
        'is_enabled' => 'require|integer|in:0,1',
        'area_id' => 'require|integer|egt:0',
    ];

    protected $message = [
        'id.require' => '规则ID不能为空',
        'id.integer' => '规则ID必须为整数',
        'id.gt' => '规则ID必须大于0',
        'rule_name.require' => '规则名称不能为空',
        'rule_name.string' => '规则名称必须为字符串',
        'rule_name.max' => '规则名称不能超过255个字符',
        'base_fee.require' => '基础配送费不能为空',
        'base_fee.float' => '基础配送费必须为数字',
        'base_fee.egt' => '基础配送费必须大于等于0',
        'distance_fee_type.require' => '距离费用计算类型不能为空',
        'distance_fee_type.integer' => '距离费用计算类型必须为整数',
        'distance_fee_type.in' => '距离费用计算类型不正确',
        'weight_fee_type.require' => '重量费用计算类型不能为空',
        'weight_fee_type.integer' => '重量费用计算类型必须为整数',
        'weight_fee_type.in' => '重量费用计算类型不正确',
        'time_period_fee_type.require' => '时段费用计算类型不能为空',
        'time_period_fee_type.integer' => '时段费用计算类型必须为整数',
        'time_period_fee_type.in' => '时段费用计算类型不正确',
        'weather_fee_type.require' => '天气费用计算类型不能为空',
        'weather_fee_type.integer' => '天气费用计算类型必须为整数',
        'weather_fee_type.in' => '天气费用计算类型不正确',
        'distance_rules.require' => '距离规则不能为空',
        'weight_rules.require' => '重量规则不能为空',
        'time_period_rules.require' => '时段规则不能为空',
        'weather_rules.require' => '天气规则不能为空',
        'rider_fee_rate.require' => '骑手配送费抽成比例不能为空',
        'rider_fee_rate.float' => '骑手配送费抽成比例必须为数字',
        'rider_fee_rate.egt' => '骑手配送费抽成比例必须大于等于0',
        'rider_fee_rate.elt' => '骑手配送费抽成比例不能超过100',
        'is_enabled.require' => '是否启用不能为空',
        'is_enabled.integer' => '是否启用必须为整数',
        'is_enabled.in' => '是否启用值不正确',
        'area_id.require' => '适用区域不能为空',
        'area_id.integer' => '适用区域必须为整数',
        'area_id.egt' => '适用区域必须大于等于0',
    ];

    protected $scene = [
        'create' => [
            'rule_name', 'base_fee', 'distance_fee_type', 'distance_rules',
            'weight_fee_type', 'weight_rules', 'time_period_fee_type', 
            'time_period_rules', 'weather_fee_type', 'weather_rules',
            'rider_fee_rate', 'is_enabled', 'area_id'
        ],
        'update' => [
            'id', 'rule_name', 'base_fee', 'distance_fee_type', 'distance_rules',
            'weight_fee_type', 'weight_rules', 'time_period_fee_type', 
            'time_period_rules', 'weather_fee_type', 'weather_rules',
            'rider_fee_rate', 'is_enabled', 'area_id'
        ]
    ];

    /**
     * 验证距离规则
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkDistanceRules($value, $rule, $data)
    {
        // 直接验证数组结构
        if (!is_array($value)) {
            return '距离规则必须为数组格式';
        }
        
        // 检查费用类型并验证相应的规则结构
        if ($data['distance_fee_type'] == RiderFeeRuleEnum::DISTANCE_FEE_TYPE_STEP) {
            // 阶梯式
            if (!isset($value['step']) || !is_array($value['step'])) {
                return '阶梯式距离规则格式不正确';
            }
            
            foreach ($value['step'] as $step) {
                if (!isset($step['min']) || !isset($step['max']) || !isset($step['fee'])) {
                    return '阶梯式距离规则缺少必要字段(min/max/fee)';
                }
                
                if (!is_numeric($step['min']) || !is_numeric($step['max']) || !is_numeric($step['fee'])) {
                    return '阶梯式距离规则数值字段必须为数字';
                }
                
                if ($step['min'] > $step['max']) {
                    return '阶梯式距离规则中最小值不能大于最大值';
                }
                
                if ($step['fee'] < 0) {
                    return '阶梯式距离规则中费用不能为负数';
                }
            }
        } elseif ($data['distance_fee_type'] == RiderFeeRuleEnum::DISTANCE_FEE_TYPE_CONTINUOUS) {
            // 连续式
            if (!isset($value['continuous']) || !is_array($value['continuous'])) {
                return '连续式距离规则格式不正确';
            }
            
            if (!isset($value['continuous']['start_value']) || !isset($value['continuous']['per_unit_fee'])) {
                return '连续式距离规则缺少必要字段(start_value/per_unit_fee)';
            }
            
            if (!is_numeric($value['continuous']['start_value']) || !is_numeric($value['continuous']['per_unit_fee'])) {
                return '连续式距离规则数值字段必须为数字';
            }
            
            if ($value['continuous']['start_value'] < 0) {
                return '连续式距离规则中起始值不能为负数';
            }
            
            if ($value['continuous']['per_unit_fee'] < 0) {
                return '连续式距离规则中每单位费用不能为负数';
            }
        }
        
        return true;
    }

    /**
     * 验证重量规则
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkWeightRules($value, $rule, $data)
    {
        // 直接验证数组结构
        if (!is_array($value)) {
            return '重量规则必须为数组格式';
        }
        
        // 如果不计算重量费用，则跳过验证
        if ($data['weight_fee_type'] == RiderFeeRuleEnum::WEIGHT_FEE_TYPE_NONE) {
            return true;
        }
        
        // 检查费用类型并验证相应的规则结构
        if ($data['weight_fee_type'] == RiderFeeRuleEnum::WEIGHT_FEE_TYPE_STEP) {
            // 阶梯式
            if (!isset($value['step']) || !is_array($value['step'])) {
                return '阶梯式重量规则格式不正确';
            }
            
            foreach ($value['step'] as $step) {
                if (!isset($step['min']) || !isset($step['max']) || !isset($step['fee'])) {
                    return '阶梯式重量规则缺少必要字段(min/max/fee)';
                }
                
                if (!is_numeric($step['min']) || !is_numeric($step['max']) || !is_numeric($step['fee'])) {
                    return '阶梯式重量规则数值字段必须为数字';
                }
                
                if ($step['min'] > $step['max']) {
                    return '阶梯式重量规则中最小值不能大于最大值';
                }
                
                if ($step['fee'] < 0) {
                    return '阶梯式重量规则中费用不能为负数';
                }
            }
        } elseif ($data['weight_fee_type'] == RiderFeeRuleEnum::WEIGHT_FEE_TYPE_CONTINUOUS) {
            // 连续式
            if (!isset($value['continuous']) || !is_array($value['continuous'])) {
                return '连续式重量规则格式不正确';
            }
            
            if (!isset($value['continuous']['start_value']) || !isset($value['continuous']['per_unit_fee'])) {
                return '连续式重量规则缺少必要字段(start_value/per_unit_fee)';
            }
            
            if (!is_numeric($value['continuous']['start_value']) || !is_numeric($value['continuous']['per_unit_fee'])) {
                return '连续式重量规则数值字段必须为数字';
            }
            
            if ($value['continuous']['start_value'] < 0) {
                return '连续式重量规则中起始值不能为负数';
            }
            
            if ($value['continuous']['per_unit_fee'] < 0) {
                return '连续式重量规则中每单位费用不能为负数';
            }
        }
        
        return true;
    }

    /**
     * 验证时段规则
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkTimePeriodRules($value, $rule, $data)
    {
        // 直接验证数组结构
        if (!is_array($value)) {
            return '时段规则必须为数组格式';
        }
        
        // 如果不计算时段费用，则跳过验证
        if ($data['time_period_fee_type'] == RiderFeeRuleEnum::TIME_PERIOD_FEE_TYPE_NONE) {
            return true;
        }
        
        foreach ($value as $period) {
            if (!isset($period['start']) || !isset($period['end']) || !isset($period['fee'])) {
                return '时段规则缺少必要字段(start/end/fee)';
            }
            
            // 验证时间格式 (H:i)
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $period['start']) || 
                !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $period['end'])) {
                return '时段规则中时间格式不正确，应为HH:MM格式';
            }
            
            if (!is_numeric($period['fee']) || $period['fee'] < 0) {
                return '时段规则中费用必须为非负数字';
            }
        }
        
        return true;
    }

    /**
     * 验证天气规则
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkWeatherRules($value, $rule, $data)
    {
        // 直接验证数组结构
        if (!is_array($value)) {
            return '天气规则必须为数组格式';
        }
        
        // 如果不计算天气费用，则跳过验证
        if ($data['weather_fee_type'] == RiderFeeRuleEnum::WEATHER_FEE_TYPE_NONE) {
            return true;
        }
        
        $validTypes = [
            RiderFeeRuleEnum::WEATHER_TYPE_RAIN,
            RiderFeeRuleEnum::WEATHER_TYPE_SNOW,
            RiderFeeRuleEnum::WEATHER_TYPE_HOT,
            RiderFeeRuleEnum::WEATHER_TYPE_COLD
        ];
        
        foreach ($value as $weather) {
            if (!isset($weather['type']) || !isset($weather['fee'])) {
                return '天气规则缺少必要字段(type/fee)';
            }
            
            if (!in_array($weather['type'], $validTypes)) {
                return '天气规则中天气类型不正确';
            }
            
            if (!is_numeric($weather['fee']) || $weather['fee'] < 0) {
                return '天气规则中费用必须为非负数字';
            }
        }
        
        return true;
    }
}