<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;

use app\deshang\service\wechat\DeshangMiniSettingService;

class WechatMiniSettingService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatMiniSetting()
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;

        $result = (new DeshangMiniSettingService())->getWechatMiniSetting($merchant_id);
        return $result;
    }

    public function updateWechatMiniSetting(array $data)
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;
        $result = (new DeshangMiniSettingService())->updateWechatMiniSetting($merchant_id, $data);
        return $result;
    }

}