<?php

namespace app\common\enum\rider;

class RiderFeeRuleEnum
{
    // 距离费用计算类型
    const DISTANCE_FEE_TYPE_STEP = 1;       // 阶梯式
    const DISTANCE_FEE_TYPE_CONTINUOUS = 2; // 连续式

    // 距离费用计算类型字典
    public static function getDistanceFeeTypeDict(): array
    {
        return [
            self::DISTANCE_FEE_TYPE_STEP => '阶梯式',
            self::DISTANCE_FEE_TYPE_CONTINUOUS => '连续式',
        ];
    }

    // 获取距离费用计算类型描述
    public static function getDistanceFeeTypeDesc($value): string
    {
        $data = self::getDistanceFeeTypeDict();
        return $data[$value] ?? '未知';
    }

    // 重量费用计算类型
    const WEIGHT_FEE_TYPE_NONE = 0;         // 不计算
    const WEIGHT_FEE_TYPE_STEP = 1;         // 阶梯式
    const WEIGHT_FEE_TYPE_CONTINUOUS = 2;   // 连续式

    // 重量费用计算类型字典
    public static function getWeightFeeTypeDict(): array
    {
        return [
            self::WEIGHT_FEE_TYPE_NONE => '不计算',
            self::WEIGHT_FEE_TYPE_STEP => '阶梯式',
            self::WEIGHT_FEE_TYPE_CONTINUOUS => '连续式',
        ];
    }

    // 获取重量费用计算类型描述
    public static function getWeightFeeTypeDesc($value): string
    {
        $data = self::getWeightFeeTypeDict();
        return $data[$value] ?? '未知';
    }


    // 时段费用计算类型
    const TIME_PERIOD_FEE_TYPE_NONE = 0;    // 不计算
    const TIME_PERIOD_FEE_TYPE_ADD = 1;     // 按时段加价

    // 时段费用计算类型字典
    public static function getTimePeriodFeeTypeDict(): array
    {
        return [
            self::TIME_PERIOD_FEE_TYPE_NONE => '不计算',
            self::TIME_PERIOD_FEE_TYPE_ADD => '按时段加价',
        ];
    }

    // 获取时段费用计算类型描述
    public static function getTimePeriodFeeTypeDesc($value): string
    {
        $data = self::getTimePeriodFeeTypeDict();
        return $data[$value] ?? '未知';
    }

    // 天气费用计算类型
    const WEATHER_FEE_TYPE_NONE = 0;    // 不计算
    const WEATHER_FEE_TYPE_ADD = 1;     // 恶劣天气加价

    // 天气费用计算类型字典
    public static function getWeatherFeeTypeDict(): array
    {
        return [
            self::WEATHER_FEE_TYPE_NONE => '不计算',
            self::WEATHER_FEE_TYPE_ADD => '恶劣天气加价',
        ];
    }
    // 获取天气费用计算类型描述
    public static function getWeatherFeeTypeDesc($value): string
    {
        $data = self::getWeatherFeeTypeDict();
        return $data[$value] ?? '未知';
    }


    // 天气类型
    const WEATHER_TYPE_RAIN = 'rain';   // 雨天
    const WEATHER_TYPE_SNOW = 'snow';   // 雪天
    const WEATHER_TYPE_HOT = 'hot';     // 高温
    const WEATHER_TYPE_COLD = 'cold';   // 低温

    // 天气类型字典
    public static function getWeatherTypeDict(): array
    {
        return [
            self::WEATHER_TYPE_RAIN => '雨天',
            self::WEATHER_TYPE_SNOW => '雪天',
            self::WEATHER_TYPE_HOT => '高温',
            self::WEATHER_TYPE_COLD => '低温',
        ];
    }

    // 获取天气类型描述
    public static function getWeatherTypeDesc($value): string
    {
        $data = self::getWeatherTypeDict();
        return $data[$value] ?? '未知';
    }


    // 距离规则默认配置
    public static function getDistanceRulesConfig(): array
    {
        return [
            'step' => [
                [
                    // 最小距离
                    'min' => 0,
                    // 最大距离
                    'max' => 3,
                    // 费用
                    'fee' => 0
                ]
            ],
            'continuous' => [
                // 开始距离
                'start_value' => 3,
                // 每单位距离费用
                'per_unit_fee' => 1
            ]
        ];
    }

    // 重量规则默认配置
    public static function getWeightRulesConfig(): array
    {
        return [
            'step' => [
                [
                    // 最小重量
                    'min' => 0,
                    // 最大重量
                    'max' => 5,
                    // 费用
                    'fee' => 0
                ]
            ],
            'continuous' => [
                // 开始重量
                'start_value' => 5,
                // 每单位重量费用
                'per_unit_fee' => 1
            ]
        ];
    }

    // 时段规则默认配置
    public static function getTimePeriodRulesConfig(): array
    {
        return [
            [
                // 开始时间
                'start' => '',
                // 结束时间
                'end' => '',
                // 费用
                'fee' => 0
            ]
        ];
    }


    // 天气规则默认配置
    public static function getWeatherRulesConfig(): array
    {
        return [
            [
                'type' => self::WEATHER_TYPE_RAIN,
                'fee' => 0
            ]
        ];
    }
}
