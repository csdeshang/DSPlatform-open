<?php

namespace app\common\enum\store;

class TblStoreEnum
{
    // 店铺审核状态
    const APPLY_STATUS_WAIT = 0;    // 待审核
    const APPLY_STATUS_APPROVED = 1;    // 审核通过
    const APPLY_STATUS_REJECTED = 2;    // 拒绝
    
    // 店铺审核状态字典
    public static function getApplyStatusDict(): array
    {
        return [
            self::APPLY_STATUS_WAIT => '待审核',
            self::APPLY_STATUS_APPROVED => '审核通过',
            self::APPLY_STATUS_REJECTED => '拒绝',
        ];
    }
    // 获取店铺审核状态描述
    public static function getApplyStatusDesc($value): string
    {
        $data = self::getApplyStatusDict();
        return $data[$value] ?? '未知';
    }







}

