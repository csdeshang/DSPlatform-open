<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\SystemException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserGrowthEnum;

use app\common\dao\user\UserDao;
use app\common\dao\user\UserGrowthLogDao;
use app\common\dao\user\UserGrowthLevelDao;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\LockKeyManager;

class DeshangUserGrowthService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }


    // 修改用户成长值 调用建议通过事务处理
    public function modifyUserGrowth($data)
    {
        // 使用枚举类验证变动方式
        if (!array_key_exists($data['change_mode'], UserGrowthEnum::getChangeModeDict())) {
            throw new CommonException('UserGrowthEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($data['change_type'], UserGrowthEnum::getChangeTypeDict())) {
            throw new CommonException('UserGrowthEnum 变动类型错误');
        }

        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($data['change_num'])) {
            throw new CommonException('成长值格式错误，必须为数字');
        }
        if ($data['change_num'] <= 0) {
            throw new CommonException('成长值必须为正数');
        }

        // ========== 第一层：分布式锁（减少并发冲突）==========
        $lockKey = sprintf(LockKeyManager::LOCK_USER_GROWTH_KEY, $data['user_id']);
        $lockValue = KvManager::lock()->acquire($lockKey, 5);
        if (!$lockValue) {
            throw new SystemException('成长值更新失败，系统繁忙，请稍后重试');
        }
        
        try {
            // ========== 第二层：乐观锁（确保数据一致性）==========
            // 在分布式锁内使用乐观锁重试机制
            $maxRetries = 3;
            $retryCount = 0;

            while ($retryCount < $maxRetries) {
                $result = $this->tryModifyUserGrowth($data);
                if ($result) {
                    return true;
                }
                $retryCount++;

                // 延迟后重试（使用指数退避 + 随机延迟，避免惊群效应）
                if ($retryCount < $maxRetries) {
                    ds_retry_delay($retryCount); // 指数退避：第1次约5ms，第2次约10ms，第3次约20ms
                }
            }

            throw new SystemException('成长值更新失败，版本冲突，已重试' . $maxRetries . '次', 409);
        } finally {
            // 释放分布式锁
            KvManager::lock()->release($lockValue, $lockKey);
        }
    }


    private function tryModifyUserGrowth($data)
    {
        //获取用户信息（包含 version）
        // 注意：暂时不包含 growth_in 和 growth_out 字段，等数据库添加字段后再启用
        $user_info = (new UserDao())->getUserInfoById($data['user_id'], 'id,growth,growth_level_id,version');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够成长值进行扣除
        if ($data['change_mode'] == UserGrowthEnum::MODE_DECREASE) {
            if ($user_info['growth'] < $data['change_num']) {
                throw new CommonException('用户成长值不足');
            }
        }

        $after_growth = $data['change_mode'] == UserGrowthEnum::MODE_INCREASE 
            ? $user_info['growth'] + $data['change_num'] 
            : $user_info['growth'] - $data['change_num'];

        //修改用户成长值
        $user_updata = array(
            'growth' => $after_growth
        );

        // 获取等级列表
        $growth_level_list = (new UserGrowthLevelDao())->getUserGrowthLevelList([], '*', 'min_growth desc');

        // 计算变动后成长值对应的等级ID
        $new_growth_level_id = $user_info['growth_level_id']; // 默认不变
        foreach ($growth_level_list as $level) {
            if ($after_growth >= $level['min_growth']) {
                $new_growth_level_id = $level['id'];
                break;
            }
        }

        // 如果等级有变化，更新等级ID
        if ($new_growth_level_id != $user_info['growth_level_id']) {
            $user_updata['growth_level_id'] = $new_growth_level_id;
        }

        // 统计成长值收入和支出总额（暂时注释，等数据库添加 growth_in 和 growth_out 字段后再启用）
        // 说明：订单取消会退回成长值，理论上需要统计支出总额，但目前数据库表结构暂未添加这两个字段
        // 如需启用，请先在数据库执行：
        // ALTER TABLE `#__user` 
        // ADD COLUMN `growth_in` int(11) NOT NULL DEFAULT '0' COMMENT '成长值收入总额' AFTER `growth_level_id`,
        // ADD COLUMN `growth_out` int(11) NOT NULL DEFAULT '0' COMMENT '成长值支出总额' AFTER `growth_in`;
        // switch ($data['change_mode']) {
        //     case UserGrowthEnum::MODE_INCREASE:
        //         //收入总额
        //         $user_updata['growth_in'] = $user_info['growth_in'] + $data['change_num'];
        //         break;
        //     case UserGrowthEnum::MODE_DECREASE:
        //         //支出总额（订单取消退回成长值时使用）
        //         $user_updata['growth_out'] = $user_info['growth_out'] + $data['change_num'];
        //         break;
        // }

        // 版本号+1
        $user_updata['version'] = $user_info['version'] + 1;

        // 使用条件更新，确保更新的是读取时的成长值和版本号（双重验证，防止并发问题）
        // 条件1：用户ID匹配
        // 条件2：成长值必须等于读取时的值（确保成长值未被其他操作修改）
        // 条件3：版本号必须等于读取时的值（乐观锁，确保版本号未被其他操作修改）
        $affectedRows = (new UserDao())->updateUser(
            [
                ['id', '=', $data['user_id']],
                ['growth', '=', $user_info['growth']],
                ['version', '=', $user_info['version']]
            ],
            $user_updata
        );

        // 如果影响行数为0，则表示更新失败
        if ($affectedRows === 0) {
            return false;
        }

        // 更新成功后再创建成长值日志（保证数据一致性）
        $growth_data = array(
            'user_id' => $data['user_id'],
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $data['change_type'], // 变动类型 充值 提现 退款 系统
            'change_mode' => $data['change_mode'], // 变动方式 1 增加 2 减少
            'change_num' => $data['change_num'], // 变动金额
            'before_num' => $user_info['growth'], // 变动前金额
            'after_num' => $after_growth, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        (new UserGrowthLogDao())->createGrowthLog($growth_data);

        return true;
    }





    /**
     * 订单支付增加成长值
     * 
     * 根据系统配置计算订单支付时应给予的成长值，支持固定成长值和按支付金额比例计算两种方式
     * 
     * 注意：
     * - 此方法不检查配置是否开启（growth_pay_enabled），调用方应在入队前检查
     * - 同步执行：Listener 检查配置后调用此方法
     * - 异步执行：Listener 检查配置后决定是否入队，队列处理器调用此方法
     * 
     * @param array $order_info 订单信息，必须包含以下字段：
     *   - user_id: int 用户ID
     *   - id: int 订单ID
     *   - pay_amount: float 支付金额（按比例计算时需要）
     * @return bool true 表示成功增加成长值，false 表示配置为0或计算后<=0未处理
     * @throws CommonException 当用户不存在或成长值格式错误时抛出异常
     */
    public function addGrowthForOrderPay(array $order_info): bool
    {
        // 获取支付成长值配置
        $growth_pay_amount = sysConfig('growth:growth_pay_amount');

        if ($growth_pay_amount <= 0) {
            return false;
        }


        $change_num = $growth_pay_amount;
        $change_desc = '支付获取成长值';

        // 检查是否开启按比例获取成长值
        $growth_payrate_enabled = sysConfig('growth:growth_payrate_enabled');
        if ($growth_payrate_enabled == 1) {
            $growth_payrate_amount = sysConfig('growth:growth_payrate_amount');
            // 按支付金额比例计算成长值：成长值 = 支付金额 × 比例
            $change_num = intval($growth_payrate_amount * $order_info['pay_amount']);
            $change_desc = '支付获取成长值,支付金额' . $order_info['pay_amount'] . '元' . '*' . $growth_payrate_amount;
        }

        if ($change_num <= 0) {
            return false;
        }

        // 如果计算后的成长值 > 0，则增加成长值

        $add_data = array(
            'user_id' => $order_info['user_id'],
            'related_id' => $order_info['id'],
            'change_type' => UserGrowthEnum::TYPE_ORDER_PAY,
            'change_mode' => UserGrowthEnum::MODE_INCREASE,
            'change_num' => $change_num,
            'change_desc' => $change_desc,
        );

        $this->modifyUserGrowth($add_data);

        return true;
    }


    /**
     * 订单取消扣除成长值
     * 
     * 查询订单支付时获得的成长值，并扣除相同数量的成长值
     * 
     * 注意：
     * - 此方法会自动查询订单支付时获得的成长值，无需手动计算
     * - 如果未找到支付时的成长值记录，抛出异常，队列会自动重试
     * - 如果依赖任务未完成，重试时会等待；如果依赖任务失败，重试几次后会标记为失败
     * 
     * @param array $order_info 订单信息，必须包含以下字段：
     *   - user_id: int 用户ID
     *   - id: int 订单ID
     * @return bool true 表示成功扣除成长值，false 表示参数无效
     * @throws SystemException 当未找到成长值记录时抛出（触发队列重试）
     * @throws CommonException 当用户不存在、成长值不足或成长值格式错误时抛出异常
     */
    public function deductGrowthForOrderCancel(array $order_info): bool
    {
        $order_id = (int)$order_info['id'];
        $user_id = (int)$order_info['user_id'];

        if ($order_id <= 0 || $user_id <= 0) {
            return false; // 参数无效
        }

        // 查询订单支付时获得的成长值记录（正常情况下每个订单只会有一条支付成长值记录）
        $growth_log = (new UserGrowthLogDao())->getGrowthLogInfo([
            ['related_id', '=', $order_id],
            ['user_id', '=', $user_id],
            ['change_type', '=', UserGrowthEnum::TYPE_ORDER_PAY],
            ['change_mode', '=', UserGrowthEnum::MODE_INCREASE],
        ], 'id,change_num');

        // 如果未找到成长值记录，直接抛异常，队列会自动重试
        // 如果依赖任务未完成，重试时会等待；如果依赖任务失败，重试几次后会标记为失败
        if (empty($growth_log)) {
            throw new SystemException(
                "订单取消扣除成长值失败：未找到订单支付时的成长值记录。订单ID: {$order_id}, 用户ID: {$user_id}",
                409 // 409 Conflict，表示需要重试
            );
        }

        // 获取需要扣除的成长值数量
        $deduct_num = (int)$growth_log['change_num'];

        // 如果扣除数量 <= 0，说明数据异常，抛出异常
        if ($deduct_num <= 0) {
            throw new SystemException(
                "订单取消扣除成长值失败：成长值记录中的数量异常（<=0）。订单ID: {$order_id}, 用户ID: {$user_id}, 成长值数量: {$deduct_num}",
                409 // 409 Conflict，表示需要重试
            );
        }

        // 构建成长值扣除数据
        $deduct_data = array(
            'user_id' => $user_id,
            'related_id' => $order_id,
            'change_type' => UserGrowthEnum::TYPE_ORDER_PAY,
            'change_mode' => UserGrowthEnum::MODE_DECREASE,
            'change_num' => $deduct_num,
            'change_desc' => '订单取消扣除成长值',
        );

        // 调用统一的成长值修改方法（内部会检查成长值是否足够，并自动更新等级）
        $this->modifyUserGrowth($deduct_data);

        return true;
    }



    /**
     * 用户注册增加成长值
     * 
     * 根据系统配置计算用户注册时应给予的成长值
     * 
     * 注意：
     * - 此方法不检查配置是否开启（growth_register_enabled），调用方应在调用前检查
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加成长值，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或成长值格式错误时抛出异常
     */
    public function addGrowthForUserRegister(int $user_id): bool
    {
        // 获取注册成长值配置
        $growth_register_amount = sysConfig('growth:growth_register_amount');

        if ($growth_register_amount <= 0) {
            return false;
        }

        // 构建成长值增加数据
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserGrowthEnum::TYPE_REGISTER,
            'change_mode' => UserGrowthEnum::MODE_INCREASE,
            'change_num' => $growth_register_amount,
            'change_desc' => '注册获取成长值',
        );

        // 调用统一的成长值修改方法
        $this->modifyUserGrowth($add_data);

        return true;
    }



    /**
     * 检查用户今天是否已领取登录成长值
     * 
     * 用于判断用户今天是否已经领取过登录成长值奖励
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示今天已领取，false 表示今天未领取
     */
    public function hasReceivedLoginGrowthToday(int $user_id): bool
    {
        $condition = [
            ['user_id', '=', $user_id],
            ['change_type', '=', UserGrowthEnum::TYPE_LOGIN],
            ['change_mode', '=', UserGrowthEnum::MODE_INCREASE],
            ['create_at', '>=', strtotime(date('Y-m-d'))],
        ];
        $today_growth_log = (new UserGrowthLogDao())->getGrowthLogInfo($condition);
        
        return !empty($today_growth_log);
    }

    /**
     * 用户登录增加成长值
     * 
     * 根据系统配置计算用户登录时应给予的成长值，每天首次登录才能获取
     * 
     * 注意：
     * - 此方法不检查配置是否开启（growth_login_enabled），调用方应在调用前检查
     * - 会自动检查今天是否已领取，如果已领取则返回 false（幂等）
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加成长值，false 表示配置为0、今天已领取或未处理
     * @throws CommonException 当用户不存在或成长值格式错误时抛出异常
     */
    public function addGrowthForUserLogin(int $user_id): bool
    {
        // 获取登录成长值配置
        $growth_login_amount = sysConfig('growth:growth_login_amount');

        if ($growth_login_amount <= 0) {
            return false;
        }

        // 判断今天是否已领取（使用独立方法）
        if ($this->hasReceivedLoginGrowthToday($user_id)) {
            return false;
        }

        // 构建成长值增加数据
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserGrowthEnum::TYPE_LOGIN,
            'change_mode' => UserGrowthEnum::MODE_INCREASE,
            'change_num' => $growth_login_amount,
            'change_desc' => '登录获取成长值',
        );

        // 调用统一的成长值修改方法
        $this->modifyUserGrowth($add_data);

        return true;
    }



    /**
     * 用户邀请增加成长值
     * 
     * 根据系统配置计算用户邀请新用户注册时应给予的成长值奖励
     * 
     * 注意：
     * - 此方法不检查配置是否开启（growth_invite_enabled），调用方应在调用前检查
     * 
     * @param int $inviter_id 邀请人ID
     * @return bool true 表示成功增加成长值，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或成长值格式错误时抛出异常
     */
    public function addGrowthForUserInvite(int $inviter_id): bool
    {
        // 获取邀请成长值配置
        $growth_invite_amount = sysConfig('growth:growth_invite_amount');

        if ($growth_invite_amount <= 0) {
            return false;
        }

        // 构建成长值增加数据
        $add_data = array(
            'user_id' => $inviter_id,
            'related_id' => 0,
            'change_type' => UserGrowthEnum::TYPE_INVITE,
            'change_mode' => UserGrowthEnum::MODE_INCREASE,
            'change_num' => $growth_invite_amount,
            'change_desc' => '邀请获取成长值',
        );

        // 调用统一的成长值修改方法
        $this->modifyUserGrowth($add_data);

        return true;
    }







    /**
     * 用户商品评论增加成长值
     * 
     * 根据系统配置计算用户评论商品时应给予的成长值奖励
     * 
     * 注意：
     * - 此方法不检查配置是否开启（growth_review_enabled），调用方应在调用前检查
     * 
     * @param int $user_id 用户ID
     * @return bool true 表示成功增加成长值，false 表示配置为0未处理
     * @throws CommonException 当用户不存在或成长值格式错误时抛出异常
     */
    public function addGrowthForUserGoodsComment(int $user_id): bool
    {
        // 获取评论成长值配置
        $growth_review_amount = sysConfig('growth:growth_review_amount');

        if ($growth_review_amount <= 0) {
            return false;
        }

        // 构建成长值增加数据
        $add_data = array(
            'user_id' => $user_id,
            'related_id' => 0,
            'change_type' => UserGrowthEnum::TYPE_GOODS_COMMENT,
            'change_mode' => UserGrowthEnum::MODE_INCREASE,
            'change_num' => $growth_review_amount,
            'change_desc' => '评论获取成长值',
        );

        // 调用统一的成长值修改方法
        $this->modifyUserGrowth($add_data);

        return true;
    }
}
