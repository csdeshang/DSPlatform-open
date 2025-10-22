<?php


namespace app\deshang\service\trade;

use app\deshang\service\BaseDeshangService;

use app\deshang\core\ThirdPartyLoader;

use app\common\enum\trade\TradePaymentConfigEnum;
use app\common\enum\trade\TradeTransferEnum;
use app\common\enum\user\UserWithdrawalEnum;
use app\deshang\exceptions\CommonException;

use app\common\dao\trade\TradeTransferLogDao;

class DeshangTradeTransferService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    // 转账
    public function transfer($transfer_type,$withdrawal_log)
    {

        switch ($transfer_type) {
            case UserWithdrawalEnum::TRANSFER_TYPE_MANUAL:
                // 手动转账  直接返回
                return true;
                break;
            case UserWithdrawalEnum::ACCOUNT_TYPE_WECHAT:
                // 微信自动转账
                throw new CommonException('微信自动转账,暂未开通');
                break;
            case UserWithdrawalEnum::ACCOUNT_TYPE_ALIPAY:

                // 支付宝自动转账
                $trade = ThirdPartyLoader::trade(0, TradePaymentConfigEnum::CHANNEL_ALIPAY, TradePaymentConfigEnum::SCENE_H5, 'transfer');

                // 支付宝支持 支付宝账户及银行卡转账
                if ($withdrawal_log['account_type'] == UserWithdrawalEnum::ACCOUNT_TYPE_ALIPAY || $withdrawal_log['account_type'] == UserWithdrawalEnum::ACCOUNT_TYPE_BANK) {
                    $transfer_data = [
                        'out_transfer_no' => $withdrawal_log['out_transfer_no'],
                        'transfer_amount' => $withdrawal_log['withdrawal_amount'],
                        'account_type' => $withdrawal_log['account_type'],
                        'account_name' => $withdrawal_log['account_name'],
                        'account_number' => $withdrawal_log['account_number'],
                        'account_holder' => $withdrawal_log['account_holder'],
                    ];
                    $result = $trade->transfer($transfer_data);

                    // 结果写入日志
                    $trade_transfer_log_id = $this->writeTradeTransferResult($transfer_type,$withdrawal_log, $result);

                    if ($result['status'] == 'success') {
                        return true;
                    } else {
                        throw new CommonException($result['message']);
                    }
                } else {
                    throw new CommonException('提现账户类型不正确');
                }

                break;
            default:
                throw new CommonException('transfer_type 类型不正确');
        }

    }



    // 转账结果写入日志
    private function writeTradeTransferResult($transfer_type,$withdrawal_log, $result)
    {

        $data = [
            'user_id' => $withdrawal_log['user_id'],
            'source_type' => TradeTransferEnum::SOURCE_TYPE_WITHDRAWAL,
            'source_id' => $withdrawal_log['id'],
            'out_transfer_no' => $withdrawal_log['out_transfer_no'],
            'transfer_no' => $result['transfer_no'],
            'transfer_type' => $transfer_type,
            'transfer_amount' => $withdrawal_log['withdrawal_amount'],
            'transfer_status' => $result['status'] == 'success' ? TradeTransferEnum::TRANSFER_STATUS_SUCCESS : TradeTransferEnum::TRANSFER_STATUS_FAILED,
            'transfer_response' => json_encode($result['response']),
            'account_type' => $withdrawal_log['account_type'],
            'account_name' => $withdrawal_log['account_name'],
            'account_number' => $withdrawal_log['account_number'],
            'account_holder' => $withdrawal_log['account_holder'],
        ];

        return (new TradeTransferLogDao())->createTradeTransferLog($data);
    }
    
    
    


}