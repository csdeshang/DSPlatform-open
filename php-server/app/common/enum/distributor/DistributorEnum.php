<?php

namespace app\common\enum\distributor;

class DistributorEnum
{
    // 状态
    const STATUS_NORMAL = 1; // 正常
    const STATUS_FROZEN = 2; // 冻结


    /**
     * 获取分销商状态列表
     * @return array
     */
    public static function getDistributorStatusDict(): array
    {
        return [
            self::STATUS_NORMAL => '正常',
            self::STATUS_FROZEN => '冻结',

        ];
    }

    /**
     * 获取分销商状态描述
     * @param string $value
     * @return string
     */
    public static function getDistributorStatusDesc($value): string
    {
        $data = self::getDistributorStatusDict();
        return $data[$value] ?? '未知状态';
    }




    
}
