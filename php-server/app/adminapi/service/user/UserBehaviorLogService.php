<?php

namespace app\adminapi\service\user;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\user\UserBehaviorLogDao;
use app\deshang\utils\SearchHelper;
use app\deshang\exceptions\CommonException;

class UserBehaviorLogService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取用户行为日志分页列表
     * @param array $data 查询条件
     * @return array
     */
    public function getUserBehaviorLogPages(array $data): array
    {
        $condition = [];
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $condition[] = ['username', 'like', '%' . $data['username'] . '%'];
        }
        
        // 行为类型筛选
        if (isset($data['behavior_type']) && $data['behavior_type'] !== '') {
            $condition[] = ['behavior_type', '=', $data['behavior_type']];
        }
        
        // 行为状态筛选
        if (isset($data['behavior_status']) && $data['behavior_status'] !== '') {
            $condition[] = ['behavior_status', '=', $data['behavior_status']];
        }
        
        // 风险等级筛选
        if (isset($data['risk_level']) && $data['risk_level'] !== '') {
            $condition[] = ['risk_level', '=', $data['risk_level']];
        }
        
        // IP地址筛选
        if (isset($data['ip_address']) && $data['ip_address'] !== '') {
            $condition[] = ['ip_address', 'like', '%' . $data['ip_address'] . '%'];
        }



        $result = (new UserBehaviorLogDao())->getWithRelUserBehaviorLogPages($condition);
        return $result;
    }

    /**
     * 获取用户行为日志详情
     * @param int $id 日志ID
     * @return array
     */
    public function getUserBehaviorLogInfo(int $id): array
    {
        $result = (new UserBehaviorLogDao())->getUserBehaviorLogInfo(['id' => $id]);   
        return $result;
    }

    /**
     * 删除用户行为日志
     * @param int $id 日志ID
     * @return void
     * @throws CommonException
     */
    public function deleteUserBehaviorLog(int $id): void
    {
        // 检查日志是否存在
        $log = (new UserBehaviorLogDao())->getUserBehaviorLogInfo(['id' => $id]);
        if (empty($log)) {
            throw new CommonException('日志不存在');
        }
        
        // 删除日志
        (new UserBehaviorLogDao())->deleteUserBehaviorLog(['id' => $id]);
    }
}
