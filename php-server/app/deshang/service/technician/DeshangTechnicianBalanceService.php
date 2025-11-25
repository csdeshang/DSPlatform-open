<?php


namespace app\deshang\service\technician;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\SystemException;
use app\deshang\service\BaseDeshangService;
use app\common\enum\technician\TechnicianBalanceEnum;

use app\common\dao\technician\TechnicianBalanceLogDao;
use app\common\dao\technician\TechnicianDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;


// 师傅余额记录服务
class DeshangTechnicianBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }



    // 修改技师余额 调用建议通过事务处理
    public function modifyTechnicianBalance($data)
    {
        // 使用枚举类验证变动方式
        if (!array_key_exists($data['change_mode'], TechnicianBalanceEnum::getChangeModeDict())) {
            throw new CommonException('TechnicianBalanceEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($data['change_type'], TechnicianBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('TechnicianBalanceEnum 变动类型错误');
        }

        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($data['change_amount'])) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($data['change_amount'] <= 0) {
            throw new CommonException('金额必须为正数');
        }

        // ========== 第一层：分布式锁（减少并发冲突）==========
        $lockKey = sprintf(LockKeyManager::LOCK_TECHNICIAN_BALANCE_KEY, $data['technician_id']);
        $lockValue = KvManager::lock()->acquire($lockKey, 5);
        if (!$lockValue) {
            throw new SystemException('技师余额更新失败，系统繁忙，请稍后重试');
        }
        
        try {
            // ========== 第二层：乐观锁（确保数据一致性）==========
            // 在分布式锁内使用乐观锁重试机制
            $maxRetries = 3;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $result = $this->tryModifyTechnicianBalance($data);
                if ($result) {
                    return true;
                }
                $retryCount++;

                // 延迟后重试（使用指数退避 + 随机延迟，避免惊群效应）
                if ($retryCount < $maxRetries) {
                    ds_retry_delay($retryCount); // 指数退避：第1次约5ms，第2次约10ms，第3次约20ms
                }
            }

            throw new SystemException('技师余额更新失败，版本冲突，已重试' . $maxRetries . '次', 409);
        } finally {
            // 释放分布式锁
            KvManager::lock()->release($lockValue, $lockKey);
        }
    }



    
    private function tryModifyTechnicianBalance($data)
    {
        //获取技师信息
        $technician_info = (new TechnicianDao())->getTechnicianInfoById($data['technician_id'], 'id,balance,balance_in,balance_out,version');
        if (empty($technician_info)) {
            throw new CommonException('技师不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($data['change_mode'] == TechnicianBalanceEnum::MODE_DECREASE) {
            if ($technician_info['balance'] < $data['change_amount']) {
                throw new CommonException('技师余额不足');
            }
        }

        $after_balance = $data['change_mode'] == TechnicianBalanceEnum::MODE_INCREASE ? $technician_info['balance'] + $data['change_amount'] : $technician_info['balance'] - $data['change_amount'];

        //修改技师余额
        $technician_updata = array(
            'balance' => $after_balance
        );
        switch ($data['change_mode']) {
            case TechnicianBalanceEnum::MODE_INCREASE:
                //收入总额
                $technician_updata['balance_in'] = $technician_info['balance_in'] + $data['change_amount'];
                break;
            case TechnicianBalanceEnum::MODE_DECREASE:
                //支出总额
                $technician_updata['balance_out'] = $technician_info['balance_out'] + $data['change_amount'];
                break;
        }
        // 版本号+1
        $technician_updata['version'] = $technician_info['version'] + 1;

        // 使用条件更新，确保更新的是读取时的余额和版本号（双重验证，防止并发问题）
        // 条件1：技师ID匹配
        // 条件2：余额必须等于读取时的值（确保余额未被其他操作修改）
        // 条件3：版本号必须等于读取时的值（乐观锁，确保版本号未被其他操作修改）
        $affectedRows = (new TechnicianDao())->updateTechnician(
            [
                ['id', '=', $data['technician_id']],
                ['balance', '=', $technician_info['balance']],
                ['version', '=', $technician_info['version']]
            ],
            $technician_updata
        );

        // 如果影响行数为0，则表示更新失败
        if ($affectedRows === 0) {
            return false;
        }

        $balance_data = array(
            'technician_id' => $data['technician_id'],
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $data['change_type'], // 变动类型 充值 提现 退款 系统
            'change_mode' => $data['change_mode'], // 变动方式 1 增加 2 减少
            'change_amount' => $data['change_amount'], // 变动金额
            'before_balance' => $technician_info['balance'], // 变动前金额
            'after_balance' => $after_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        (new TechnicianBalanceLogDao())->createTechnicianBalanceLog($balance_data);

        return true;
    }
}
