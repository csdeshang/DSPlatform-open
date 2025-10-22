<?php

namespace app\common\model\pointsGoods;

use app\deshang\base\BaseModel;
use app\common\enum\pointsGoods\PointsGoodsCategoryEnum;

class PointsGoodsCategoryModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'points_goods_category';

    // 是否显示获取器
    public function getIsShowDescAttr($value, $data)
    {
        return PointsGoodsCategoryEnum::getIsShowDesc($data['is_show']);
    }
}
