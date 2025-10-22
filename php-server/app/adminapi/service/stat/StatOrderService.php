<?php

namespace app\adminapi\service\stat;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\order\TblOrderDao;

use app\common\enum\order\TblOrderEnum;



class StatOrderService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblOrderDao();
    }

    public function getStatOrderOverview(array $data)
    {

        $platform = $data['platform'] ?? '';


        $result = [];

        // 总新增订单数
        $result['new_order']['total'] = $this->dao->getOrderCount([['platform', '=', $platform]]);
        // 今日新增
        $result['new_order']['today'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['payment_time', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_order']['yesterday'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['payment_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_order']['week'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['payment_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_order']['month'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['payment_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);


        // 订单总金额
        $result['order_amount']['total'] = $this->dao->getOrderColumnSum([['platform', '=', $platform], ['add_time', '>', 0]], 'pay_amount');
        // 今日订单总金额
        $result['order_amount']['today'] = $this->dao->getOrderColumnSum([['platform', '=', $platform], ['add_time', '>=', strtotime(date('Y-m-d 00:00:00'))]], 'pay_amount');
        // 昨日订单总金额
        $result['order_amount']['yesterday'] = $this->dao->getOrderColumnSum([['platform', '=', $platform], ['add_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))], ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]], 'pay_amount');
        // 本周订单总金额
        $result['order_amount']['week'] = $this->dao->getOrderColumnSum([['platform', '=', $platform], ['add_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))], ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]], 'pay_amount');
        // 本月订单总金额
        $result['order_amount']['month'] = $this->dao->getOrderColumnSum([['platform', '=', $platform], ['add_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))], ['payment_time', '<', strtotime(date('Y-m-d 00:00:00'))]], 'pay_amount');



        // 订单状态
        // 待支付
        $result['order_status']['pending'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['order_status', '=', TblOrderEnum::ORDER_STATUS_PENDING]
        ]);
        // 已付款
        $result['order_status']['paid'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            ['order_status', '=', TblOrderEnum::ORDER_STATUS_PAID]
        ]);
        // 已发货 已确认
        $result['order_status']['accepted'] = $this->dao->getOrderCount([
            ['platform', '=', $platform],
            [
                ['order_status', '=', TblOrderEnum::ORDER_STATUS_ACCEPTED],
                ['order_status', '=', TblOrderEnum::ORDER_STATUS_CONFIRMED]
            ]
        ]);






        return $result;
    }
}
