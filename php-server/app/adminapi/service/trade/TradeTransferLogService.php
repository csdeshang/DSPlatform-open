<?php


namespace app\adminapi\service\trade;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\trade\TradeTransferLogDao;
use app\deshang\utils\SearchHelper;

class TradeTransferLogService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取交易转账记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getTradeTransferLogPages(array $data): array
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
        
        // 商户转账单号搜索
        if (isset($data['out_transfer_no']) && $data['out_transfer_no'] !== '') {
            $condition[] = ['out_transfer_no', 'like', '%' . $data['out_transfer_no'] . '%'];
        }
        
        // 转账单号搜索
        if (isset($data['transfer_no']) && $data['transfer_no'] !== '') {
            $condition[] = ['transfer_no', 'like', '%' . $data['transfer_no'] . '%'];
        }
        
        // 转账金额区间搜索
        if (isset($data['transfer_amount_min']) && $data['transfer_amount_min'] !== '') {
            $condition[] = ['transfer_amount', '>=', $data['transfer_amount_min']];
        }
        if (isset($data['transfer_amount_max']) && $data['transfer_amount_max'] !== '') {
            $condition[] = ['transfer_amount', '<=', $data['transfer_amount_max']];
        }
        
        // 转账状态搜索
        if (isset($data['transfer_status']) && $data['transfer_status'] !== '') {
            $condition[] = ['transfer_status', '=', $data['transfer_status']];
        }

        $result = (new TradeTransferLogDao())->getTradeTransferLogPages($condition);
        return $result;
    }


}