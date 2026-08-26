<?php

namespace app\adminapi\service\order;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderLogDao;

use app\common\enum\trade\TradePayEnum;
use app\common\model\trade\TradePayLogModel;

use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;
use app\deshang\utils\ExcelExporter;
use app\common\enum\order\TblOrderEnum;


class TblOrderService extends BaseAdminService
{
    /** 单次导出最大条数 */
    public const EXPORT_MAX_ROWS = 5000;


    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblOrderDao();
    }

    /**
     * 构建订单列表/导出共用查询条件
     */
    public function buildOrderCondition(array $data): array
    {
        $condition = [];
        if (isset($data['platform']) && $data['platform'] != '') {
            $condition['platform'] = $data['platform'];
        }
        if (isset($data['order_sn']) && $data['order_sn'] != '') {
            $condition['order_sn'] = $data['order_sn'];
        }
        if (isset($data['order_id']) && $data['order_id'] != '') {
            $condition['id'] = (int)$data['order_id'];
        }
        if (isset($data['order_status']) && $data['order_status'] != '') {
            $condition['order_status'] = $data['order_status'];
        }
        if (isset($data['store_id']) && $data['store_id'] != '') {
            $condition['store_id'] = $data['store_id'];
        }
        if (isset($data['user_id']) && $data['user_id'] != '') {
            $condition['user_id'] = $data['user_id'];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 商户名搜索
        if (isset($data['merchant_name']) && $data['merchant_name'] != '') {
            $merchantIds = SearchHelper::getMerchantIdsByMerchantName($data['merchant_name']);
            $condition[] = ['merchant_id', 'in', $merchantIds];
        }
        
        // 店铺名搜索
        if (isset($data['store_name']) && $data['store_name'] != '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
        }
        
        // 商品名搜索（通过订单商品表关联）
        if (isset($data['goods_name']) && $data['goods_name'] != '') {
            $goodsIds = SearchHelper::getGoodsIdsByGoodsName($data['goods_name']);
            if (!empty($goodsIds)) {
                // 通过订单商品表获取包含该商品的订单ID列表
                $orderGoodsDao = new TblOrderGoodsDao();
                $orderIds = $orderGoodsDao->getOrderGoodsColumn([
                    ['goods_id', 'in', $goodsIds]
                ], 'order_id');
                $condition[] = ['id', 'in', $orderIds];
            }else{
                $condition[] = ['id', '=', -1];
            }
        }
        
        // 支付单号搜索
        if (isset($data['out_trade_no']) && $data['out_trade_no'] != '') {
            $condition[] = ['out_trade_no', 'like', '%' . $data['out_trade_no'] . '%'];
        }
        
        // 交易号搜索
        if (isset($data['trade_no']) && $data['trade_no'] != '') {
            $condition[] = ['trade_no', 'like', '%' . $data['trade_no'] . '%'];
        }
        
        // 交付方式搜索
        if (isset($data['delivery_method']) && $data['delivery_method'] !== '') {
            $condition[] = ['delivery_method', '=', $data['delivery_method']];
        }
        
        // 是否评价搜索
        if (isset($data['is_evaluate']) && $data['is_evaluate'] !== '') {
            $condition[] = ['is_evaluate', '=', $data['is_evaluate']];
        }
        
        // 退款状态搜索
        if (isset($data['refund_status']) && $data['refund_status'] !== '') {
            $condition[] = ['refund_status', '=', $data['refund_status']];
        }
        
        // 是否退款中搜索（根据refunding_count判断）
        if (isset($data['is_refunding']) && $data['is_refunding'] !== '') {
            if ($data['is_refunding'] == '1') {
                // 退款中：refunding_count > 0
                $condition[] = ['refunding_count', '>', 0];
            } else {
                // 非退款中：refunding_count = 0
                $condition[] = ['refunding_count', '=', 0];
            }
        }
        
        // 支付金额区间搜索
        if (isset($data['pay_amount_min']) && $data['pay_amount_min'] !== '') {
            $condition[] = ['pay_amount', '>=', $data['pay_amount_min']];
        }
        if (isset($data['pay_amount_max']) && $data['pay_amount_max'] !== '') {
            $condition[] = ['pay_amount', '<=', $data['pay_amount_max']];
        }

        return $condition;
    }

    /**
     * 获取订单分页
     * @param array $params 查询参数
     * @return array
     */
    public function getTblOrderPages(array $data): array
    {
        $condition = $this->buildOrderCondition($data);

        $result = $this->dao->getWithRelOrderPages($condition);
        return $result;
    }

    /**
     * 导出订单为 Excel（筛选条件与列表一致）
     */
    public function exportTblOrders(array $data): void
    {
        $condition = $this->buildOrderCondition($data);
        $count = $this->dao->getOrderCount($condition);

        if ($count <= 0) {
            throw new CommonException('暂无数据可导出');
        }
        if ($count > self::EXPORT_MAX_ROWS) {
            throw new CommonException('导出数量超过' . self::EXPORT_MAX_ROWS . '条，请缩小筛选范围后再导出');
        }

        $list = $this->dao->getWithRelOrderList($condition);

        $headers = [
            '订单ID',
            '订单号',
            '平台',
            '店铺',
            '订单状态',
            '买家账号',
            '买家昵称',
            '收货人',
            '收货手机',
            '收货地址',
            '商品明细',
            '商品金额',
            '运费',
            '优惠金额',
            '订单金额',
            '实付金额',
            '支付方式',
            '支付单号',
            '交易号',
            '交付方式',
            '开票状态',
            '退款状态',
            '退款金额',
            '用户备注',
            '店铺备注',
            '下单时间',
            '支付时间',
            '发货时间',
            '完成时间',
        ];

        $rows = [];
        foreach ($list as $order) {
            $goodsParts = [];
            foreach ($order['orderGoodsList'] ?? [] as $goods) {
                $name = $goods['goods_name'] ?? '';
                $sku = !empty($goods['sku_name']) ? ('[' . $goods['sku_name'] . ']') : '';
                $num = $goods['goods_num'] ?? 0;
                $price = $goods['pay_price'] ?? '';
                $goodsParts[] = "{$name}{$sku} x{$num} ¥{$price}";
            }

            $address = $order['orderAddress'] ?? [];
            $refundStatus = isset($order['refund_status'])
                ? TblOrderEnum::getOrderRefundStatusDesc($order['refund_status'])
                : '';

            $rows[] = [
                $order['id'] ?? '',
                $order['order_sn'] ?? '',
                $order['platform'] ?? '',
                $order['store']['store_name'] ?? '',
                $order['order_status_desc'] ?? '',
                $order['user']['username'] ?? '',
                $order['user']['nickname'] ?? '',
                $address['reciver_name'] ?? '',
                $address['reciver_mobile'] ?? '',
                $address['reciver_address'] ?? '',
                implode('；', $goodsParts),
                $order['goods_amount'] ?? '',
                $order['shipping_amount'] ?? '',
                $order['discount_amount'] ?? '',
                $order['order_amount'] ?? '',
                $order['pay_amount'] ?? '',
                $order['pay_channel'] ?? '',
                $order['out_trade_no'] ?? '',
                $order['trade_no'] ?? '',
                $order['delivery_method_desc'] ?? '',
                $order['invoice_status_desc'] ?? '',
                $refundStatus,
                $order['refund_amount'] ?? '',
                $order['user_remark'] ?? '',
                $order['store_remark'] ?? '',
                $order['add_time'] ?? '',
                $order['payment_time'] ?? '',
                $order['delivery_time'] ?? '',
                $order['finnshed_time'] ?? '',
            ];
        }

        $platform = !empty($data['platform']) ? $data['platform'] : 'all';
        $filename = '订单导出_' . $platform . '_' . date('Ymd_His') . '.xlsx';
        ExcelExporter::download($headers, $rows, $filename);
    }


    public function getTblOrderInfo($id): array
    {
        return $this->dao->getWithRelOrderInfo(['id' => $id]);
    }

    public function getTblOrderGoodsList($data): array
    {
        $condition = [];
        if ($data['order_id'] > 0) {
            $condition['order_id'] = $data['order_id'];
        }
        if ($data['goods_id'] > 0) {
            $condition['goods_id'] = $data['goods_id'];
        }
        $result = (new TblOrderGoodsDao())->getOrderGoodsList($condition);
        return $result;
    }
    public function getTblOrderGoodsPages($data): array
    {
        $condition = [];
        if ($data['order_id'] > 0) {
            $condition['order_id'] = $data['order_id'];
        }
        if ($data['goods_id'] > 0) {
            $condition['goods_id'] = $data['goods_id'];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        $result = (new TblOrderGoodsDao())->getOrderGoodsPages($condition);
        return $result;
    }

    public function getTblOrderLogList($data): array
    {
        $result = (new TblOrderLogDao())->getOrderLogList(['order_id' => $data['order_id']]);
        return $result;
    }
    // 获取订单支付记录列表 
    // 订单id 和 合并支付订单id 二选一
    public function getTblOrderPayLogList($data): array
    {


        $condition = [];
        if ($data['order_id']) {
            $condition = [
                'source_id' => $data['order_id'],
                'source_type' => TradePayEnum::SOURCE_TYPE_ORDER,
            ];
        }

        if ($data['order_merge_id']) {
            $condition = [
                'source_id' => $data['order_merge_id'],
                'source_type' => TradePayEnum::SOURCE_TYPE_ORDER_MERGE,
            ];
        }



        $result = (new TradePayLogModel())->where($condition)->select()->toArray();
        return $result;
    }
}
