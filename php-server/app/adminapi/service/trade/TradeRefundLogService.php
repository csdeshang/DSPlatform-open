<?php


namespace app\adminapi\service\trade;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\trade\TradeRefundLogDao;
use app\deshang\utils\SearchHelper;

class TradeRefundLogService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取交易退款记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getTradeRefundLogPages(array $data): array
    {
        $condition = [];
        if(isset($data['user_id']) && $data['user_id'] != ''){
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 商户订单号搜索
        if (isset($data['out_trade_no']) && $data['out_trade_no'] !== '') {
            $condition[] = ['out_trade_no', 'like', '%' . $data['out_trade_no'] . '%'];
        }
        
        // 支付订单号搜索
        if (isset($data['trade_no']) && $data['trade_no'] !== '') {
            $condition[] = ['trade_no', 'like', '%' . $data['trade_no'] . '%'];
        }
        
        // 退款单号搜索
        if (isset($data['out_refund_no']) && $data['out_refund_no'] !== '') {
            $condition[] = ['out_refund_no', 'like', '%' . $data['out_refund_no'] . '%'];
        }
        
        // 退款状态搜索
        if (isset($data['refund_status']) && $data['refund_status'] !== '') {
            $condition[] = ['refund_status', '=', $data['refund_status']];
        }
        
        // 支付金额区间搜索
        if (isset($data['pay_amount_min']) && $data['pay_amount_min'] !== '') {
            $condition[] = ['pay_amount', '>=', $data['pay_amount_min']];
        }
        if (isset($data['pay_amount_max']) && $data['pay_amount_max'] !== '') {
            $condition[] = ['pay_amount', '<=', $data['pay_amount_max']];
        }

        $result = (new TradeRefundLogDao())->getTradeRefundLogPages($condition);
        return $result;
    }


}