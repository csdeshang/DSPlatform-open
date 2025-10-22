<?php

namespace app\common\enum\system;

/**
 * 地图服务提供商常量定义
 */
class SysLbsProviderEnum
{

    // 地图服务提供商
    const LBS_PROVIDER_BAIDU = 'baidu';
    const LBS_PROVIDER_TENCENT = 'tencent';
    const LBS_PROVIDER_TIANDI = 'tianditu';
    const LBS_PROVIDER_GAODE = 'gaode';

    // 获取地图服务提供商字典
    public static function getLbsProviderDict(): array
    {
        return [
            self::LBS_PROVIDER_BAIDU => '百度',
            self::LBS_PROVIDER_TENCENT => '腾讯',
            self::LBS_PROVIDER_GAODE => '高德',
            self::LBS_PROVIDER_TIANDI => '天地图',
        ];
    }

    // 获取地图服务提供商描述
    public static function getLbsProviderDesc($value): string
    {
        $data = self::getLbsProviderDict();
        return $data[$value] ?? '未知地图服务提供商';
    }


    // 地图配置列表 及 配置参数
    public static function getLbsProviderList(): array
    {
        return [
            self::LBS_PROVIDER_BAIDU => [
                'name' => '百度地图',
                'provider' => self::LBS_PROVIDER_BAIDU,
                'config' => [
                    // 服务端AK
                    'service_ak' => '',
                    // 浏览器AK
                    'browser_ak' => '',
                ],
            ],
            self::LBS_PROVIDER_TENCENT => [
                'name' => '腾讯地图',
                'provider' => self::LBS_PROVIDER_TENCENT,
                'config' => [
                    // 腾讯只需一个KEY
                    'service_key' => '',
                ],
            ],
            self::LBS_PROVIDER_GAODE => [
                'name' => '高德地图',
                'provider' => self::LBS_PROVIDER_GAODE,   
                'config' => [
                    // 浏览器KEY
                    'browser_key' => '',
                    // 浏览器密钥
                    'browser_secret' => '',
                    // 服务端KEY
                    'service_key' => '',
                ],
            ],
            self::LBS_PROVIDER_TIANDI => [
                'name' => '天地图',
                'provider' => self::LBS_PROVIDER_TIANDI,
                'config' => [
                    // 浏览器KEY
                    'browser_key' => '',
                    // 服务端KEY
                    'service_key' => '',
                ],
            ],
        ];
    }











}
