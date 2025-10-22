<?php


namespace app\deshang\service\store;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\model\store\TblStoreCouponUserModel;
use app\common\enum\store\TblStoreCouponEnum;
use app\common\enum\store\TblStoreCouponUserEnum;

class DeshangTblStoreCouponService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    // 下单当前可用的优惠券列表
    public function getAvailableStoreCoupons(int $store_id, int $user_id, float $goods_amount): array
    {
        $available_store_coupons = (new TblStoreCouponUserModel)
            ->hasWhere('storeCoupon', [
                ['store_id', '=', $store_id],
                ['status', '=', TblStoreCouponEnum::STATUS_START],
                ['start_time', '<=', time()],
                ['end_time', '>=', time()],
                ['min_spend', '<=', $goods_amount],
            ])
            ->where('status', '=', TblStoreCouponUserEnum::USER_STATUS_UNUSED)
            ->where('user_id', '=', $user_id)
            ->with(
                [
                    'storeCoupon' => function ($query) {
                        $query->field('*');
                    }
                ]
            )
            ->limit(20)
            ->select()
            ->toArray();

        return $available_store_coupons;
    }


    // 修改优惠券使用状态
    public function isUsedStoreCoupon(array $store_coupon_info)
    {
        $result = (new TblStoreCouponUserModel())->where('id', $store_coupon_info['id'])->update(['status' => TblStoreCouponUserEnum::USER_STATUS_USED]);
        if ($result === false) {
            throw new CommonException('修改优惠券使用状态失败');
        }
        return $result;
    }







}