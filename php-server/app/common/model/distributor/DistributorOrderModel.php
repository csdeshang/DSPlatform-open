<?php

namespace app\common\model\distributor;

use app\deshang\base\BaseModel;
use app\common\enum\distributor\DistributorOrderEnum;

use app\common\model\merchant\MerchantModel;
use app\common\model\user\UserModel;
use app\common\model\order\TblOrderModel;
use app\common\model\store\TblStoreModel;
use app\common\model\goods\TblGoodsModel;

class DistributorOrderModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'distributor_order';


    // 关联订单
    public function order()
    {
        return $this->hasOne(TblOrderModel::class, 'id', 'order_id');
    }
    // 关联分销员
    public function distributorUser()
    {
        return $this->hasOne(UserModel::class, 'id', 'distributor_user_id');
    }

    // 关联商户
    public function merchant()
    {
        return $this->hasOne(MerchantModel::class, 'id', 'merchant_id');
    }
    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }
    // 关联商品
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }







    // 支付金额 获取器
    public function getPayPriceAttr($value, $data)
    {
        return $this->formatPrice($data['pay_price']);
    }

    // 佣金金额 获取器
    public function getCommissionAmountAttr($value, $data)
    {
        return $this->formatPrice($data['commission_amount']);
    }

    // 佣金类型 获取器
    public function getCommissionTypeDescAttr($value, $data)
    {
        return DistributorOrderEnum::getCommissionTypeDesc($data['commission_type']);
    }

    // 佣金状态 获取器
    public function getCommissionStatusDescAttr($value, $data)
    {
        return DistributorOrderEnum::getCommissionStatusDesc($data['commission_status']);
    }
}
