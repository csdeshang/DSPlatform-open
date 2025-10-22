<?php


namespace app\deshang\service\merchant;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\merchant\MerchantBalanceEnum;


use app\common\dao\merchant\MerchantBalanceLogDao;
use app\common\dao\merchant\MerchantDao;


class DeshangMerchantBalanceService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }






    // 修改商户余额 调用建议通过事务处理
    public function modifyMerchantBalance($data)
    {

        $change_mode = $data['change_mode'];
        $change_amount = $data['change_amount'];
        $merchant_id = $data['merchant_id'];
        $change_type = $data['change_type'];
        $store_id = isset($data['store_id']) ? $data['store_id'] : 0;


        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, MerchantBalanceEnum::getChangeModeDict())) {
            throw new CommonException('MerchantBalanceEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, MerchantBalanceEnum::getChangeTypeDict())) {
            throw new CommonException('MerchantBalanceEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_amount)) {
            throw new CommonException('金额格式错误，必须为数字');
        }
        if ($change_amount <= 0) {
            throw new CommonException('金额必须为正数');
        }

        //获取用户信息

        $merchant_info = (new MerchantDao())->getMerchantInfoById($merchant_id, 'id,balance,balance_in,balance_out');
        if (empty($merchant_info)) {
            throw new CommonException('商户不存在');
        }

        // 判断是否有足够金额进行扣除
        if ($change_mode == MerchantBalanceEnum::MODE_DECREASE) {
            if ($merchant_info['balance'] < $change_amount) {
                throw new CommonException('商户余额不足');
            }
        }

        $after_balance = $change_mode == 1 ? $merchant_info['balance'] + $change_amount : $merchant_info['balance'] - $change_amount;



        $balance_data = array(
            'merchant_id' => $merchant_id,
            // 店铺ID(店铺主要用于区分是哪个店铺的余额变动)
            'store_id' => $store_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 订单 退款 系统
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_amount' => $change_amount, // 变动金额
            'before_balance' => $merchant_info['balance'], // 变动前金额
            'after_balance' => $after_balance, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        $balance_log_id = (new MerchantBalanceLogDao())->createBalanceLog($balance_data);

        //修改用户余额
        $merchant_updata = array(
            'balance' => $after_balance
        );
        switch ($change_mode) {
            case MerchantBalanceEnum::MODE_INCREASE:
                //收入总额
                $merchant_updata['balance_in'] = $merchant_info['balance_in'] + $change_amount;
                break;
            case MerchantBalanceEnum::MODE_DECREASE:
                //支出总额
                $merchant_updata['balance_out'] = $merchant_info['balance_out'] + $change_amount;
                break;
        }

        $result = (new MerchantDao())->updateMerchant(['id' => $merchant_id], $merchant_updata);




        return $result;
    }
}
