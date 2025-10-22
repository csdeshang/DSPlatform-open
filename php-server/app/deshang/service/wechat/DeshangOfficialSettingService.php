<?php


namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;

use app\common\dao\wechat\WechatSettingDao;

class DeshangOfficialSettingService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatOfficialSetting(int $merchant_id)
    {


        // 获取微信设置  merchant_id = 0 为系统设置   wechat_official_setting字段 为json字符串 公众号配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);
        

        $wechat_official_setting = isset($wechat_setting['wechat_official_setting']) ? json_decode($wechat_setting['wechat_official_setting'], true) : [];


        $result = [
            'app_id' => $wechat_official_setting['app_id'] ?? '',
            'app_secret' => $wechat_official_setting['app_secret'] ?? '',
            'token' => $wechat_official_setting['token'] ?? '',
            'encoding_aes_key' => $wechat_official_setting['encoding_aes_key'] ?? '',
            'encryption_type' => $wechat_official_setting['encryption_type'] ?? '',
        ];

        return $result;

    }


    public function updateWechatOfficialSetting(int $merchant_id, array $data)
    {

        // 获取微信设置  merchant_id = 0 为系统设置  wechat_official_setting字段 为json字符串 公众号配置信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);

        $wechat_official_setting = [
            'app_id' => $data['app_id'] ?? '',
            'app_secret' => $data['app_secret'] ?? '',
            'token' => $data['token'] ?? '',
            'encoding_aes_key' => $data['encoding_aes_key'] ?? '',
            'encryption_type' => $data['encryption_type'] ?? '',
        ];

        if (empty($wechat_setting)) {
            $update_data = [
                'merchant_id' => $merchant_id,
                'wechat_official_setting' => json_encode($wechat_official_setting),
            ];
            $result = (new WechatSettingDao())->createWechatSetting($update_data);
        } else {
            $update_data = [
                'wechat_official_setting' => json_encode($wechat_official_setting),
            ];
            $result = (new WechatSettingDao())->updateWechatSetting([['merchant_id', '=', $merchant_id]], $update_data);
        }

        
        return $result;
    }



}