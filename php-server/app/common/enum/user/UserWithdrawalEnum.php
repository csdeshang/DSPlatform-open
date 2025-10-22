<?php

namespace app\common\enum\user;

/**
 * 会员提现相关
 */
class UserWithdrawalEnum
{
    

    // 账户类型 (user_withdrawal_log user_withdrawal_account 表)
    const ACCOUNT_TYPE_ALIPAY = 'alipay'; // 支付宝
    const ACCOUNT_TYPE_WECHAT = 'wechat'; // 微信
    const ACCOUNT_TYPE_BANK = 'bank'; // 银行卡


    // 获取账户类型列表
    public static function getAccountTypeDict(): array
    {
        return [
            self::ACCOUNT_TYPE_ALIPAY => '支付宝',
            self::ACCOUNT_TYPE_WECHAT => '微信',
            self::ACCOUNT_TYPE_BANK => '银行卡',
        ];
    }


    // 获取账户类型描述
    public static function getAccountTypeDesc($value): string
    {
        $data = self::getAccountTypeDict();
        return $data[$value] ?? '未知类型';
    }


    // transfer_type  转账类型
    const TRANSFER_TYPE_MANUAL = 'manual'; // 手动
    const TRANSFER_TYPE_ALIPAY = 'alipay'; // 支付宝
    const TRANSFER_TYPE_WECHAT = 'wechat'; // 微信


    // 获取转账类型列表  (user_withdrawal_log 表)
    public static function getTransferTypeDict(): array
    {
        return [
            self::TRANSFER_TYPE_MANUAL => '手动支付',
            self::TRANSFER_TYPE_ALIPAY => '支付宝自动支付',
            // 暂时不支持微信自动支付
            // self::TRANSFER_TYPE_WECHAT => '微信自动支付',

        ];
    }

    
    // 获取转账类型描述
    public static function getTransferTypeDesc($value): string
    {
        $data = self::getTransferTypeDict();
        return $data[$value] ?? '未知类型';
    }


    // 提现状态 
    const STATUS_APPLY = 0; // 申请中
    const STATUS_APPROVED = 1; // 已同意
    const STATUS_REJECTED = 2; // 已拒绝


    // 获取提现状态列表  (user_withdrawal_log 表)
    public static function getStatusDict(): array
    {
        return [
            self::STATUS_APPLY => '申请中',
            self::STATUS_APPROVED => '已同意',
            self::STATUS_REJECTED => '已拒绝',
        ];
    }


    // 获取提现状态描述
    public static function getStatusDesc(int $value): string
    {
        $data = self::getStatusDict();
        return $data[$value] ?? '未知状态';
    }


}