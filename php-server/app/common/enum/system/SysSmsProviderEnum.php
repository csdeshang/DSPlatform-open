<?php

namespace app\common\enum\system;

/**
 * 系统通知相关常量定义
 */
class SysSmsProviderEnum
{

    // 短信服务商
    const SMS_PROVIDER_ALIYUN = 'aliyun';
    const SMS_PROVIDER_TENCENT = 'tencent';

    // 获取短信服务商字典
    public static function getSmsProviderDict(): array
    {
        return [
            self::SMS_PROVIDER_ALIYUN => '阿里云',
            self::SMS_PROVIDER_TENCENT => '腾讯云',
        ];
    }

    // 获取短信服务商描述
    public static function getSmsProviderDesc($value): string
    {
        $data = self::getSmsProviderDict();
        return $data[$value] ?? '未知短信服务商';
    }


    // 短信配置列表 及 配置参数
    public static function getSmsProviderList(): array
    {
        return [
            self::SMS_PROVIDER_ALIYUN => [
                'name' => '阿里云',
                'provider' => self::SMS_PROVIDER_ALIYUN,
                'config' => [
                    'sign_name' => '阿里云',
                    'access_key_id' => '',
                    'access_key_secret' => '',
                ],
            ],
            // self::SMS_PROVIDER_TENCENT => [
            //     'name' => '腾讯云',
            //     'provider' => self::SMS_PROVIDER_TENCENT,   
            //     'config' => [
            //         'sign_name' => '腾讯云',
            //         'access_key_id' => 'access_key_id',
            //         'access_key_secret' => 'access_key_secret',
            //     ],
            // ],
        ];
    }











}
