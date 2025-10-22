<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\model\goods\TblGoodsModel;

class TblGoodsCommentModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_comment';


    // 定义与用户模型的关系
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    
    // 定义与商品模型的关系
    public function goods()
    {
        return $this->hasOne(TblGoodsModel::class, 'id', 'goods_id');
    }




    // 回复时间 获取器
    public function getReplyTimeAttr($value, $data)
    {
        return $this->formatTime($data['reply_time']);
    }

    // 删除时间 获取器
    public function getDeletedAtAttr($value, $data)
    {
        return $this->formatTime($data['deleted_at']);
    }
    
    



    
}
