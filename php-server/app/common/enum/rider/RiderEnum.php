<?php

namespace app\common\enum\rider;

class RiderEnum
{
    // 骑手状态
    const RIDER_STATUS_REST = 0;    // 休息
    const RIDER_STATUS_AVAILABLE = 1;    // 接单
    const RIDER_STATUS_BUSY = 2;    // 忙碌
    
    // 骑手状态字典
    public static function getRiderStatusDict(): array
    {
        return [
            self::RIDER_STATUS_REST => '休息',
            self::RIDER_STATUS_AVAILABLE => '接单',
            self::RIDER_STATUS_BUSY => '忙碌',
        ];
    }
    // 获取骑手状态描述
    public static function getRiderStatusDesc($value): string
    {
        $data = self::getRiderStatusDict();
        return $data[$value] ?? '未知';
    }

    
    // 骑手审核状态
    const APPLY_STATUS_PENDING = 0;   // 审核中
    const APPLY_STATUS_APPROVED = 1;  // 审核通过
    const APPLY_STATUS_REJECTED = 2;  // 拒绝
    
    // 骑手审核状态字典
    public static function getApplyStatusDict(): array
    {
        return [
            self::APPLY_STATUS_PENDING => '审核中',
            self::APPLY_STATUS_APPROVED => '审核通过',
            self::APPLY_STATUS_REJECTED => '拒绝',
        ];
    }
    
    // 获取骑手审核状态描述
    public static function getApplyStatusDesc($value): string
    {
        $data = self::getApplyStatusDict();
        return $data[$value] ?? '未知';
    }





}

