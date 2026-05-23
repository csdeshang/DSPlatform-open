<?php

namespace app\common\enum\user;

class UserEnum
{


    
    // 会员性别
    const SEX_UNKNOWN = 0;    // 未知
    const SEX_MALE = 1;       // 男
    const SEX_FEMALE = 2;     // 女

    // 会员性别字典
    public static function getUserSexDict(): array
    {
        return [
            self::SEX_UNKNOWN => '未知',
            self::SEX_MALE => '男',
            self::SEX_FEMALE => '女',
        ];
    }

    // 获取会员性别描述
    public static function getUserSexDesc($value): string
    {
        $data = self::getUserSexDict();
        return $data[$value] ?? '未知';
    }


    // 实名认证状态
    const IDCARD_STATUS_DEFAULT = 0;   // 默认/未提交
    const IDCARD_STATUS_PENDING = 1;   // 审核中
    const IDCARD_STATUS_REJECTED = 2; // 未通过
    const IDCARD_STATUS_VERIFIED = 3; // 已认证

    // 实名认证状态字典
    public static function getIdcardStatusDict(): array
    {
        return [
            self::IDCARD_STATUS_DEFAULT => '未认证',
            self::IDCARD_STATUS_PENDING => '审核中',
            self::IDCARD_STATUS_REJECTED => '未通过',
            self::IDCARD_STATUS_VERIFIED => '已认证',
        ];
    }

    // 获取实名认证状态描述
    public static function getIdcardStatusDesc($value): string
    {
        $data = self::getIdcardStatusDict();
        return $data[$value] ?? '未知';
    }


}