<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\SystemException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserBalanceEnum;

use app\common\dao\user\UserBalanceLogDao;
use app\common\dao\user\UserDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;


class DeshangUserBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改用户金额 调用建议通过事务处理
    public function modifyUserBalance($data)
    {
        // 使用枚举类验证变动方式
        if (!array_key_exists($data['change_mode'], UserBalanceEnum::getChangeModeDict())) {
            throw new CommonException('UserBalanceEnum  变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($data['change_type'], UserBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('UserBalanceEnum 变动类型错误');
        }

        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($data['change_amount'])) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($data['change_amount'] <= 0) {
            throw new CommonException('金额必须为正数');
        }

        // ========== 第一层：分布式锁（减少并发冲突）==========
        $lockKey = sprintf(LockKeyManager::LOCK_USER_BALANCE_KEY, $data['user_id']);
        $lockValue = KvManager::lock()->acquire($lockKey, 5);
        if (!$lockValue) {
            throw new SystemException('余额更新失败，系统繁忙，请稍后重试');
        }
        
        try {
            // ========== 第二层：乐观锁（确保数据一致性）==========
            // 在分布式锁内使用乐观锁重试机制
            $maxRetries = 3;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $result = $this->tryModifyUserBalance($data);
                if ($result) {
                    return true;
                }
                $retryCount++;

                // 延迟后重试（使用指数退避 + 随机延迟，避免惊群效应）
                if ($retryCount < $maxRetries) {
                    ds_retry_delay($retryCount); // 指数退避：第1次约5ms，第2次约10ms，第3次约20ms
                }
            }

            throw new SystemException('余额更新失败，版本冲突，已重试' . $maxRetries . '次', 409);
        } finally {
            // 释放分布式锁
            KvManager::lock()->release($lockValue, $lockKey);
        }
    }




    private function tryModifyUserBalance($data)
    {
        //获取用户信息
        $user_info = (new UserDao())->getUserInfoById($data['user_id'], 'id,balance,balance_in,balance_out,version');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($data['change_mode'] == UserBalanceEnum::MODE_DECREASE) {
            if ($user_info['balance'] < $data['change_amount']) {
                throw new CommonException('用户余额不足');
            }
        }

        $after_balance = $data['change_mode'] == 1 ? $user_info['balance'] + $data['change_amount'] : $user_info['balance'] - $data['change_amount'];

        //修改用户余额
        $user_updata = array(
            'balance' => $after_balance
        );
        switch ($data['change_mode']) {
            case UserBalanceEnum::MODE_INCREASE:
                //收入总额
                $user_updata['balance_in'] = $user_info['balance_in'] + $data['change_amount'];
                break;
            case UserBalanceEnum::MODE_DECREASE:
                //支出总额
                $user_updata['balance_out'] = $user_info['balance_out'] + $data['change_amount'];
                break;
        }
        // 版本号+1
        $user_updata['version'] = $user_info['version'] + 1;

        // 使用条件更新，确保更新的是读取时的余额和版本号（双重验证，防止并发问题）
        // 条件1：用户ID匹配
        // 条件2：余额必须等于读取时的值（确保余额未被其他操作修改）
        // 条件3：版本号必须等于读取时的值（乐观锁，确保版本号未被其他操作修改）
        $affectedRows = (new UserDao())->updateUser(
            [
                ['id', '=', $data['user_id']],
                ['balance', '=', $user_info['balance']],
                ['version', '=', $user_info['version']]
            ],
            $user_updata
        );

        // 如果影响行数为0，则表示更新失败
        if ($affectedRows === 0) {
            return false;
        }


        $balance_data = array(
            'user_id' => $data['user_id'],
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $data['change_type'], // 变动类型 充值 提现 退款 系统
            'change_mode' => $data['change_mode'], // 变动方式 1 增加 2 减少
            'change_amount' => $data['change_amount'], // 变动金额
            'before_balance' => $user_info['balance'], // 变动前金额
            'after_balance' => $after_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        (new UserBalanceLogDao())->createBalanceLog($balance_data);

        return true;
    }
}
