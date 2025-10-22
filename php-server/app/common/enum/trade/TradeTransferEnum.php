<?php

namespace app\common\enum\trade;


/**
 * 转账相关
 */
class TradeTransferEnum
{

    // 来源类型 (不同业务的转账来源，涉及的表不一样)
    const SOURCE_TYPE_WITHDRAWAL = 'withdrawal'; // 提现

    // 来源类型
    public static function getSourceTypeDict(): array
    {
        return [
            self::SOURCE_TYPE_WITHDRAWAL => '提现',
        ];
    }
    // 获取来源类型描述
    public static function getSourceTypeDesc($value): string
    {
        $data = self::getSourceTypeDict();
        return $data[$value] ?? '未知来源';
    }



    // 转账状态
    const TRANSFER_STATUS_FAILED = 0; // 转账失败
    const TRANSFER_STATUS_SUCCESS = 1; // 转账成功

    // 转账状态
    public static function getTransferStatusDict(): array
    {
        return [
            self::TRANSFER_STATUS_FAILED => '转账失败',
            self::TRANSFER_STATUS_SUCCESS => '转账成功',
        ];
    }
    // 获取转账状态描述
    public static function getTransferStatusDesc($value): string
    {
        $data = self::getTransferStatusDict();
        return $data[$value] ?? '未知状态';
    }




}
