<?php

namespace app\adminapi\service\distributor;

use app\deshang\base\service\BaseAdminService;


use app\deshang\exceptions\CommonException;

use app\common\dao\distributor\DistributorOrderDao;

use app\common\enum\distributor\DistributorOrderEnum;
use app\deshang\utils\SearchHelper;

class DistributorOrderService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getDistributorOrderPages($data)
    {
        $condition = [];

        if ($data['order_id'] > 0) {
            $condition[] = ['order_id', '=', $data['order_id']];
        }

        if ($data['goods_id'] > 0) {
            $condition[] = ['goods_id', '=', $data['goods_id']];
        }

        if ($data['distributor_user_id'] > 0) {
            $condition[] = ['distributor_user_id', '=', $data['distributor_user_id']];
        }

        if (array_key_exists($data['commission_status'], DistributorOrderEnum::getCommissionStatusDict())) {
            $condition[] = ['commission_status', '=', $data['commission_status']];
        }

        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['distributor_user_id', 'in', $userIds];
        }
        
        // 商品名搜索
        if (isset($data['goods_name']) && $data['goods_name'] != '') {
            $goodsIds = SearchHelper::getGoodsIdsByGoodsName($data['goods_name']);
            $condition[] = ['goods_id', 'in', $goodsIds];
        }
        
        // 店铺名搜索
        if (isset($data['store_name']) && $data['store_name'] != '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
        }
        
        // 佣金类型搜索
        if (isset($data['commission_type']) && $data['commission_type'] !== '') {
            $condition[] = ['commission_type', '=', $data['commission_type']];
        }
        
        // 佣金金额区间搜索
        if (isset($data['commission_amount_min']) && $data['commission_amount_min'] !== '') {
            $condition[] = ['commission_amount', '>=', $data['commission_amount_min']];
        }
        if (isset($data['commission_amount_max']) && $data['commission_amount_max'] !== '') {
            $condition[] = ['commission_amount', '<=', $data['commission_amount_max']];
        }
        
        // 支付金额区间搜索
        if (isset($data['pay_price_min']) && $data['pay_price_min'] !== '') {
            $condition[] = ['pay_price', '>=', $data['pay_price_min']];
        }
        if (isset($data['pay_price_max']) && $data['pay_price_max'] !== '') {
            $condition[] = ['pay_price', '<=', $data['pay_price_max']];
        }

        return (new DistributorOrderDao())->getDistributorOrderPages($condition);
    }

    public function getDistributorOrderList($data)
    {
        $condition = [];

        if ($data['order_id'] > 0) {
            $condition[] = ['order_id', '=', $data['order_id']];
        }

        return (new DistributorOrderDao())->getDistributorOrderList($condition);
    }
}
