<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;
use app\deshang\service\wechat\DeshangAppSettingService;

class WechatAppSettingService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatAppSetting()
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;

        $result = (new DeshangAppSettingService())->getWechatAppSetting($merchant_id);
        return $result;
    }

    public function updateWechatAppSetting(array $data)
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;
        $result = (new DeshangAppSettingService())->updateWechatAppSetting($merchant_id, $data);
        return $result;
    }

}
