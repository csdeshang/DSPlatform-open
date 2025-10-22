<?php

namespace app\common\model\pointsGoods;

use app\deshang\base\BaseModel;
use app\common\enum\pointsGoods\PointsGoodsEvaluateEnum;
use app\common\model\user\UserModel;

class PointsGoodsEvaluateModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'points_goods_evaluate';


    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }


    // 评价分数获取器
    public function getEvaluateScoreDescAttr($value, $data)
    {
        return PointsGoodsEvaluateEnum::getEvaluateScoreDesc($data['evaluate_score']);
    }

    // 是否匿名获取器
    public function getIsAnonymousDescAttr($value, $data)
    {
        return PointsGoodsEvaluateEnum::getIsAnonymousDesc($data['is_anonymous']);
    }

    // 是否显示获取器
    public function getIsShowDescAttr($value, $data)
    {
        return PointsGoodsEvaluateEnum::getIsShowDesc($data['is_show']);
    }
}
