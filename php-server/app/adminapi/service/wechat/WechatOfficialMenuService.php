<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;

use app\deshang\service\wechat\DeshangOfficialMenuService;

class WechatOfficialMenuService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取微信公众号菜单
     * @return array
     */
    public function getWechatOfficialMenu()
    {
        $merchant_id = 0;
        $menu = (new DeshangOfficialMenuService())->getWechatOfficialMenu($merchant_id);
        return $menu;
    }


    /**
     * 更新微信公众号菜单
     * @param array $data
     * @return array
     */
    public function updateWechatOfficialMenu(array $data)
    {
        $merchant_id = 0;
        $menu = (new DeshangOfficialMenuService())->updateWechatOfficialMenu($merchant_id, $data);
        return $menu;
    }


    /**
     * 发布微信公众号菜单
     * @param array $data
     * @return array
     */
    public function publishWechatOfficialMenu()
    {
        $merchant_id = 0;
        return (new DeshangOfficialMenuService())->publishWechatOfficialMenu($merchant_id);
    }
}