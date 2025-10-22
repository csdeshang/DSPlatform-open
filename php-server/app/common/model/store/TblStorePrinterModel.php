<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;
use app\common\model\store\TblStoreModel;
use app\common\enum\system\SysPrinterProviderEnum;


class TblStorePrinterModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store_printer';

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }

    // 获取器
    public function getPrinterProviderDescAttr($value, $data)
    {
        return SysPrinterProviderEnum::getPrinterProviderDesc($data['printer_provider']);
    }
}
