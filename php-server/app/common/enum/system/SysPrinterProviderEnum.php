<?php

namespace app\common\enum\system;

/**
 * 打印机服务商常量定义
 */
class SysPrinterProviderEnum
{
    // 打印机服务商
    const PRINTER_PROVIDER_FEIE = 'feie';
    const PRINTER_PROVIDER_YILIAN = 'yilian';
    // const PRINTER_PROVIDER_XINYE = 'xinye';
    // const PRINTER_PROVIDER_JIABO = 'jiabo';
    


    // 获取打印机服务商字典
    public static function getPrinterProviderDict(): array
    {
        return [
            self::PRINTER_PROVIDER_FEIE => '飞鹅打印机',
            self::PRINTER_PROVIDER_YILIAN => '易联云打印机',
            // self::PRINTER_PROVIDER_XINYE => '芯烨打印机',
            // self::PRINTER_PROVIDER_JIABO => '佳博打印机',
        ];
    }

    // 获取打印机服务商描述
    public static function getPrinterProviderDesc($value): string
    {
        $data = self::getPrinterProviderDict();
        return $data[$value] ?? '未知打印机服务商';
    }

    // 打印机配置列表及配置参数
    public static function getPrinterProviderList(): array
    {
        return [
            // https://help.feieyun.com/home/doc/zh;nav=1-3
            self::PRINTER_PROVIDER_FEIE => [
                'name' => '飞鹅打印机',
                'provider' => self::PRINTER_PROVIDER_FEIE,
                'config' => [
                    'user' => '飞鹅云后台注册用户名',
                    'ukey' => '飞鹅云后台登录生成的UKEY',
                ],
            ],
            // https://www.kancloud.cn/ly6886/oauth-api/3170322
            self::PRINTER_PROVIDER_YILIAN => [
                'name' => '易联云打印机',
                'provider' => self::PRINTER_PROVIDER_YILIAN,
                'config' => [
                    'client_id' => '易联云应用ID',
                    'client_secret' => '易联云应用密钥',
                    'access_token' => '易联云访问令牌(自动获取)',
                    'refresh_token' => '易联云刷新令牌(自动获取)',
                    'token_expire_time' => '易联云令牌过期时间(自动获取)',
                ],
            ],
            // self::PRINTER_PROVIDER_XINYE => [
            //     'name' => '芯烨打印机',
            //     'provider' => self::PRINTER_PROVIDER_XINYE,
            //     'config' => [
            //         'api_key' => '芯烨API密钥',
            //         'api_secret' => '芯烨API密钥',
            //     ],
            // ],
            // self::PRINTER_PROVIDER_JIABO => [
            //     'name' => '佳博打印机',
            //     'provider' => self::PRINTER_PROVIDER_JIABO,
            //     'config' => [
            //         'api_key' => '佳博API密钥',
            //         'api_secret' => '佳博API密钥',
            //     ],
            // ],
        ];
    }
}