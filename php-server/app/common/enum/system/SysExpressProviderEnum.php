<?php

namespace app\common\enum\system;

/**
 * 快递查询服务商相关常量定义
 */
class SysExpressProviderEnum
{
    // 快递查询服务商
    const EXPRESS_PROVIDER_KUAIDINIAO = 'kuaidiniao';
    const EXPRESS_PROVIDER_KUAIDI100 = 'kuaidi100';

    // 获取快递查询服务商字典
    public static function getExpressProviderDict(): array
    {
        return [
            self::EXPRESS_PROVIDER_KUAIDINIAO => '快递鸟',
            self::EXPRESS_PROVIDER_KUAIDI100 => '快递100',
        ];
    }

    // 获取快递查询服务商描述
    public static function getExpressProviderDesc($value): string
    {
        $data = self::getExpressProviderDict();
        return $data[$value] ?? '未知快递查询服务商';
    }

    // 快递查询配置列表及配置参数
    public static function getExpressProviderList(): array
    {
        return [
            self::EXPRESS_PROVIDER_KUAIDINIAO => [
                'name' => '快递鸟',
                'provider' => self::EXPRESS_PROVIDER_KUAIDINIAO,
                'description' => '快递鸟物流轨迹查询API，支持1500+快递公司',
                'website' => 'https://www.kdniao.com/',
                'config' => [
                    'ebusiness_id' => '',
                    'app_key' => '',
                    // free 免费接口，paid 收费接口
                    'api_type' => 'free',
                ],
            ],
            self::EXPRESS_PROVIDER_KUAIDI100 => [
                'name' => '快递100',
                'provider' => self::EXPRESS_PROVIDER_KUAIDI100,
                'description' => '快递100实时查询API，支持800+快递公司',
                'website' => 'https://www.kuaidi100.com/',
                'config' => [
                    'customer' => '',
                    'key' => '',
                    // free 免费接口，paid 收费接口
                    'api_type' => 'free',
                ],
            ],
        ];
    }


} 