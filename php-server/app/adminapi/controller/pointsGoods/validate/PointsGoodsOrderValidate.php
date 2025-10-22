<?php

namespace app\adminapi\controller\pointsGoods\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\pointsGoods\PointsGoodsOrderEnum;


class PointsGoodsOrderValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // 订单ID，必填，必须是大于0的整数
        'order_sn' => 'max:32', // 订单号，最大长度32
        'user_id' => 'integer|gt:0', // 用户ID，必须是大于0的整数
        'goods_id' => 'integer|gt:0', // 商品ID，必须是大于0的整数
        'goods_name' => 'max:128', // 商品名称，最大长度128
        'goods_image' => 'max:255', // 商品图片，最大长度255
        'points_price' => 'integer|egt:0', // 积分价格，必须是大于等于0的整数
        'exchange_num' => 'integer|gt:0', // 兑换数量，必须是大于0的整数
        'total_points' => 'integer|egt:0', // 总积分，必须是大于等于0的整数
        'order_status' => 'checkOrderStatus', // 订单状态验证
        'delivery_method' => 'require|checkDeliveryMethod', // 配送方式，必须是express或delivery
        'receiver_name' => 'max:32', // 收货人姓名，最大长度32
        'receiver_mobile' => 'mobile', // 收货人手机，必须是手机号格式
        'receiver_address' => 'max:255', // 收货详细地址，最大长度255
        'express_company' => 'max:32', // 快递公司，最大长度32
        'express_no' => 'max:32', // 快递单号，最大长度32
        'remark' => 'max:255', // 备注，最大长度255
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '订单ID不能为空',
        'id.integer' => '订单ID必须是整数',
        'id.gt' => '订单ID必须大于0',
        'order_sn.max' => '订单号不能超过32个字符',
        'user_id.integer' => '用户ID必须是整数',
        'user_id.gt' => '用户ID必须大于0',
        'goods_id.integer' => '商品ID必须是整数',
        'goods_id.gt' => '商品ID必须大于0',
        'goods_name.max' => '商品名称不能超过128个字符',
        'goods_image.max' => '商品图片不能超过255个字符',
        'points_price.integer' => '积分价格必须是整数',
        'points_price.egt' => '积分价格必须大于等于0',
        'exchange_num.integer' => '兑换数量必须是整数',
        'exchange_num.gt' => '兑换数量必须大于0',
        'total_points.integer' => '总积分必须是整数',
        'total_points.egt' => '总积分必须大于等于0',
        'order_status.checkOrderStatus' => '订单状态无效',
        'delivery_method.require' => '配送方式不能为空',
        'delivery_method.checkDeliveryMethod' => '配送方式必须是express或delivery',
        'receiver_name.max' => '收货人姓名不能超过32个字符',
        'receiver_mobile.mobile' => '收货人手机格式不正确',
        'receiver_address.max' => '收货详细地址不能超过255个字符',
        'express_company.max' => '快递公司不能超过32个字符',
        'express_no.max' => '快递单号不能超过32个字符',
        'remark.max' => '备注不能超过255个字符',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['order_sn', 'user_id', 'goods_id', 'goods_name', 'order_status', 'receiver_name', 'receiver_mobile'], // 分页查询场景
        'info' => ['id'], // 获取详情场景
        'update' => ['id', 'delivery_method', 'receiver_name', 'receiver_mobile', 'receiver_address', 'express_company', 'express_no', 'remark'], // 更新场景
        'cancel' => ['id'], // 取消订单场景
        'ship' => ['id', 'delivery_method', 'express_company', 'express_no', 'remark'], // 发货场景
        'confirm' => ['id'], // 确认收货场景
        'delete' => ['id'], // 删除场景
    ];


    public function checkDeliveryMethod($value, $rule, $data)
    {
        return array_key_exists($value, PointsGoodsOrderEnum::getDeliveryMethodDict());
    }

    public function checkOrderStatus($value, $rule, $data)
    {
        return array_key_exists($value, PointsGoodsOrderEnum::getOrderStatusDict());
    }
}
