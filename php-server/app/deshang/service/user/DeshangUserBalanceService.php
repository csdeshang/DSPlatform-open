<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserBalanceEnum;


use app\common\dao\user\UserBalanceLogDao;
use app\common\dao\user\UserDao;


class DeshangUserBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改用户金额 调用建议通过事务处理
    public function modifyUserBalance($data)
    {

        $change_mode = $data['change_mode'];
        $change_amount = $data['change_amount'];
        $user_id = $data['user_id'];
        $change_type = $data['change_type'];



        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, UserBalanceEnum::getChangeModeDict())) {
            throw new CommonException('UserBalanceEnum  变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, UserBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('UserBalanceEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_amount)) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($change_amount <= 0) {
            throw new CommonException('金额必须为正数');
        }

        //获取用户信息

        $user_info = (new UserDao())->getUserInfoById($user_id, 'id,balance,balance_in,balance_out');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($change_mode == UserBalanceEnum::MODE_DECREASE) {
            if ($user_info['balance'] < $change_amount) {
                throw new CommonException('用户余额不足');
            }
        }

        $after_balance = $change_mode == 1 ? $user_info['balance'] + $change_amount : $user_info['balance'] - $change_amount;




        $balance_data = array(
            'user_id' => $user_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 充值 提现 退款 系统
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_amount' => $change_amount, // 变动金额
            'before_balance' => $user_info['balance'], // 变动前金额
            'after_balance' => $after_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        $balance_log_id = (new UserBalanceLogDao())->createBalanceLog($balance_data);

        //修改用户余额
        $user_updata = array(
            'balance' => $after_balance
        );
        switch ($change_mode) {
            case UserBalanceEnum::MODE_INCREASE:
                //收入总额
                $user_updata['balance_in'] = $user_info['balance_in'] + $change_amount;
                break;
            case UserBalanceEnum::MODE_DECREASE:
                //支出总额
                $user_updata['balance_out'] = $user_info['balance_out'] + $change_amount;
                break;
        }

        $result = (new UserDao())->updateUser(['id' => $user_id], $user_updata);




        return $result;
    }
}
