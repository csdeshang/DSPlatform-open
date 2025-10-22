<?php


namespace app\deshang\service\distributor;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\distributor\DistributorBalanceEnum;


use app\common\dao\distributor\DistributorBalanceLogDao;
use app\common\dao\user\UserDao;


class DeshangDistributorBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改分销员金额 调用建议通过事务处理
    public function modifyDistributorBalance($data)
    {

        $change_mode = $data['change_mode'];
        $change_amount = $data['change_amount'];
        $distributor_user_id = $data['distributor_user_id'];
        $change_type = $data['change_type'];



        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, DistributorBalanceEnum::getChangeModeDict())) {
            throw new CommonException('DistributorBalanceEnum  变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, DistributorBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('DistributorBalanceEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_amount)) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($change_amount <= 0) {
            throw new CommonException('金额必须为正数');
        }

        //获取用户信息

        $distributor_user_info = (new UserDao())->getUserInfoById($distributor_user_id, 'id,distributor_balance,distributor_balance_in,distributor_balance_out');
        if (empty($distributor_user_info)) {
            throw new CommonException('分销员不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($change_mode == DistributorBalanceEnum::MODE_DECREASE) {
            if ($distributor_user_info['distributor_balance'] < $change_amount) {
                throw new CommonException('分销员余额不足');
            }
        }

        $after_distributor_balance = $change_mode == DistributorBalanceEnum::MODE_INCREASE ? $distributor_user_info['distributor_balance'] + $change_amount : $distributor_user_info['distributor_balance'] - $change_amount;




        $balance_data = array(
            'distributor_user_id' => $distributor_user_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_amount' => $change_amount, // 变动金额
            'before_balance' => $distributor_user_info['distributor_balance'], // 变动前金额
            'after_balance' => $after_distributor_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        $balance_log_id = (new DistributorBalanceLogDao())->createDistributorBalanceLog($balance_data);

        //修改用户余额
        $distributor_user_updata = array(
            'distributor_balance' => $after_distributor_balance
        );
        switch ($change_mode) {
            case DistributorBalanceEnum::MODE_INCREASE:
                //收入总额
                $distributor_user_updata['distributor_balance_in'] = $distributor_user_info['distributor_balance_in'] + $change_amount;
                break;
            case DistributorBalanceEnum::MODE_DECREASE:
                //支出总额
                $distributor_user_updata['distributor_balance_out'] = $distributor_user_info['distributor_balance_out'] + $change_amount;
                break;
        }

        $result = (new UserDao())->updateUser(['id' => $distributor_user_id], $distributor_user_updata);




        return $result;
    }
}
