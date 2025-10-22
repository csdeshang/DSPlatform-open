<?php


namespace app\adminapi\service\user;

use think\facade\Db;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\user\UserWithdrawalLogDao;

use app\common\enum\user\UserWithdrawalEnum;
use app\common\enum\user\UserBalanceEnum;
use app\deshang\service\user\DeshangUserBalanceService;

use app\deshang\exceptions\CommonException;

use app\deshang\service\trade\DeshangTradeTransferService;
use app\deshang\utils\SearchHelper;


class UserWithdrawalService extends BaseAdminService
{
    /**
     * 获取会员提现记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getUserWithdrawalLogPages(array $data): array
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
        
        // 转账单号搜索
        if (isset($data['out_transfer_no']) && $data['out_transfer_no'] !== '') {
            $condition[] = ['out_transfer_no', 'like', '%' . $data['out_transfer_no'] . '%'];
        }
        
        // 提现金额区间搜索
        if (isset($data['withdrawal_amount_min']) && $data['withdrawal_amount_min'] !== '') {
            $condition[] = ['withdrawal_amount', '>=', $data['withdrawal_amount_min']];
        }
        if (isset($data['withdrawal_amount_max']) && $data['withdrawal_amount_max'] !== '') {
            $condition[] = ['withdrawal_amount', '<=', $data['withdrawal_amount_max']];
        }

        $result = (new UserWithdrawalLogDao())->getWithRelWithdrawalLogPages($condition);
        return $result;
    }


    public function getUserWithdrawalLogInfo($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new UserWithdrawalLogDao())->getWithdrawalLogInfo($condition);
        return $result;
    }


    // 管理员操作提现
    public function operationUserWithdrawal($id, $data)
    {
        $withdrawal_log = (new UserWithdrawalLogDao())->getWithdrawalLogInfo([['id', '=', $id]]);
        if ($withdrawal_log['status'] != UserWithdrawalEnum::STATUS_APPLY) {
            throw new CommonException('提现状态不正确');
        }


        // 如果拒绝 则申请金额退回至账户
        if ($data['status'] == UserWithdrawalEnum::STATUS_REJECTED) {
            // 退回至账户
            (new DeshangUserBalanceService())->modifyUserBalance([
                'user_id' => $withdrawal_log['user_id'],
                'related_id' => $id,
                'change_type' => UserBalanceEnum::TYPE_WITHDRAWAL_REJECT,
                'change_mode' => UserBalanceEnum::MODE_INCREASE,
                'change_amount' => $withdrawal_log['apply_amount'],
                'change_desc' => '提现被拒绝，退回至账户,拒绝原因：'.$data['operation_remark'],
            ]);


            // 更新提现状态
            $update_data = [
                'status' => $data['status'],
                'transfer_type' => '',
                'transfer_remark' => $data['transfer_remark'],
                'operation_time' => time(),
                'operation_remark' => $data['operation_remark'],
            ];
            (new UserWithdrawalLogDao())->updateWithdrawalLog([['id', '=', $id]], $update_data);
        } else if ($data['status'] == UserWithdrawalEnum::STATUS_APPROVED) {

            // 转账
            $result = (new DeshangTradeTransferService())->transfer($data['transfer_type'], $withdrawal_log);

            if ($result == true) {
                // 更新提现状态
                $update_data = [
                    'status' => $data['status'],
                    'transfer_type' => $data['transfer_type'],
                    'transfer_remark' => $data['transfer_remark'],
                    'operation_time' => time(),
                    'operation_remark' => $data['operation_remark'],
                ];
                (new UserWithdrawalLogDao())->updateWithdrawalLog([['id', '=', $id]], $update_data);
            } else {
                throw new CommonException('系统错误');
            }

        }
    }
}
