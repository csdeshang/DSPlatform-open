<?php

namespace app\common\enum\distributor;

class DistributorGoodsEnum
{
    // 状态
    const TYPE_DEFAULT = 0; // 默认等级设置佣金比例
    const TYPE_RATIO = 1; // 单独设置比例
    const TYPE_FIXED = 2; // 固定金额



    /**
     * 获取分销商状态列表
     * @return array
     */
    public static function getDistributorGoodsTypeDict(): array
    {
        return [
            self::TYPE_DEFAULT => '默认等级比例',
            self::TYPE_RATIO => '单独设置比例',
            self::TYPE_FIXED => '单独设置金额',
        ];
    }

    /**
     * 获取分销商状态描述
     * @param string $value
     * @return string
     */
    public static function getDistributorGoodsTypeDesc($value): string
    {
        $data = self::getDistributorGoodsTypeDict();
        return $data[$value] ?? '未知状态';
    }




    
}
