<?php


namespace app\deshang\service\distributor;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\SystemException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\distributor\DistributorBalanceEnum;

use app\common\dao\distributor\DistributorBalanceLogDao;
use app\common\dao\user\UserDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;


class DeshangDistributorBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改分销员金额 调用建议通过事务处理
    public function modifyDistributorBalance($data){

        // 使用枚举类验证变动方式
        if (!array_key_exists($data['change_mode'], DistributorBalanceEnum::getChangeModeDict())) {
            throw new CommonException('DistributorBalanceEnum  变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($data['change_type'], DistributorBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('DistributorBalanceEnum 变动类型错误');
        }

        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($data['change_amount'])) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($data['change_amount'] <= 0) {
            throw new CommonException('金额必须为正数');
        }

        // ========== 第一层：分布式锁（减少并发冲突）==========
        $lockKey = sprintf(LockKeyManager::LOCK_DISTRIBUTOR_BALANCE_KEY, $data['distributor_user_id']);
        $lockValue = KvManager::lock()->acquire($lockKey, 5);
        if (!$lockValue) {
            throw new SystemException('分销员余额更新失败，系统繁忙，请稍后重试');
        }
        
        try {
            // ========== 第二层：乐观锁（确保数据一致性）==========
            // 在分布式锁内使用乐观锁重试机制
            $maxRetries = 3;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $result = $this->tryModifyDistributorBalance($data);
                if ($result) {
                    return true;
                }
                $retryCount++;

                // 延迟后重试（使用指数退避 + 随机延迟，避免惊群效应）
                if ($retryCount < $maxRetries) {
                    ds_retry_delay($retryCount); // 指数退避：第1次约5ms，第2次约10ms，第3次约20ms
                }
            }

            throw new SystemException('分销员余额更新失败，版本冲突，已重试' . $maxRetries . '次', 409);
        } finally {
            // 释放分布式锁
            KvManager::lock()->release($lockValue, $lockKey);
        }


    }





    
    private function tryModifyDistributorBalance($data)
    {
        //获取用户信息
        $distributor_user_info = (new UserDao())->getUserInfoById($data['distributor_user_id'], 'id,distributor_balance,distributor_balance_in,distributor_balance_out,version');
        if (empty($distributor_user_info)) {
            throw new CommonException('分销员不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($data['change_mode'] == DistributorBalanceEnum::MODE_DECREASE) {
            if ($distributor_user_info['distributor_balance'] < $data['change_amount']) {
                throw new CommonException('分销员余额不足');
            }
        }

        $after_distributor_balance = $data['change_mode'] == DistributorBalanceEnum::MODE_INCREASE ? $distributor_user_info['distributor_balance'] + $data['change_amount'] : $distributor_user_info['distributor_balance'] - $data['change_amount'];

        //修改用户余额
        $distributor_user_updata = array(
            'distributor_balance' => $after_distributor_balance
        );
        switch ($data['change_mode']) {
            case DistributorBalanceEnum::MODE_INCREASE:
                //收入总额
                $distributor_user_updata['distributor_balance_in'] = $distributor_user_info['distributor_balance_in'] + $data['change_amount'];
                break;
            case DistributorBalanceEnum::MODE_DECREASE:
                //支出总额
                $distributor_user_updata['distributor_balance_out'] = $distributor_user_info['distributor_balance_out'] + $data['change_amount'];
                break;
        }
        // 版本号+1
        $distributor_user_updata['version'] = $distributor_user_info['version'] + 1;

        // 使用条件更新，确保更新的是读取时的分销余额和版本号（双重验证，防止并发问题）
        // 条件1：用户ID匹配
        // 条件2：分销余额必须等于读取时的值（确保分销余额未被其他操作修改）
        // 条件3：版本号必须等于读取时的值（乐观锁，确保版本号未被其他操作修改）
        $affectedRows = (new UserDao())->updateUser(
            [
                ['id', '=', $data['distributor_user_id']],
                ['distributor_balance', '=', $distributor_user_info['distributor_balance']],
                ['version', '=', $distributor_user_info['version']]
            ],
            $distributor_user_updata
        );

        // 如果影响行数为0，则表示更新失败
        if ($affectedRows === 0) {
            return false;
        }

        $balance_data = array(
            'distributor_user_id' => $data['distributor_user_id'],
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $data['change_type'], // 变动类型 
            'change_mode' => $data['change_mode'], // 变动方式 1 增加 2 减少
            'change_amount' => $data['change_amount'], // 变动金额
            'before_balance' => $distributor_user_info['distributor_balance'], // 变动前金额
            'after_balance' => $after_distributor_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        (new DistributorBalanceLogDao())->createDistributorBalanceLog($balance_data);

        return true;
    }
}
