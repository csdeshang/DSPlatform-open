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




}