<?php

namespace app\common\enum\technician;

class TechnicianEnum
{



    // 师傅审核状态
    const APPLY_STATUS_PENDING = 0;   // 审核中
    const APPLY_STATUS_APPROVED = 1;  // 审核通过
    const APPLY_STATUS_REJECTED = 2;  // 拒绝

    // 师傅审核状态字典
    public static function getApplyStatusDict(): array
    {
        return [
            self::APPLY_STATUS_PENDING => '审核中',
            self::APPLY_STATUS_APPROVED => '审核通过',
            self::APPLY_STATUS_REJECTED => '拒绝',
        ];
    }

    // 获取师傅审核状态描述
    public static function getApplyStatusDesc($value): string
    {
        $data = self::getApplyStatusDict();
        return $data[$value] ?? '未知';
    }




    // 师傅状态
    const TECHNICIAN_STATUS_REST = 0;    // 休息
    const TECHNICIAN_STATUS_AVAILABLE = 1;    // 接单
    const TECHNICIAN_STATUS_BUSY = 2;    // 忙碌

    // 师傅状态字典
    public static function getTechnicianStatusDict(): array
    {
        return [
            self::TECHNICIAN_STATUS_REST => '休息',
            self::TECHNICIAN_STATUS_AVAILABLE => '接单',
            self::TECHNICIAN_STATUS_BUSY => '忙碌',
        ];
    }
    // 获取师傅状态描述
    public static function getTechnicianStatusDesc($value): string
    {
        $data = self::getTechnicianStatusDict();
        return $data[$value] ?? '未知';
    }






    // 师傅性别
    const GENDER_UNKNOWN = 0;    // 未知
    const GENDER_MALE = 1;       // 男
    const GENDER_FEMALE = 2;     // 女

    // 师傅性别字典
    public static function getTechnicianGenderDict(): array
    {
        return [
            self::GENDER_UNKNOWN => '未知',
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
        ];
    }

    // 获取师傅性别描述
    public static function getTechnicianGenderDesc($value): string
    {
        $data = self::getTechnicianGenderDict();
        return $data[$value] ?? '未知';
    }



    

}
