<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;
use app\common\model\store\TblStorePrinterModel;
use app\common\model\store\TblStoreModel;

class TblStorePrinterLogModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store_printer_log';

    // 关联打印机
    public function printer()
    {
        return $this->hasOne(TblStorePrinterModel::class, 'id', 'printer_id');
    }

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

}