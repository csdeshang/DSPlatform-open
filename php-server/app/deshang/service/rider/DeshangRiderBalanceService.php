<?php


namespace app\deshang\service\rider;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;
use app\common\enum\rider\RiderBalanceEnum;

use app\common\dao\rider\RiderBalanceLogDao;
use app\common\dao\rider\RiderDao;


// 骑手余额记录服务
class DeshangRiderBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }



    // 修改用户金额 调用建议通过事务处理
    public function modifyRiderBalance($data)
    {

        $change_mode = $data['change_mode'];
        $change_amount = $data['change_amount'];
        $rider_id = $data['rider_id'];
        $change_type = $data['change_type'];



        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, RiderBalanceEnum::getChangeModeDict())) {
            throw new CommonException('RiderBalanceEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, RiderBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('RiderBalanceEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_amount)) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($change_amount <= 0) {
            throw new CommonException('金额必须为正数');
        }

        //获取骑手信息
        $rider_info = (new RiderDao())->getRiderInfoById($rider_id, 'id,balance,balance_in,balance_out');

        if (empty($rider_info)) {
            throw new CommonException('骑手不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($change_mode == 2) {
            if ($rider_info['balance'] < $change_amount) {
                throw new CommonException('骑手余额不足');
            }
        }

        $after_balance = $change_mode == 1 ? $rider_info['balance'] + $change_amount : $rider_info['balance'] - $change_amount;



        $balance_data = array(
            'rider_id' => $rider_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 充值 提现 退款 系统
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_amount' => $change_amount, // 变动金额
            'before_balance' => $rider_info['balance'], // 变动前金额
            'after_balance' => $after_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );
        $balance_log_id = (new RiderBalanceLogDao())->createRiderBalanceLog($balance_data);


        //修改用户余额
        $rider_updata = array(
            'balance' => $after_balance
        );
        switch ($change_mode) {
            case 1:
                //收入总额
                $rider_updata['balance_in'] = $rider_info['balance_in'] + $change_amount;
                break;
            case 2:
                //支出总额
                $rider_updata['balance_out'] = $rider_info['balance_out'] + $change_amount;
                break;
        }

        $result = (new RiderDao())->updateRider(['id' => $rider_id], $rider_updata);

        return $result;
    }
}
