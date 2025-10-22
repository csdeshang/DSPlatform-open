<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;

use app\common\model\system\SysPlatformModel;
use app\common\model\merchant\MerchantModel;

use app\common\enum\store\TblStoreEnum;

class TblStoreModel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'tbl_store';


    // 关联平台 一对一 [platform]
    public function platformInfo()
    {
        return $this->hasOne(SysPlatformModel::class, 'platform', 'platform');
    }

    // 关联商户
    public function merchant()
    {
        return $this->hasOne(MerchantModel::class, 'id', 'merchant_id');
    }





    // 获取器
    public function getApplyStatusDescAttr($value, $data)
    {
        return TblStoreEnum::getApplyStatusDesc($data['apply_status']);
    }

    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }


}
