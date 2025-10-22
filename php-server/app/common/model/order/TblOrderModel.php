<?php

namespace app\common\model\order;

use app\deshang\base\BaseModel;

use app\common\model\system\SysPlatformModel;
use app\common\model\merchant\MerchantModel;
use app\common\model\order\TblOrderMergeModel;
use app\common\model\store\TblStoreModel;
use app\common\model\order\TblOrderDeliveryModel;
use app\common\model\order\TblOrderAddressModel;
use app\common\model\user\UserModel;

use app\common\enum\order\TblOrderEnum;


class TblOrderModel extends BaseModel
{

    // 表名
    protected $name = 'tbl_order';


    // 关联平台
    public function platform()
    {
        return $this->hasOne(SysPlatformModel::class, 'platform', 'platform');
    }

    // 关联收款方
    public function payMerchant()
    {
        return $this->hasOne(MerchantModel::class, 'id', 'pay_merchant_id');
    }

    // 关联合并订单
    public function orderMerge()
    {
        return $this->hasOne(TblOrderMergeModel::class, 'id', 'order_merge_id');
    }

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 关联交付
    public function orderDelivery()
    {
        return $this->hasOne(TblOrderDeliveryModel::class, 'order_id', 'id');
    }

    // 关联订单商品
    public function orderGoodsList()
    {
        return $this->hasMany(TblOrderGoodsModel::class, 'order_id', 'id');
    }

    // 关联订单地址
    public function orderAddress()
    {
        return $this->hasOne(TblOrderAddressModel::class, 'order_id', 'id');
    }


    // 关联订单日志
    public function orderLogList()
    {
        return $this->hasMany(TblOrderLogModel::class, 'order_id', 'id');
    }

    // 关联订单退款
    public function orderRefundList()
    {
        return $this->hasMany(TblOrderRefundModel::class, 'order_id', 'id');
    }

    // 关联订单资金分配
    public function orderFinance()
    {
        return $this->hasOne(TblOrderFinanceModel::class, 'order_id', 'id');
    }








    // 获取器

    // 订单状态描述获取器
    public function getOrderStatusDescAttr($value, $data)
    {
        $platform = $data['platform'] ?? '';

        if ($platform == 'mall') {
            $statusDict = TblOrderEnum::getMallOrderStatusDict();
        } elseif ($platform == 'food') {
            $statusDict = TblOrderEnum::getFoodOrderStatusDict();
        } elseif ($platform == 'house') {
            $statusDict = TblOrderEnum::getHouseOrderStatusDict();
        } elseif ($platform == 'kms') {
            $statusDict = TblOrderEnum::getKmsOrderStatusDict();
        } else {
            // 默认使用商城状态
            $statusDict = TblOrderEnum::getMallOrderStatusDict();
        }

        return $statusDict[$data['order_status']] ?? '未知状态';
    }

    // 交付方式描述获取器
    public function getDeliveryMethodDescAttr($value, $data)
    {
        return TblOrderEnum::getAllDeliveryDesc($data['delivery_method']);
    }

    // 商品总原价 获取器
    public function getOriginalAmountAttr($value, $data)
    {
        return $this->formatPrice($data['original_amount']);
    }

    // 订单金额格式化获取器
    public function getOrderAmountAttr($value, $data)
    {
        return $this->formatPrice($data['order_amount']);
    }

    // 支付金额格式化获取器
    public function getPayAmountAttr($value, $data)
    {
        return $this->formatPrice($data['pay_amount']);
    }

    // 平台服务费格式化获取器
    public function getServiceAmountAttr($value, $data)
    {
        return $this->formatPrice($data['service_amount']);
    }

    // 商品金额格式化获取器
    public function getGoodsAmountAttr($value, $data)
    {
        return $this->formatPrice($data['goods_amount']);
    }

    // 运费格式化获取器
    public function getShippingAmountAttr($value, $data)
    {
        return $this->formatPrice($data['shipping_amount']);
    }

    // 优惠金额格式化获取器
    public function getDiscountAmountAttr($value, $data)
    {
        return $this->formatPrice($data['discount_amount']);
    }

    // 退款金额格式化获取器
    public function getRefundAmountAttr($value, $data)
    {
        return $this->formatPrice($data['refund_amount']);
    }


    // 订单生成时间获取器
    public function getAddTimeAttr($value, $data)
    {
        return $this->formatTime($data['add_time']);
    }

    // 支付时间获取器
    public function getPaymentTimeAttr($value, $data)
    {
        return $this->formatTime($data['payment_time']);
    }
    // 交付时间获取器
    public function getDeliveryTimeAttr($value, $data)
    {
        return $this->formatTime($data['delivery_time']);
    }

    // 配送时间获取器
    public function getShippingTimeAttr($value, $data)
    {
        return $this->formatTime($data['shipping_time']);
    }

    // 订单完成时间获取器
    public function getFinnshedTimeAttr($value, $data)
    {
        return $this->formatTime($data['finnshed_time']);
    }

    // 评价时间获取器
    public function getEvaluateTimeAttr($value, $data)
    {
        return $this->formatTime($data['evaluate_time']);
    }

    // 订单关闭时间获取器
    public function getCloseTimeAttr($value, $data)
    {
        return $this->formatTime($data['close_time']);
    }

    // 取消时间获取器
    public function getCancelTimeAttr($value, $data)
    {
        return $this->formatTime($data['cancel_time']);
    }

    // 允许退款时间获取器
    public function getAllowRefundTimeAttr($value, $data)
    {
        return $this->formatTime($data['allow_refund_time']);
    }



    // 删除时间获取器
    public function getDeletedAtAttr($value, $data)
    {
        return $this->formatTime($data['deleted_at']);
    }
}
