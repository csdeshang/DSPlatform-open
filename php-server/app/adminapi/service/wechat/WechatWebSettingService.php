<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;

use app\deshang\service\wechat\DeshangWebSettingService;

class WechatWebSettingService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getWechatWebSetting()
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;

        $result = (new DeshangWebSettingService())->getWechatWebSetting($merchant_id);
        return $result;
    }

    public function updateWechatWebSetting(array $data)
    {
        // 默认系统配置  merchant_id = 0
        $merchant_id = 0;
        $result = (new DeshangWebSettingService())->updateWechatWebSetting($merchant_id, $data);
        return $result;
    }

}
