<?php

namespace app\deshang\service\rider;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;
use app\common\enum\rider\RiderFeeRuleEnum;
use app\common\dao\rider\RiderFeeRuleDao;

// 骑手费用规则服务
class DeshangRiderFeeRuleService extends BaseDeshangService
{
    // 计算骑手配送费用
    public function calculateRiderFee($store_info, $address_info, $weight)
    {
        // 店铺位置
        $store_latitude = $store_info['store_latitude'] ?? 0;
        $store_longitude = $store_info['store_longitude'] ?? 0;

        // 用户位置
        $user_latitude = $address_info['latitude'] ?? 0;
        $user_longitude = $address_info['longitude'] ?? 0;

        // 计算距离
        $distance = haversineGreatCircleDistance($store_latitude, $store_longitude, $user_latitude, $user_longitude);

        // 获取当前区域适用的配送费规则
        $area_id = $store_info['area_id'];
        $ruleInfo = (new RiderFeeRuleDao())->getRiderFeeRuleInfo([['area_id', '=', $area_id], ['is_enabled', '=', 1]]);
        if (empty($ruleInfo)) {
            // 获取全部区域 的配送费规则
            $ruleInfo = (new RiderFeeRuleDao())->getRiderFeeRuleInfo([['area_id', '=', 0], ['is_enabled', '=', 1]]);
            if (empty($ruleInfo)) {
                throw new CommonException('当前区域未配置配送费规则');
            }
        }

        // 解析规则JSON
        $distance_rules = !empty($ruleInfo['distance_rules']) ? json_decode($ruleInfo['distance_rules'], true) : [];
        $weight_rules = !empty($ruleInfo['weight_rules']) ? json_decode($ruleInfo['weight_rules'], true) : [];
        $time_period_rules = !empty($ruleInfo['time_period_rules']) ? json_decode($ruleInfo['time_period_rules'], true) : [];
        $weather_rules = !empty($ruleInfo['weather_rules']) ? json_decode($ruleInfo['weather_rules'], true) : [];

        // 初始化费用和描述
        $total_fee = $ruleInfo['base_fee'];
        $fee_desc = ['基础配送费：' . $total_fee . '元'];

        // 1. 计算距离费用
        if ($ruleInfo['distance_fee_type'] == RiderFeeRuleEnum::DISTANCE_FEE_TYPE_STEP) {
            // 阶梯式距离费用
            foreach ($distance_rules['step'] as $step) {
                if ($distance >= $step['min'] && $distance <= $step['max']) {
                    $total_fee += $step['fee'];
                    $fee_desc[] = '距离费用(' . $distance . 'km)：' . $step['fee'] . '元';
                    break;
                }
            }
        } elseif ($ruleInfo['distance_fee_type'] == RiderFeeRuleEnum::DISTANCE_FEE_TYPE_CONTINUOUS) {
            // 连续式距离费用
            if ($distance > $distance_rules['continuous']['start_value']) {
                $extra_distance = $distance - $distance_rules['continuous']['start_value'];
                $distance_fee = $extra_distance * $distance_rules['continuous']['per_unit_fee'];
                $total_fee += $distance_fee;
                $fee_desc[] = '距离费用(' . $distance . 'km)：' . $distance_fee . '元';
            }
        }

        // 2. 计算重量费用
        if ($ruleInfo['weight_fee_type'] != RiderFeeRuleEnum::WEIGHT_FEE_TYPE_NONE && !empty($weight_rules)) {
            if ($ruleInfo['weight_fee_type'] == RiderFeeRuleEnum::WEIGHT_FEE_TYPE_STEP) {
                // 阶梯式重量费用
                foreach ($weight_rules['step'] as $step) {
                    if ($weight >= $step['min'] && $weight <= $step['max']) {
                        $total_fee += $step['fee'];
                        $fee_desc[] = '重量费用(' . $weight . 'kg)：' . $step['fee'] . '元';
                        break;
                    }
                }
            } elseif ($ruleInfo['weight_fee_type'] == RiderFeeRuleEnum::WEIGHT_FEE_TYPE_CONTINUOUS) {
                // 连续式重量费用
                if ($weight > $weight_rules['continuous']['start_value']) {
                    $extra_weight = $weight - $weight_rules['continuous']['start_value'];
                    $weight_fee = $extra_weight * $weight_rules['continuous']['per_unit_fee'];
                    $total_fee += $weight_fee;
                    $fee_desc[] = '重量费用(' . $weight . 'kg)：' . $weight_fee . '元';
                }
            }
        }

        // 3. 计算时段费用
        if ($ruleInfo['time_period_fee_type'] == RiderFeeRuleEnum::TIME_PERIOD_FEE_TYPE_ADD) {
            $current_time = date('H:i');
            foreach ($time_period_rules as $period) {
                if ($current_time >= $period['start'] && $current_time <= $period['end']) {
                    $total_fee += $period['fee'];
                    $fee_desc[] = '时段费用(' . $period['start'] . '-' . $period['end'] . ')：' . $period['fee'] . '元';
                    break;
                }
            }
        }

        // 4. 计算天气费用
        if ($ruleInfo['weather_fee_type'] == RiderFeeRuleEnum::WEATHER_FEE_TYPE_ADD) {
            // 获取当前天气类型（这里需要根据实际情况获取）
            $current_weather = $this->getCurrentWeather();
            foreach ($weather_rules as $weather) {
                if ($weather['type'] == $current_weather) {
                    $total_fee += $weather['fee'];
                    $fee_desc[] = '天气费用(' . RiderFeeRuleEnum::getWeatherTypeDesc($current_weather) . ')：' . $weather['fee'] . '元';
                    break;
                }
            }
        }

        // 计算骑手实际配送费（考虑平台抽佣）
        $rider_fee_rate = $ruleInfo['rider_fee_rate'] / 100; // 转换为小数
        $rider_fee = ds_commerce_money($total_fee * (1 - $rider_fee_rate), 2);

        return [
            'rider_total_fee' => ds_commerce_money($total_fee, 2),
            'rider_fee_rate' => $ruleInfo['rider_fee_rate'],
            'rider_fee' => $rider_fee,
            'rider_distance' => ds_commerce_money($distance, 2),
            'rider_fee_desc' => implode('，', $fee_desc)
        ];
    }

    /**
     * 获取当前天气类型
     * @return string
     */
    private function getCurrentWeather()
    {
        // TODO: 实现获取当前天气的逻辑
        // 这里需要根据实际情况调用天气API或从缓存中获取
        return RiderFeeRuleEnum::WEATHER_TYPE_RAIN;
    }
}
