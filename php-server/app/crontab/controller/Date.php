<?php

namespace app\crontab\controller;

use app\deshang\base\controller\BaseApiController;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\model\distributor\DistributorOrderModel;
use app\common\enum\order\TblOrderEnum;
use app\common\enum\distributor\DistributorOrderEnum;
use app\common\enum\distributor\DistributorBalanceEnum;
use app\deshang\service\distributor\DeshangDistributorBalanceService;

class Date extends BaseApiController
{


    public function index()
    {
        $this->updateDistributorOrder();
    }



    // 更新店铺数据



    // 更新分销订单，处理 分销订单与分销员的金额结算
    public function updateDistributorOrder()
    {

        // 根据 allow_refund_time 获取不允许退款以及没有退款正在退款申请的订单
        $distributor_order_list = (new DistributorOrderModel)
            ->hasWhere('order', [
                ['allow_refund_time', '<', time()],
                ['refunding_count', '=', 0],
                ['order_status', '=', TblOrderEnum::ORDER_STATUS_COMPLETED]
            ])
            ->where('commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_WAIT)
            ->with(
                [
                    'order' => function ($query) {
                        $query->field('id,allow_refund_time,refunding_count,order_status');
                    }
                ]
            )
            ->limit(1000)
            ->select()
            ->toArray();




        // 事务处理
        Db::startTrans();
        try {
            foreach ($distributor_order_list as $distributor_order) {
                // 扣款
                $distributor_balance_data = [
                    'change_mode' => DistributorBalanceEnum::MODE_INCREASE,
                    'change_type' => DistributorBalanceEnum::TYPE_ORDER_COMMISSION,
                    'change_amount' => $distributor_order['commission_amount'],
                    'distributor_user_id' => $distributor_order['distributor_user_id'],
                    'related_id' => $distributor_order['id'],
                    'change_desc' => '分销佣金结算，分销订单ID:' . $distributor_order['id'] . ',商品订单ID：' . $distributor_order['order_id'] . ',佣金：' . $distributor_order['commission_amount'],
                ];
                (new DeshangDistributorBalanceService())->modifyDistributorBalance($distributor_balance_data);

                // 更新分销订单状态为已结算
                (new DistributorOrderModel)->where('id', $distributor_order['id'])
                    ->update([
                        'commission_status' => DistributorOrderEnum::COMMISSION_STATUS_SETTLED,
                        'update_at' => time()
                    ]);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new CommonException('获取到的异常' . $e->getMessage());
        }
    }
}
