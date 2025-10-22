<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;
use app\common\model\user\UserGrowthLevelModel;

/**
 * 商品平台会员等级价格模型
 */
class TblGoodsUserdiscountModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_userdiscount';
    
    /**
     * 关联用户等级表
     */
    public function userLevel()
    {
        return $this->hasOne(UserGrowthLevelModel::class, 'id', 'user_level_id');
    }

    // 会员折扣价 获取器
    public function getUserLevelPriceAttr($value, $data)
    {
        return $this->formatPrice($data['user_level_price']);
    }

} 