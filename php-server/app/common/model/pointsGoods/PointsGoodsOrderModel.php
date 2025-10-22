<?php

namespace app\common\model\pointsGoods;

use app\deshang\base\BaseModel;
use app\common\enum\pointsGoods\PointsGoodsOrderEnum;
use app\common\model\user\UserModel;

class PointsGoodsOrderModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'points_goods_order';

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 订单状态获取器
    public function getOrderStatusDescAttr($value, $data)
    {
        return PointsGoodsOrderEnum::getOrderStatusDesc($data['order_status']);
    }

    // 配送方式获取器
    public function getDeliveryMethodDescAttr($value, $data)
    {
        return PointsGoodsOrderEnum::getDeliveryMethodDesc($data['delivery_method']);
    }


    // 发货时间获取器
    public function getExpressTimeAttr($value, $data)
    {
        return $this->formatTime($data['express_time']);
    }


    // 发货时间获取器
    public function getReceiveTimeAttr($value, $data)
    {
        return $this->formatTime($data['receive_time']);
    }
}
