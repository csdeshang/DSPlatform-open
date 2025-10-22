<?php

namespace app\common\model\pointsGoods;

use app\deshang\base\BaseModel;
use app\common\enum\pointsGoods\PointsGoodsEnum;

class PointsGoodsModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'points_goods';

    // 商品状态获取器
    public function getGoodsStatusDescAttr($value, $data)
    {
        return PointsGoodsEnum::getGoodsStatusDesc($data['goods_status']);
    }

    // 是否热门获取器
    public function getIsHotDescAttr($value, $data)
    {
        return PointsGoodsEnum::getIsHotDesc($data['is_hot']);
    }

    // 是否推荐获取器
    public function getIsRecommendDescAttr($value, $data)
    {
        return PointsGoodsEnum::getIsRecommendDesc($data['is_recommend']);
    }

    // 是否新品获取器
    public function getIsNewDescAttr($value, $data)
    {
        return PointsGoodsEnum::getIsNewDesc($data['is_new']);
    }
}
