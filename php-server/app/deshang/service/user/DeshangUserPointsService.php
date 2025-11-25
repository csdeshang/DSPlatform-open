<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\SystemException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserPointsEnum;

use app\common\dao\user\UserPointsLogDao;
use app\common\dao\user\UserDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;


class DeshangUserPointsService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }


    // 修改用户积分 调用建议通过事务处理
    public function modifyUserPoints($data)
    {
        // 使用枚举类验证变动方式
        if (!array_key_exists($data['change_mode'], UserPointsEnum::getChangeModeDict())) {
            throw new CommonException('UserPointsEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($data['change_type'], UserPointsEnum::getChangeTypeDict())) {
            throw new CommonException('UserPointsEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($data['change_num'])) {
            throw new CommonException('积分格式错误，必须为数字');
        }
        if ($data['change_num'] <= 0) {
            throw new CommonException('积分必须为正数');
        }

        // ========== 第一层：分布式锁（减少并发冲突）==========
        $lockKey = sprintf(LockKeyManager::LOCK_USER_POINTS_KEY, $data['user_id']);
        $lockValue = KvManager::lock()->acquire($lockKey, 5);
        if (!$lockValue) {
            throw new SystemException('积分更新失败，系统繁忙，请稍后重试');
        }
        
        try {
            // ========== 第二层：乐观锁（确保数据一致性）==========
            // 在分布式锁内使用乐观锁重试机制
            $maxRetries = 3;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $result = $this->tryModifyUserPoints($data);
                if ($result) {
                    return true;
                }
                $retryCount++;

                // 延迟后重试（使用指数退避 + 随机延迟，避免惊群效应）
                if ($retryCount < $maxRetries) {
                    ds_retry_delay($retryCount); // 指数退避：第1次约5ms，第2次约10ms，第3次约20ms
                }
            }

            throw new SystemException('积分更新失败，版本冲突，已重试' . $maxRetries . '次', 409);
        } finally {
            // 释放分布式锁
            KvManager::lock()->release($lockValue, $lockKey);
        }
    }





    private function tryModifyUserPoints($data)
    {
        //获取用户信息（包含 version）
        $user_info = (new UserDao())->getUserInfoById($data['user_id'], 'id,points,points_in,points_out,version');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够积分进行扣除
        if ($data['change_mode'] == UserPointsEnum::MODE_DECREASE) {
            if ($user_info['points'] < $data['change_num']) {
                throw new CommonException('用户积分不足');
            }
        }

        $after_points = $data['change_mode'] == UserPointsEnum::MODE_INCREASE
            ? $user_info['points'] + $data['change_num']
            : $user_info['points'] - $data['change_num'];

        //修改用户积分
        $user_updata = array(
            'points' => $after_points
        );
        switch ($data['change_mode']) {
            case UserPointsEnum::MODE_INCREASE:
                //收入总额
                $user_updata['points_in'] = $user_info['points_in'] + $data['change_num'];
                break;
            case UserPointsEnum::MODE_DECREASE:
                //支出总额
                $user_updata['points_out'] = $user_info['points_out'] + $data['change_num'];
                break;
        }
        // 版本号+1
        $user_updata['version'] = $user_info['version'] + 1;

        // 使用条件更新，确保更新的是读取时的积分和版本号（双重验证，防止并发问题）
        // 条件1：用户ID匹配
        // 条件2：积分必须等于读取时的值（确保积分未被其他操作修改）
        // 条件3：版本号必须等于读取时的值（乐观锁，确保版本号未被其他操作修改）
        $affectedRows = (new UserDao())->updateUser(
            [
                ['id', '=', $data['user_id']],
                ['points', '=', $user_info['points']],
                ['version', '=', $user_info['version']]
            ],
            $user_updata
        );

        // 如果影响行数为0，则表示更新失败
        if ($affectedRows === 0) {
            return false;
        }

        // 更新成功后再创建积分日志（保证数据一致性）
        $points_data = array(
            'user_id' => $data['user_id'],
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $data['change_type'], // 变动类型 充值 提现 退款 系统
            'change_mode' => $data['change_mode'], // 变动方式 1 增加 2 减少
            'change_num' => $data['change_num'], // 变动金额
            'before_num' => $user_info['points'], // 变动前金额
            'after_num' => $after_points, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        (new UserPointsLogDao())->createPointsLog($points_data);

        return true;
    }





    /**
     * 订单支付增加积分
     * 
     * 根据系统配置计算订单支付时应给予的积分，支持固定积分和按支付金额比例计算两种方式
     * 
     * 注意：
     * - 此方法不检查配置是否开启（points_pay_enabled），调用方应在入队前检查
     * - 同步执行：Listener 检查配置后调用此方法
     * - 异步执行：Listener 检查配置后决定是否入队，队列处理器调用此方法
     * 
     * @param array $order_info 订单信息，必须包含以下字段：
     *   - user_id: int 用户ID
     *   - id: int 订单ID
     *   - pay_amount: float 支付金额（按比例计算时需要）
     * @return bool true 表示成功增加积分，false 表示配置为0或计算后<=0未处理
     * @throws CommonException 当用户不存在或积分格式错误时抛出异常
     */
    public function addPointsForOrderPay(array $order_info): bool
    {
        // 获取支付积分配置
        $points_pay_amount = sysConfig('points:points_pay_amount');

        if ($points_pay_amount <= 0) {
            return false;
        }


        $change_num = $points_pay_amount;
        $change_desc = '支付获取积分';

        // 检查是否开启按比例获取积分
        $points_payrate_enabled = sysConfig('points:points_payrate_enabled');
        if ($points_payrate_enabled == 1) {
            $points_payrate_amount = sysConfig('points:points_payrate_amount');
            // 按支付金额比例计算积分：积分 = 支付金额 × 比例
            $change_num = intval($points_payrate_amount * $order_info['pay_amount']);
            $change_desc = '支付获取积分,支付金额' . $order_info['pay_amount'] . '元' . '*' . $points_payrate_amount;
        }

        if ($change_num <= 0) {
            return false;
        }

        // 如果计算后的积分 > 0，则增加积分

        $add_data = array(
            'user_id' => $order_info['user_id'],
            'related_id' => $order_info['id'],
            'change_type' => UserPointsEnum::TYPE_ORDER_PAY,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $change_num,
            'change_desc' => $change_desc,
        );

        $this->modifyUserPoints($add_data);

        return true;
    }


    /**
     * 订单取消扣除积分
     * 
     * 查询订单支付时获得的积分，并扣除相同数量的积分
     * 
     * 注意：
     * - 此方法会自动查询订单支付时获得的积分，无需手动计算
     * - 如果未找到支付时的积分记录，抛出异常，队列会自动重试
     * - 如果依赖任务未完成，重试时会等待；如果依赖任务失败，重试几次后会标记为失败
     * 
     * @param array $order_info 订单信息，必须包含以下字段：
     *   - user_id: int 用户ID
     *   - id: int 订单ID
     * @return bool true 表示成功扣除积分，false 表示参数无效
     * @throws SystemException 当未找到积分记录时抛出（触发队列重试）
     * @throws CommonException 当用户不存在、积分不足或积分格式错误时抛出异常
     */
    public function deductPointsForOrderCancel(array $order_info): bool
    {
        $order_id = (int)$order_info['id'];
        $user_id = (int)$order_info['user_id'];

        if ($order_id <= 0 || $user_id <= 0) {
            return false; // 参数无效
        }

        // 查询订单支付时获得的积分记录（正常情况下每个订单只会有一条支付积分记录）
        $points_log = (new UserPointsLogDao())->getPointsLogInfo([
            ['related_id', '=', $order_id],
            ['user_id', '=', $user_id],
            ['change_type', '=', UserPointsEnum::TYPE_ORDER_PAY],
            ['change_mode', '=', UserPointsEnum::MODE_INCREASE],
        ], 'id,change_num');

        // 如果未找到积分记录，直接抛异常，队列会自动重试
        // 如果依赖任务未完成，重试时会等待；如果依赖任务失败，重试几次后会标记为失败
        if (empty($points_log)) {
            throw new SystemException(
                "订单取消扣除积分失败：未找到订单支付时的积分记录。订单ID: {$order_id}, 用户ID: {$user_id}",
                409 // 409 Conflict，表示需要重试
            );
        }

        // 获取需要扣除的积分数量
        $deduct_num = (int)$points_log['change_num'];

        // 如果扣除数量 <= 0，说明数据异常，抛出异常
        if ($deduct_num <= 0) {
            throw new SystemException(
                "订单取消扣除积分失败：积分记录中的数量异常（<=0）。订单ID: {$order_id}, 用户ID: {$user_id}, 积分数量: {$deduct_num}",
                409 // 409 Conflict，表示需要重试
            );
        }

        // 构建积分扣除数据
        $deduct_data = array(
            'user_id' => $user_id,
            'related_id' => $order_id,
            'change_type' => UserPointsEnum::TYPE_ORDER_PAY,
            'change_mode' => UserPointsEnum::MODE_DECREASE,
            'change_num' => $deduct_num,
            'change_desc' => '订单取消扣除积分',
        );

        // 调用统一的积分修改方法（内部会检查积分是否足够）
        $this->modifyUserPoints($deduct_data);

        return true;
    }





    /**
     * 用户注册增加积分
     * 
     * 根据系统配置计算用户注册时应给予的积分
     * 
     * 注意：
     * - 此方法不检查配置是否开启（points_register_enabled），调用方应在调用前检查
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加积分，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或积分格式错误时抛出异常
     */
    public function addPointsForUserRegister(int $user_id): bool
    {
        // 获取注册积分配置
        $points_register_amount = sysConfig('points:points_register_amount');

        if ($points_register_amount <= 0) {
            return false;
        }
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserPointsEnum::TYPE_REGISTER,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $points_register_amount,
            'change_desc' => '注册获取积分',
        );

        $this->modifyUserPoints($add_data);
        return true;
    }



    /**
     * 检查用户今天是否已领取登录积分
     * 
     * 用于判断用户今天是否已经领取过登录积分奖励
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示今天已领取，false 表示今天未领取
     */
    public function hasReceivedLoginPointsToday(int $user_id): bool
    {
        $condition = [
            ['user_id', '=', $user_id],
            ['change_type', '=', UserPointsEnum::TYPE_LOGIN],
            ['change_mode', '=', UserPointsEnum::MODE_INCREASE],
            ['create_at', '>=', strtotime(date('Y-m-d'))],
        ];
        $today_points_log = (new UserPointsLogDao())->getPointsLogInfo($condition);

        return !empty($today_points_log);
    }

    /**
     * 用户登录增加积分
     * 
     * 根据系统配置计算用户登录时应给予的积分，每天首次登录才能获取
     * 
     * 注意：
     * - 此方法不检查配置是否开启（points_login_enabled），调用方应在调用前检查
     * - 会自动检查今天是否已领取，如果已领取则返回 false（幂等）
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加积分，false 表示配置为0、今天已领取或未处理
     * @throws CommonException 当用户不存在或积分格式错误时抛出异常
     */
    public function addPointsForUserLogin(int $user_id): bool
    {
        // 获取登录积分配置
        $points_login_amount = sysConfig('points:points_login_amount');

        if ($points_login_amount <= 0) {
            return false;
        }

        // 判断今天是否已领取（使用独立方法）
        if ($this->hasReceivedLoginPointsToday($user_id)) {
            return false;
        }

        // 构建积分增加数据
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserPointsEnum::TYPE_LOGIN,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $points_login_amount,
            'change_desc' => '登录获取积分',
        );

        // 调用统一的积分修改方法
        $this->modifyUserPoints($add_data);

        return true;
    }






    /**
     * 用户邀请增加积分
     * 
     * 根据系统配置计算用户邀请新用户注册时应给予的积分奖励
     * 
     * 注意：
     * - 此方法不检查配置是否开启（points_invite_enabled），调用方应在调用前检查
     * 
     * @param int $inviter_id 邀请人ID
     * @return bool true 表示成功增加积分，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或积分格式错误时抛出异常
     */
    public function addPointsForUserInvite(int $inviter_id): bool
    {
        // 获取邀请积分配置
        $points_invite_amount = sysConfig('points:points_invite_amount');

        if ($points_invite_amount <= 0) {
            return false;
        }

        // 构建积分增加数据
        $add_data = array(
            'user_id' => $inviter_id,
            'related_id' => 0,
            'change_type' => UserPointsEnum::TYPE_INVITE,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $points_invite_amount,
            'change_desc' => '邀请获取积分',
        );

        // 调用统一的积分修改方法
        $this->modifyUserPoints($add_data);

        return true;
    }






    /**
     * 用户商品评论增加积分
     * 
     * 根据系统配置计算用户评论商品时应给予的积分奖励
     * 
     * 注意：
     * - 此方法不检查配置是否开启（points_review_enabled），调用方应在调用前检查
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加积分，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或积分格式错误时抛出异常
     */
    public function addPointsForUserGoodsComment(int $user_id): bool
    {
        // 获取评论积分配置
        $points_review_amount = sysConfig('points:points_review_amount');

        if ($points_review_amount <= 0) {
            return false;
        }

        // 构建积分增加数据
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserPointsEnum::TYPE_GOODS_COMMENT,
            'change_mode' => UserPointsEnum::MODE_INCREASE,
            'change_num' => $points_review_amount,
            'change_desc' => '评论获取积分',
        );

        // 调用统一的积分修改方法
        $this->modifyUserPoints($add_data);

        return true;
    }
}
