<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\rider\RiderFeeRuleService;




class RiderFeeRule extends BaseAdminController
{

    public function getRiderFeeRulePages()
    {

        $data = array(
            'name' => input('param.name', ''),
        );

        $result = (new RiderFeeRuleService())->getRiderFeeRulePages($data);
        return ds_json_success('操作成功', $result);
    }

    public function createRiderFeeRule()
    {
        $data = array(
            // 规则名称
            'rule_name' => input('param.rule_name', ''),
            // 基础配送费
            'base_fee' => input('param.base_fee', 0),
            // 距离费用计算类型
            'distance_fee_type' => input('param.distance_fee_type', 1),
            // 距离规则
            'distance_rules' => input('param.distance_rules', []),
            // 重量费用计算类型
            'weight_fee_type' => input('param.weight_fee_type', 0),
            // 重量规则
            'weight_rules' => input('param.weight_rules', []),
            // 时段费用计算类型
            'time_period_fee_type' => input('param.time_period_fee_type', 0),
            // 时段规则
            'time_period_rules' => input('param.time_period_rules', []),
            // 天气费用计算类型
            'weather_fee_type' => input('param.weather_fee_type', 0),
            // 天气规则
            'weather_rules' => input('param.weather_rules', []),
            // 骑手配送费抽成比例
            'rider_fee_rate' => input('param.rider_fee_rate', 0),
            // 是否启用
            'is_enabled' => input('param.is_enabled', 0),
            // 适用区域
            'area_id' => input('param.area_id', 0),
        );

        // 验证数据
        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderFeeRuleValidate.create');

        $result = (new RiderFeeRuleService())->createRiderFeeRule($data);
        return ds_json_success('操作成功', $result);
    }
    
    /**
     * 更新骑手配送费规则
     */
    public function updateRiderFeeRule()
    {
        $data = array(
            // 规则ID
            'id' => input('param.id', 0),
            // 规则名称
            'rule_name' => input('param.rule_name', ''),
            // 基础配送费
            'base_fee' => input('param.base_fee', 0),
            // 距离费用计算类型
            'distance_fee_type' => input('param.distance_fee_type', 1),
            // 距离规则
            'distance_rules' => input('param.distance_rules', []),
            // 重量费用计算类型
            'weight_fee_type' => input('param.weight_fee_type', 0),
            // 重量规则
            'weight_rules' => input('param.weight_rules', []),
            // 时段费用计算类型
            'time_period_fee_type' => input('param.time_period_fee_type', 0),
            // 时段规则
            'time_period_rules' => input('param.time_period_rules', []),
            // 天气费用计算类型
            'weather_fee_type' => input('param.weather_fee_type', 0),
            // 天气规则
            'weather_rules' => input('param.weather_rules', []),
            // 骑手配送费抽成比例
            'rider_fee_rate' => input('param.rider_fee_rate', 0),
            // 是否启用
            'is_enabled' => input('param.is_enabled', 0),
            // 适用区域
            'area_id' => input('param.area_id', 0),
        );

        // 验证数据
        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderFeeRuleValidate.update');

        $result = (new RiderFeeRuleService())->updateRiderFeeRule($data);
        return ds_json_success('更新成功', $result);
    }
    
    /**
     * 获取骑手配送费规则详情
     */
    public function getRiderFeeRuleInfo()
    {
        $id = input('param.id', 0);
        if (empty($id)) {
            return ds_json_error('规则ID不能为空');
        }
        
        $result = (new RiderFeeRuleService())->getRiderFeeRuleInfo($id);
        return ds_json_success('获取成功', $result);
    }
    
    /**
     * 删除骑手配送费规则
     */
    public function deleteRiderFeeRule()
    {
        $id = input('param.id', 0);
        if (empty($id)) {
            return ds_json_error('规则ID不能为空');
        }
        
        $result = (new RiderFeeRuleService())->deleteRiderFeeRule($id);
        return ds_json_success('删除成功', $result);
    }
}
