<?php

namespace app\common\model\pointsGoods;

use app\deshang\base\BaseModel;
use app\common\enum\pointsGoods\PointsGoodsOrderEnum;


/**
 * 积分商品订单日志模型
 */
class PointsGoodsOrderLogModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'points_goods_order_log';

    // 订单状态描述获取器
    public function getOrderStatusDescAttr($value, $data)
    {
        return PointsGoodsOrderEnum::getOrderStatusDesc($data['order_status']);
    }

}
