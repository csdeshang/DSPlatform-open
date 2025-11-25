<?php

namespace app\deshang\core;

use app\deshang\third_party\sms\SmsManager;
use app\deshang\third_party\upload\UploadManager;
use app\deshang\third_party\trade\TradeManager;
use app\deshang\third_party\lbs\LbsManager;
use app\deshang\third_party\express\ExpressManager;
use app\deshang\third_party\printer\PrinterManager;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\PayException;

use app\deshang\service\trade\DeshangTradePaymentConfigService;
use app\common\enum\trade\TradePaymentConfigEnum;

use app\common\dao\system\SysConfigDao;



class ThirdPartyLoader
{


    /**
     * 短信
     * @param string $provider 服务商名称
     * @return SmsManager
     */
    public static function sms($provider = '')
    {
        // 获取默认服务商
        if (empty($provider)) {
            $provider = sysConfig('sms:sms_default_provider');
        }

        // 获取服务商配置
        $config_value = sysConfig('sms:sms_config_' . $provider);
        if (empty($config_value)) {
            throw new CommonException('短信服务商配置错误');
        }
        // 解析配置
        $config = json_decode($config_value, true);

        // 根据服务商类型选择对应的短信类
        return new SmsManager($provider, $config);
    }


    // 上传
    public static function upload($provider = '')
    {
        if (empty($provider)) {
            $provider = sysConfig('storage:storage_default_provider');
        }

        $config = sysConfig('storage:storage_config_' . $provider);
        if (empty($config)) {
            throw new CommonException('上传配置错误');
        }
        $config = json_decode($config, true);
        return new UploadManager($provider, $config);
    }


    // 第三方登录
    public static function oauth() {}


    /**
     * 交易相关
     * @param int $merchant_id 商户id
     * @param string $trade_channel 交易渠道
     * @param string $trade_scene 交易场景
     * @param string $trade_type 交易类型
     * @param string $return_url 返回地址
     * @param string $quit_url 退出地址
     * @return TradeManager
     */
    public static function trade($merchant_id, $trade_channel, $trade_scene, $trade_type, $return_url = '', $quit_url = '')
    {

        $data = [
            'merchant_id' => $merchant_id,
            'payment_channel' => $trade_channel,
            'payment_scene' => $trade_scene,
            'is_enabled' => 1,
        ];


        $result = (new DeshangTradePaymentConfigService())->getPaymentConfigInfo($data);


        if (empty($result)) {
            throw new PayException('支付配置未开启');
        }
        // 设置回调地址 和 返回地址
        $result['config_data']['notify_url'] = (string)url('/api/trade/notify/' . $merchant_id . '/' . $trade_channel . '/' . $trade_scene . '/' . $trade_type . '/', [], false, true);
        $result['config_data']['return_url'] = $return_url;
        $result['config_data']['quit_url'] = $quit_url;

        $result['config_data']['trade_channel'] = $trade_channel;
        $result['config_data']['trade_scene'] = $trade_scene;
        $result['config_data']['trade_type'] = $trade_type;




        if ($result['payment_channel'] == TradePaymentConfigEnum::CHANNEL_ALIPAY) {
            // 支付宝支付配置部分检测
            if (empty($result['config_data']['app_id']) || empty($result['config_data']['app_secret_cert'])) {
                throw new PayException('支付宝支付配置未开启');
            }
            // 对应商户的支付配置
            return new TradeManager('Alipay', $result['config_data']);
        } else if ($result['payment_channel'] == TradePaymentConfigEnum::CHANNEL_WECHAT) {
            // 微信支付配置部分检测
            if (empty($result['config_data']['mch_id']) || empty($result['config_data']['mch_secret_key'])) {
                throw new PayException('微信支付配置未开启');
            }
            // 对应商户的支付配置
            return new TradeManager('Wechat', $result['config_data']);
        } else {
            throw new PayException('支付渠道错误,请检查配置');
        }
    }


    /**
     * 快递查询
     * @param string $provider 服务商名称
     * @return ExpressManager
     * 
     */
    // 使用默认服务商查询
    // $express = ThirdPartyLoader::express();
    // $result = $express->query('YT1234567890', 'YTO', '1380');
    // 使用指定服务商查询
    // $express = ThirdPartyLoader::express('kuaidiniao');
    // $result = $express->query('SF1234567890', 'SF');
    public static function express($provider = '')
    {
        // 获取默认服务商
        if (empty($provider)) {
            $provider = sysConfig('express:express_default_provider');
        }

        // 获取服务商配置
        $config_value = sysConfig('express:express_config_' . $provider);
        if (empty($config_value)) {
            throw new CommonException('快递查询服务商配置错误');
        }
        // 解析配置
        $config = json_decode($config_value, true);

        // 根据服务商类型选择对应的快递查询类
        return new ExpressManager($provider, $config);
    }



    // LBS
    public static function lbs($provider = '')
    {
        if (empty($provider)) {
            $provider = sysConfig('lbs:lbs_default_provider');
        }
        if (empty($provider)) {
            throw new CommonException('默认LBS服务未配置');
        }

        $config = sysConfig('lbs:lbs_config_' . $provider);
        if (empty($config)) {
            throw new CommonException('LBS配置错误');
        }
        $config = json_decode($config, true);
        return new LbsManager($provider, $config);
    }

    /**
     * 打印机服务
     * @param array $provider 打印机信息
     * @return PrinterManager
     */
    public static function printer($provider)
    {
        if (empty($provider)) {
            throw new CommonException('打印机服务商不能为空');
        }

        // 获取平台级配置
        $configValue = sysConfig('printer:printer_config_' . $provider);
        if (empty($configValue)) {
            throw new CommonException('打印机服务商配置错误');
        }

        $sysPrinterConfig = json_decode($configValue, true);
        if (empty($sysPrinterConfig)) {
            throw new CommonException('打印机配置解析错误');
        }

        // 如果是易联云，检查并更新 access_token 其他的服务商不处理
        if ($provider === 'yilian') {

            $accessToken = $sysPrinterConfig['access_token'] ?? '';
            $refreshToken = $sysPrinterConfig['refresh_token'] ?? '';
            $tokenExpireTime = $sysPrinterConfig['token_expire_time'] ?? 0;


            if (empty($accessToken) || empty($refreshToken) || empty($tokenExpireTime) || $tokenExpireTime < time()) {
                // 易联云获取新的access_token
                $yilianResult = (new PrinterManager($provider, $sysPrinterConfig))->getAccessToken();

                if ($yilianResult['success']) {
                    $sysPrinterConfig['access_token'] = $yilianResult['data']['access_token'];
                    $sysPrinterConfig['refresh_token'] = $yilianResult['data']['refresh_token'];
                    $sysPrinterConfig['token_expire_time'] = $yilianResult['data']['token_expire_time'];
                    // 更新系统配置
                    $configValue = json_encode($sysPrinterConfig, JSON_UNESCAPED_UNICODE);
                    $condition = [];
                    $condition[] = ['config_key', '=', 'printer_config_yilian'];
                    $condition[] = ['config_type', '=', 'printer'];
                    (new SysConfigDao())->updateSysConfig($condition, ['config_value' => $configValue]);
                } else {
                    throw new CommonException($yilianResult['message']);
                }
            }

        }

        // 根据服务商类型选择对应的打印机类
        return new PrinterManager($provider, $sysPrinterConfig);
    }
}
