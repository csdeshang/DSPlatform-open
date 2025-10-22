<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;

use app\common\model\system\SysPlatformModel;
use app\common\model\goods\TblGoodsSkuModel;
use app\common\model\store\TblStoreModel;
use app\common\enum\goods\TblGoodsEnum;



class TblGoodsModel extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods';


    // 关联平台
    public function platform()
    {
        return $this->hasOne(SysPlatformModel::class, 'platform', 'platform');
    }

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 关联商品sku 一对一
    public function goodsSku()
    {
        return $this->hasOne(TblGoodsSkuModel::class, 'goods_id', 'id');
    }

    // 关联商品sku 一对多
    public function goodsSkuList()
    {
        return $this->hasMany(TblGoodsSkuModel::class, 'goods_id', 'id');
    }

    // 管理商品Spec 一对多
    public function goodsSpecList()
    {
        return $this->hasMany(TblGoodsSpecModel::class, 'goods_id', 'id');
    }


    
    // 系统状态描述获取器
    public function getSysStatusDescAttr($value, $data)
    {
        return TblGoodsEnum::getSysStatusDesc($data['sys_status']);
    }

    // 商品状态描述获取器
    public function getGoodsStatusDescAttr($value, $data)
    {
        return TblGoodsEnum::getGoodsStatusDesc($data['goods_status']);
    }




}
