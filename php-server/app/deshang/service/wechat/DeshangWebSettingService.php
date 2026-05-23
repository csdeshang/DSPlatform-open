<?php


namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;

use app\common\dao\wechat\WechatSettingDao;

class DeshangWebSettingService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatWebSetting(int $merchant_id)
    {


        // 获取微信设置  merchant_id = 0 为系统设置   wechat_web_setting字段 为json字符串 网站应用配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);
        

        $wechat_web_setting = isset($wechat_setting['wechat_web_setting']) ? json_decode($wechat_setting['wechat_web_setting'], true) : [];


        $result = [
            'app_id' => $wechat_web_setting['app_id'] ?? '',
            'app_secret' => $wechat_web_setting['app_secret'] ?? '',
        ];

        return $result;

    }


    public function updateWechatWebSetting(int $merchant_id, array $data)
    {

        // 获取微信设置  merchant_id = 0 为系统设置  wechat_web_setting字段 为json字符串 网站应用配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);

        $wechat_web_setting = [
            'app_id' => $data['app_id'] ?? '',
            'app_secret' => $data['app_secret'] ?? '',
        ];

        if (empty($wechat_setting)) {
            $update_data = [
                'merchant_id' => $merchant_id,
                'wechat_web_setting' => json_encode($wechat_web_setting),
            ];
            $result = (new WechatSettingDao())->createWechatSetting($update_data);
        } else {
            $update_data = [
                'wechat_web_setting' => json_encode($wechat_web_setting),
            ];
            $result = (new WechatSettingDao())->updateWechatSetting([['merchant_id', '=', $merchant_id]], $update_data);
        }

        
        return $result;
    }



}
