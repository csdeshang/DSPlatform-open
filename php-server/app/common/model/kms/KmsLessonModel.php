<?php

namespace app\common\model\kms;

use app\deshang\base\BaseModel;
use app\common\enum\kms\KmsLessonEnum;
use app\common\model\goods\TblGoodsModel;
use app\common\model\store\TblStoreModel;



class KmsLessonModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'kms_lesson';

    // 关联商品表
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }

    // 关联店铺表
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 课时类型描述获取器
    public function getLessonTypeDescAttr($value, $data)
    {
        return KmsLessonEnum::getLessonTypeDesc($data['lesson_type']);
    }

    // 免费状态描述获取器
    public function getIsFreeDescAttr($value, $data)
    {
        return KmsLessonEnum::getIsFreeDesc($data['is_free']);
    }

    
}