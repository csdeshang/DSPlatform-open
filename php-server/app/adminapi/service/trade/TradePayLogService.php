<?php


namespace app\adminapi\service\trade;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\trade\TradePayLogDao;
use app\deshang\utils\SearchHelper;

class TradePayLogService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取交易支付记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getTradePayLogPages(array $data): array
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
        
        // 来源类型搜索
        if (isset($data['source_type']) && $data['source_type'] !== '') {
            $condition[] = ['source_type', '=', $data['source_type']];
        }
        
        // 商户订单号搜索
        if (isset($data['out_trade_no']) && $data['out_trade_no'] !== '') {
            $condition[] = ['out_trade_no', 'like', '%' . $data['out_trade_no'] . '%'];
        }
        
        // 支付订单号搜索
        if (isset($data['trade_no']) && $data['trade_no'] !== '') {
            $condition[] = ['trade_no', 'like', '%' . $data['trade_no'] . '%'];
        }
        
        // 支付状态搜索
        if (isset($data['pay_status']) && $data['pay_status'] !== '') {
            $condition[] = ['pay_status', '=', $data['pay_status']];
        }
        
        // 支付金额区间搜索
        if (isset($data['pay_amount_min']) && $data['pay_amount_min'] !== '') {
            $condition[] = ['pay_amount', '>=', $data['pay_amount_min']];
        }
        if (isset($data['pay_amount_max']) && $data['pay_amount_max'] !== '') {
            $condition[] = ['pay_amount', '<=', $data['pay_amount_max']];
        }

        $result = (new TradePayLogDao())->getTradePayLogPages($condition);
        return $result;
    }


}