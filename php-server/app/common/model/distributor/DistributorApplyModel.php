<?php

namespace app\common\model\distributor;

use app\deshang\base\BaseModel;

use app\common\enum\distributor\DistributorApplyEnum;

class DistributorApplyModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'distributor_apply';

    // 分销商状态描述获取器
    public function getApplyStatusDescAttr($value, $data)
    {
        return DistributorApplyEnum::getDistributorApplyStatusDesc($data['apply_status']);
    }

    // 分销商申请时间获取器
    public function getApplyTimeAttr($value, $data)
    {
        return $this->formatTime($data['apply_time']);
    }


    // 分销商审核时间获取器
    public function getAuditTimeAttr($value, $data)
    {
        return $this->formatTime($data['audit_time']);
    }

}