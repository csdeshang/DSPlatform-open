<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;

use app\deshang\service\wechat\DeshangOfficialSettingService;

class WechatOfficialSettingService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatOfficialSetting()
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;

        $result = (new DeshangOfficialSettingService())->getWechatOfficialSetting($merchant_id);
        return $result;
    }

    public function updateWechatOfficialSetting(array $data)
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;
        $result = (new DeshangOfficialSettingService())->updateWechatOfficialSetting($merchant_id, $data);
        return $result;
    }

}