<?php

namespace app\common\model\goods;

use app\deshang\base\BaseModel;

class TblGoodsFlashsaleModel extends BaseModel
{


    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_goods_flashsale';



    // 开始时间 获取器
    public function getStartTimeAttr($value, $data)
    {
        return $this->formatTime($data['start_time']);
    }
    

    // 结束时间 获取器
    public function getEndTimeAttr($value, $data)
    {
        return $this->formatTime($data['end_time']);
    }


    // 折扣价 获取器
    public function getFlashsalePriceAttr($value, $data)
    {
        return $this->formatPrice($data['flashsale_price']);
    }










}