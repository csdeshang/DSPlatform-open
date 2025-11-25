<?php

namespace app\adminapi\service\order;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\order\TblOrderRefundDao;
use app\common\dao\order\TblOrderRefundLogDao;

use app\deshang\service\order\DeshangTblOrderRefundService;

use app\deshang\exceptions\CommonException;

use think\facade\Db;

use app\deshang\utils\SearchHelper;


class TblOrderRefundService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * 获取订单退款分页
     * @param array $params 查询参数
     * @return array
     */
    public function getTblOrderRefundPages(array $data): array
    {
        $condition = [];
        if (isset($data['platform']) && $data['platform'] != '') {
            $condition['platform'] = $data['platform'];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
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
                $orderGoodsDao = new \app\common\dao\order\TblOrderGoodsDao();
                $orderIds = $orderGoodsDao->getOrderGoodsColumn([
                    ['goods_id', 'in', $goodsIds]
                ], 'order_id');
                $condition[] = ['order_id', 'in', $orderIds];
            }else{
                $condition[] = ['order_id', '=', -1];
            }
        }
        
        // 退款单号搜索
        if (isset($data['out_refund_no']) && $data['out_refund_no'] != '') {
            $condition[] = ['out_refund_no', 'like', '%' . $data['out_refund_no'] . '%'];
        }
        
        // 退款类型搜索
        if (isset($data['refund_type']) && $data['refund_type'] !== '') {
            $condition[] = ['refund_type', '=', $data['refund_type']];
        }
        
        // 退款状态搜索
        if (isset($data['refund_status']) && $data['refund_status'] !== '') {
            $condition[] = ['refund_status', '=', $data['refund_status']];
        }
        
        // 退款方式搜索
        if (isset($data['refund_method']) && $data['refund_method'] !== '') {
            $condition[] = ['refund_method', '=', $data['refund_method']];
        }
        
        // 退款金额区间搜索
        if (isset($data['refund_amount_min']) && $data['refund_amount_min'] !== '') {
            $condition[] = ['refund_amount', '>=', $data['refund_amount_min']];
        }
        if (isset($data['refund_amount_max']) && $data['refund_amount_max'] !== '') {
            $condition[] = ['refund_amount', '<=', $data['refund_amount_max']];
        }
        
        $result = (new TblOrderRefundDao())->getWithRelOrderRefundPages($condition);
        return $result;
    }


    /**
     * 获取订单退款列表
     * @param array $data 查询参数
     * @return array
     */
    public function getTblOrderRefundList(array $data): array
    {
        $condition = [];
        $condition['order_id'] = $data['order_id'];
        $result = (new TblOrderRefundDao())->getOrderRefundList($condition);
        return $result;
    }


    /**
     * 获取订单退款详情
     * @param int $refund_id 退款ID
     * @return array
     */
    public function getTblOrderRefundInfo(int $refund_id): array
    {
        $condition = [];
        $condition['id'] = $refund_id;
        $result = (new TblOrderRefundDao())->getWithRelOrderRefundInfo($condition);
        return $result;
    }

    /**
     * 重新发起退款 当店铺允许退款但实际退款失败时，重新处理退款。适用于店铺余额不足、原路退回失败等情况，退款状态为处理中或失败时可重试
     * @param int $refund_id 退款ID
     * @return array
     */
    public function retryTblOrderRefund(int $refund_id): array
    {
        // 事务处理
        Db::startTrans();
        try {
            $refund_info = (new TblOrderRefundDao())->getOrderRefundInfoById($refund_id);
            if (empty($refund_info)) {
                throw new CommonException('退款信息不存在');
            }
            // 处理退款
            $result = (new DeshangTblOrderRefundService)->processRefund($refund_info, 'admin', 0);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }

        return [];
    }

    /**
     * 获取订单退款日志列表
     * @param int $refund_id 退款ID
     * @return array
     */
    public function getTblOrderRefundLogList(int $refund_id): array
    {
        $condition = [];
        $condition['refund_id'] = $refund_id;

        $result = (new TblOrderRefundLogDao())->getOrderRefundLogList($condition);
        return $result;
    }








}