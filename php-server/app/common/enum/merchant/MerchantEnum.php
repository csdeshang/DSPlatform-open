<?php

namespace app\common\enum\merchant;

/**
 * 商户相关枚举
 */
class MerchantEnum
{
    

    // 申请状态
    const APPLY_STATUS_WAIT = 0; // 待审核
    const APPLY_STATUS_PASS = 1; // 审核通过
    const APPLY_STATUS_REJECT = 2; // 审核拒绝


    // 获取申请状态列表
    public static function getApplyStatusDict(): array
    {
        return [
            self::APPLY_STATUS_WAIT => '待审核',
            self::APPLY_STATUS_PASS => '审核通过',
            self::APPLY_STATUS_REJECT => '审核拒绝',
        ];
    }

    // 获取申请状态描述
    public static function getApplyStatusDesc($value): string
    {
        $data = self::getApplyStatusDict();
        return $data[$value] ?? '未知状态';
    }


    // 是否启用
    const IS_ENABLED_YES = 1; // 启用
    const IS_ENABLED_NO = 0; // 禁用

    // 获取是否启用列表
    public static function getIsEnabledDict(): array
    {
        return [
            self::IS_ENABLED_YES => '启用',
            self::IS_ENABLED_NO => '禁用',
        ];
    }

    // 获取是否启用描述
    public static function getIsEnabledDesc($value): string
    {
        $data = self::getIsEnabledDict();
        return $data[$value] ?? '未知状态';
    }


}