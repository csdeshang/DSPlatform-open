<?php

namespace app\common\enum\system;

/**
 * 系统通知相关常量定义
 */
class SysStorageProviderEnum
{

    // 存储类型
    const STORAGE_PROVIDER_LOCAL = 'local';
    const STORAGE_PROVIDER_ALIYUN = 'aliyun';
    const STORAGE_PROVIDER_TENCENT = 'tencent';

    // 获取存储类型字典
    public static function getStorageTypeDict(): array
    {
        return [
            self::STORAGE_PROVIDER_LOCAL => '本地',
            self::STORAGE_PROVIDER_ALIYUN => '阿里云',
            self::STORAGE_PROVIDER_TENCENT => '腾讯云',
        ];
    }

    // 获取存储类型描述
    public static function getStorageTypeDesc($value): string
    {
        $data = self::getStorageTypeDict();
        return $data[$value] ?? '未知存储类型';
    }


    // 存储配置参数 
    public static function getStorageProviderList(): array
    {
        return [
            self::STORAGE_PROVIDER_LOCAL => [
                'name' => '本地',
                'provider' => self::STORAGE_PROVIDER_LOCAL,
                'config' => [
                ],
            ],
            self::STORAGE_PROVIDER_ALIYUN => [
                'name' => '阿里云',
                'provider' => self::STORAGE_PROVIDER_ALIYUN,
                'config' => [
                    'access_key_id' => '', // 访问密钥ID
                    'access_key_secret' => '', // 访问密钥密码
                    'endpoint' => '', // 地域节点，如：oss-cn-hangzhou.aliyuncs.com
                    'bucket' => '', // 存储桶名称
                ],
            ],
            // self::STORAGE_PROVIDER_TENCENT => [
            //     'name' => '腾讯云',
            //     'provider' => self::STORAGE_PROVIDER_TENCENT,
            //     'config' => [
            //         'access_key_id' => '', // 访问密钥ID
            //         'access_key_secret' => '', // 访问密钥密码
            //         'region' => '', // 地域，如：ap-shanghai
            //         'bucket' => '', // 存储桶名称
            //     ],
            // ],
        ];
    }











}
