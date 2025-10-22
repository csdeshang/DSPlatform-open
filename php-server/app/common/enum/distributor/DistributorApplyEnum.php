<?php

namespace app\common\enum\distributor;

class DistributorApplyEnum
{
    // 状态
    const APPLY_STATUS_PENDING = 0; // 待审核
    const APPLY_STATUS_APPROVED = 1; // 审核通过
    const APPLY_STATUS_REJECTED = 2; // 审核拒绝


    /**
     * 获取分销商申请状态列表
     * @return array
     */
    public static function getDistributorApplyStatusDict(): array
    {
        return [
            self::APPLY_STATUS_PENDING => '待审核',
            self::APPLY_STATUS_APPROVED => '审核通过',
            self::APPLY_STATUS_REJECTED => '审核拒绝',

        ];
    }

    /**
     * 获取分销商申请状态描述
     * @param string $value
     * @return string
     */
    public static function getDistributorApplyStatusDesc($value): string
    {
        $data = self::getDistributorApplyStatusDict();
        return $data[$value] ?? '未知状态';
    }




    
}
