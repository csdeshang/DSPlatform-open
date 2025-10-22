<?php

namespace app\adminapi\service\rider;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\rider\RiderFeeRuleDao;
use app\deshang\exceptions\CommonException;
use app\common\enum\rider\RiderFeeRuleEnum;



class RiderFeeRuleService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getRiderFeeRulePages($data){

        $condition = [];
        if (isset($data['name']) && $data['name'] != '') {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }
        $result = (new RiderFeeRuleDao())->getRiderFeeRulePages($condition);
        return $result;

    }

    public function createRiderFeeRule($data){
        // 准备保存到数据库的数据
        $saveData = [
            'rule_name' => $data['rule_name'],
            'base_fee' => $data['base_fee'],
            'distance_fee_type' => $data['distance_fee_type'],
            'weight_fee_type' => $data['weight_fee_type'],
            'time_period_fee_type' => $data['time_period_fee_type'],
            'weather_fee_type' => $data['weather_fee_type'],
            'rider_fee_rate' => $data['rider_fee_rate'],
            'is_enabled' => $data['is_enabled'],
            'area_id' => $data['area_id'],
            'create_time' => time(),
            'update_time' => time()
        ];

        // 将规则数据转换为JSON字符串存储
        if (!empty($data['distance_rules'])) {
            $saveData['distance_rules'] = json_encode($data['distance_rules'], JSON_UNESCAPED_UNICODE);
        }
        
        // 对于重量规则，只有当weight_fee_type不为0且规则不为空时才存储
        if ($data['weight_fee_type'] != RiderFeeRuleEnum::WEIGHT_FEE_TYPE_NONE && !empty($data['weight_rules'])) {
            $saveData['weight_rules'] = json_encode($data['weight_rules'], JSON_UNESCAPED_UNICODE);
        } else {
            // 如果不计算重量费用或规则为空，则存储空JSON对象
            $saveData['weight_rules'] = '{}';
        }
        
        if (!empty($data['time_period_rules'])) {
            $saveData['time_period_rules'] = json_encode($data['time_period_rules'], JSON_UNESCAPED_UNICODE);
        }
        
        if (!empty($data['weather_rules'])) {
            $saveData['weather_rules'] = json_encode($data['weather_rules'], JSON_UNESCAPED_UNICODE);
        }

        try {
            // 保存数据
            $ruleId = (new RiderFeeRuleDao())->createRiderFeeRule($saveData);
            if (!$ruleId) {
                throw new CommonException('创建骑手配送费规则失败');
            }
            
            return $ruleId;
        } catch (\Exception $e) {
            throw new CommonException($e->getMessage());
        }
    }
    
    /**
     * 更新骑手配送费规则
     * 
     * @param array $data 更新数据
     * @return bool 更新结果
     */
    public function updateRiderFeeRule($data)
    {
        // 检查规则是否存在
        $ruleInfo = (new RiderFeeRuleDao())->getRiderFeeRuleInfoById($data['id']);
        if (empty($ruleInfo)) {
            throw new CommonException('骑手配送费规则不存在');
        }
        
        // 准备更新数据
        $updateData = [
            'rule_name' => $data['rule_name'],
            'base_fee' => $data['base_fee'],
            'distance_fee_type' => $data['distance_fee_type'],
            'weight_fee_type' => $data['weight_fee_type'],
            'time_period_fee_type' => $data['time_period_fee_type'],
            'weather_fee_type' => $data['weather_fee_type'],
            'rider_fee_rate' => $data['rider_fee_rate'],
            'is_enabled' => $data['is_enabled'],
            'area_id' => $data['area_id'],
            'update_time' => time()
        ];
        
        // 将规则数据转换为JSON字符串存储
        if (!empty($data['distance_rules'])) {
            $updateData['distance_rules'] = json_encode($data['distance_rules'], JSON_UNESCAPED_UNICODE);
        }
        
        // 对于重量规则，只有当weight_fee_type不为0且规则不为空时才存储
        if ($data['weight_fee_type'] != RiderFeeRuleEnum::WEIGHT_FEE_TYPE_NONE && !empty($data['weight_rules'])) {
            $updateData['weight_rules'] = json_encode($data['weight_rules'], JSON_UNESCAPED_UNICODE);
        } else {
            // 如果不计算重量费用或规则为空，则存储空JSON对象
            $updateData['weight_rules'] = '{}';
        }
        
        if (!empty($data['time_period_rules'])) {
            $updateData['time_period_rules'] = json_encode($data['time_period_rules'], JSON_UNESCAPED_UNICODE);
        }
        
        if (!empty($data['weather_rules'])) {
            $updateData['weather_rules'] = json_encode($data['weather_rules'], JSON_UNESCAPED_UNICODE);
        }
        
        try {
            // 更新数据
            $result = (new RiderFeeRuleDao())->updateRiderFeeRule(['id' => $data['id']], $updateData);
            if (!$result) {
                throw new CommonException('更新骑手配送费规则失败');
            }
            
            return true;
        } catch (\Exception $e) {
            throw new CommonException($e->getMessage());
        }
    }
    
    /**
     * 获取骑手配送费规则详情
     * 
     * @param int $id 规则ID
     * @return array 规则详情
     */
    public function getRiderFeeRuleInfo($id)
    {
        $ruleInfo = (new RiderFeeRuleDao())->getRiderFeeRuleInfoById($id);
        if (empty($ruleInfo)) {
            throw new CommonException('骑手配送费规则不存在');
        }
        
        // 将JSON字符串转换为数组
        $ruleInfo['distance_rules'] = !empty($ruleInfo['distance_rules']) ? json_decode($ruleInfo['distance_rules'], true) : [];
        
        $ruleInfo['weight_rules'] = !empty($ruleInfo['weight_rules']) ? json_decode($ruleInfo['weight_rules'], true) : [];
        
        $ruleInfo['time_period_rules'] = !empty($ruleInfo['time_period_rules']) ? json_decode($ruleInfo['time_period_rules'], true) : [];
        
        $ruleInfo['weather_rules'] = !empty($ruleInfo['weather_rules']) ? json_decode($ruleInfo['weather_rules'], true) : [];
        
        return $ruleInfo;
    }
    
    /**
     * 删除骑手配送费规则
     * 
     * @param int $id 规则ID
     * @return bool 删除结果
     */
    public function deleteRiderFeeRule($id)
    {
        // 检查规则是否存在
        $ruleInfo = (new RiderFeeRuleDao())->getRiderFeeRuleInfoById($id);
        if (empty($ruleInfo)) {
            throw new CommonException('骑手配送费规则不存在');
        }
        
        try {
            // 删除数据
            $result = (new RiderFeeRuleDao())->deleteRiderFeeRule(['id' => $id]);
            if (!$result) {
                throw new CommonException('删除骑手配送费规则失败');
            }
            
            return true;
        } catch (\Exception $e) {
            throw new CommonException($e->getMessage());
        }
    }
}
