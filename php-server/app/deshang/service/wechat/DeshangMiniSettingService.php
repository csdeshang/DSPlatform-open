<?php


namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;

use app\common\dao\wechat\WechatSettingDao;

class DeshangMiniSettingService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatMiniSetting(int $merchant_id)
    {


        // 获取微信设置  merchant_id = 0 为系统设置   wechat_mini_setting字段 为json字符串 小程序配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);
        

        $wechat_mini_setting = isset($wechat_setting['wechat_mini_setting']) ? json_decode($wechat_setting['wechat_mini_setting'], true) : [];


        $result = [
            'app_id' => $wechat_mini_setting['app_id'] ?? '',
            'app_secret' => $wechat_mini_setting['app_secret'] ?? '',
        ];

        return $result;

    }


    public function updateWechatMiniSetting(int $merchant_id, array $data)
    {

        // 获取微信设置  merchant_id = 0 为系统设置  wechat_mini_setting字段 为json字符串 小程序配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);

        $wechat_mini_setting = [
            'app_id' => $data['app_id'] ?? '',
            'app_secret' => $data['app_secret'] ?? '',
        ];

        if (empty($wechat_setting)) {
            $update_data = [
                'merchant_id' => $merchant_id,
                'wechat_mini_setting' => json_encode($wechat_mini_setting),
            ];
            $result = (new WechatSettingDao())->createWechatSetting($update_data);
        } else {
            $update_data = [
                'wechat_mini_setting' => json_encode($wechat_mini_setting),
            ];
            $result = (new WechatSettingDao())->updateWechatSetting([['merchant_id', '=', $merchant_id]], $update_data);
        }

        
        return $result;
    }



}