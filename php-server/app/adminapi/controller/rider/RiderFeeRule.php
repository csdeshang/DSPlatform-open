<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\rider\RiderFeeRuleService;

/**
 * @OA\Tag(
 *     name="admin-api/rider/RiderFeeRule",
 *     description="骑手配送费规则管理接口"
 * )
 */
class RiderFeeRule extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/rider/fee-rules/pages",
     *     summary="获取骑手配送费规则分页列表",
     *     tags={"admin-api/rider/RiderFeeRule"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="规则名称",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getRiderFeeRulePages()
    {

        $data = array(
            'name' => input('param.name', ''),
        );

        $result = (new RiderFeeRuleService())->getRiderFeeRulePages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/rider/fee-rules",
     *     summary="创建骑手配送费规则",
     *     tags={"admin-api/rider/RiderFeeRule"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="骑手配送费规则创建所需信息",
     *         @OA\JsonContent(
     *             required={"rule_name", "base_fee"},
     *             @OA\Property(property="rule_name", type="string", example="标准配送费规则"),
     *             @OA\Property(property="base_fee", type="number", example=5.00),
     *             @OA\Property(property="distance_fee_type", type="integer", example=1),
     *             @OA\Property(property="distance_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="weight_fee_type", type="integer", example=0),
     *             @OA\Property(property="weight_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="time_period_fee_type", type="integer", example=0),
     *             @OA\Property(property="time_period_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="weather_fee_type", type="integer", example=0),
     *             @OA\Property(property="weather_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="rider_fee_rate", type="number", example=0.8),
     *             @OA\Property(property="is_enabled", type="integer", example=1),
     *             @OA\Property(property="area_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="integer", example=1, description="新创建的规则ID")
     *         )
     *     )
     * )
     */
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
     * @OA\Put(
     *     path="/adminapi/rider/fee-rules/{id}",
     *     summary="更新骑手配送费规则",
     *     tags={"admin-api/rider/RiderFeeRule"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="骑手配送费规则更新所需信息",
     *         @OA\JsonContent(
     *             required={"id", "rule_name", "base_fee"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="rule_name", type="string", example="标准配送费规则"),
     *             @OA\Property(property="base_fee", type="number", example=5.00),
     *             @OA\Property(property="distance_fee_type", type="integer", example=1),
     *             @OA\Property(property="distance_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="weight_fee_type", type="integer", example=0),
     *             @OA\Property(property="weight_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="time_period_fee_type", type="integer", example=0),
     *             @OA\Property(property="time_period_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="weather_fee_type", type="integer", example=0),
     *             @OA\Property(property="weather_rules", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="rider_fee_rate", type="number", example=0.8),
     *             @OA\Property(property="is_enabled", type="integer", example=1),
     *             @OA\Property(property="area_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="更新成功"),
     *             @OA\Property(property="data", type="boolean", example=true)
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/adminapi/rider/fee-rules/{id}",
     *     summary="获取骑手配送费规则详情",
     *     tags={"admin-api/rider/RiderFeeRule"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="规则ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="获取成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="获取成功"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rule_name", type="string", example="标准配送费规则"),
     *                 @OA\Property(property="base_fee", type="number", example=5.00),
     *                 @OA\Property(property="distance_fee_type", type="integer", example=1),
     *                 @OA\Property(property="distance_rules", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="weight_fee_type", type="integer", example=0),
     *                 @OA\Property(property="weight_rules", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="time_period_fee_type", type="integer", example=0),
     *                 @OA\Property(property="time_period_rules", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="weather_fee_type", type="integer", example=0),
     *                 @OA\Property(property="weather_rules", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="rider_fee_rate", type="number", example=0.8),
     *                 @OA\Property(property="is_enabled", type="integer", example=1),
     *                 @OA\Property(property="area_id", type="integer", example=1),
     *                 @OA\Property(property="create_at", type="string", example="2023-01-01 12:00:00"),
     *                 @OA\Property(property="update_at", type="string", example="2023-01-01 12:00:00")
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/adminapi/rider/fee-rules/{id}",
     *     summary="删除骑手配送费规则",
     *     tags={"admin-api/rider/RiderFeeRule"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="规则ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="删除成功"),
     *             @OA\Property(property="data", type="boolean", example=true)
     *         )
     *     )
     * )
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
