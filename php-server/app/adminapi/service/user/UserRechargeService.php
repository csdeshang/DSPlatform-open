<?php


namespace app\adminapi\service\user;


use app\deshang\base\service\BaseAdminService;


use app\common\dao\user\UserRechargeLogDao;
use app\deshang\utils\SearchHelper;


class UserRechargeService extends BaseAdminService
{
    /**
     * 获取会员充值记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getUserRechargeLogPages(array $data): array
    {
        $condition = [];
        if (isset($data['user_id']) && $data['user_id'] != '') {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        if (isset($data['out_trade_no']) && $data['out_trade_no'] !== '') {
            $condition[] = ['out_trade_no', '=', $data['out_trade_no']];
        }
        if (isset($data['trade_no']) && $data['trade_no'] !== '') {
            $condition[] = ['trade_no', '=', $data['trade_no']];
        }
        if (isset($data['pay_merchant_id']) && $data['pay_merchant_id'] !== '') {
            $condition[] = ['pay_merchant_id', '=', $data['pay_merchant_id']];
        }
        if (isset($data['pay_channel']) && $data['pay_channel'] !== '') {
            $condition[] = ['pay_channel', '=', $data['pay_channel']];
        }
        if (isset($data['pay_scene']) && $data['pay_scene'] !== '') {
            $condition[] = ['pay_scene', '=', $data['pay_scene']];
        }
        // 充值金额区间搜索
        if (isset($data['recharge_amount_min']) && $data['recharge_amount_min'] !== '') {
            $condition[] = ['recharge_amount', '>=', $data['recharge_amount_min']];
        }
        if (isset($data['recharge_amount_max']) && $data['recharge_amount_max'] !== '') {
            $condition[] = ['recharge_amount', '<=', $data['recharge_amount_max']];
        }
        if (isset($data['recharge_status']) && $data['recharge_status'] !== '') {
            $condition[] = ['recharge_status', '=', $data['recharge_status']];
        }

        $result = (new UserRechargeLogDao())->getWithRelRechargeLogPages($condition);
        return $result;
    }
}
