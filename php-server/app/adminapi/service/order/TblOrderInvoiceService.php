<?php

namespace app\adminapi\service\order;

use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderInvoiceDao;
use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\NotFoundException;
use app\deshang\utils\SearchHelper;

class TblOrderInvoiceService extends BaseAdminService
{
    /**
     * 开票申请分页（全平台/全店铺只读）
     *
     * @param array $data 筛选：platform、invoice_status、order_id、order_sn、username、user_id、store_id、store_name、merchant_id、merchant_name、goods_name
     */
    public function getTblOrderInvoicePages(array $data): array
    {
        $condition = [];

        if (isset($data['platform']) && $data['platform'] !== '') {
            $condition['platform'] = $data['platform'];
        }
        if (isset($data['merchant_id']) && $data['merchant_id'] !== '' && (int) $data['merchant_id'] > 0) {
            $condition[] = ['merchant_id', '=', (int) $data['merchant_id']];
        }
        if (isset($data['merchant_name']) && $data['merchant_name'] !== '') {
            $merchantIds = SearchHelper::getMerchantIdsByMerchantName((string) $data['merchant_name']);
            if (!empty($merchantIds)) {
                $condition[] = ['merchant_id', 'in', $merchantIds];
            } else {
                $condition[] = ['id', '=', 0];
            }
        }
        if (isset($data['store_id']) && $data['store_id'] !== '' && (int) $data['store_id'] > 0) {
            $condition[] = ['store_id', '=', (int) $data['store_id']];
        }
        if (isset($data['store_name']) && $data['store_name'] !== '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName((string) $data['store_name']);
            if (!empty($storeIds)) {
                $condition[] = ['store_id', 'in', $storeIds];
            } else {
                $condition[] = ['id', '=', 0];
            }
        }
        if (isset($data['user_id']) && $data['user_id'] !== '' && (int) $data['user_id'] > 0) {
            $condition[] = ['user_id', '=', (int) $data['user_id']];
        }
        if (isset($data['username']) && $data['username'] !== '') {
            $userIds = SearchHelper::getUserIdsByUsername((string) $data['username']);
            if (!empty($userIds)) {
                $condition[] = ['user_id', 'in', $userIds];
            } else {
                $condition[] = ['id', '=', 0];
            }
        }
        if (isset($data['goods_name']) && $data['goods_name'] !== '') {
            $goodsIds = SearchHelper::getGoodsIdsByGoodsName((string) $data['goods_name']);
            if (!empty($goodsIds)) {
                $orderIds = (new TblOrderGoodsDao())->getOrderGoodsColumn([
                    ['goods_id', 'in', $goodsIds],
                ], 'order_id');
                if (!empty($orderIds)) {
                    $condition[] = ['order_id', 'in', $orderIds];
                } else {
                    $condition[] = ['id', '=', 0];
                }
            } else {
                $condition[] = ['id', '=', 0];
            }
        }
        if (isset($data['invoice_status']) && $data['invoice_status'] !== '' && $data['invoice_status'] !== null) {
            $condition[] = ['invoice_status', '=', (int) $data['invoice_status']];
        }
        if (isset($data['order_id']) && $data['order_id'] !== '' && (int) $data['order_id'] > 0) {
            $condition[] = ['order_id', '=', (int) $data['order_id']];
        }
        if (isset($data['order_sn']) && $data['order_sn'] !== '') {
            $condition[] = ['order_sn', 'like', '%' . $data['order_sn'] . '%'];
        }

        return (new TblOrderInvoiceDao())->getWithRelOrderInvoicePages($condition);
    }

    /**
     * 开票申请详情（只读）
     */
    public function getTblOrderInvoiceInfo(int $id): array
    {
        $result = (new TblOrderInvoiceDao())->getWithRelOrderInvoiceInfo(['id' => $id]);
        if (empty($result['id'])) {
            throw new NotFoundException('开票申请不存在');
        }

        return $result;
    }
}
