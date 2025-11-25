<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\wechat\WechatOfficialMenuService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatOfficialMenu", description="微信公众号菜单管理接口")
 */
class WechatOfficialMenu extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/wechat/official/menus",
     *     summary="获取微信公众号菜单",
     *     tags={"admin-api/wechat/WechatOfficialMenu"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getWechatOfficialMenu(){
        $list = (new WechatOfficialMenuService())->getWechatOfficialMenu();
        return ds_json_success('操作成功',$list);

    }

    /**
     * @OA\Put(
     *     path="/adminapi/wechat/official/menus",
     *     summary="保存微信公众号菜单",
     *     tags={"admin-api/wechat/WechatOfficialMenu"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="菜单配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="menu_data", type="object", description="菜单数据")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateWechatOfficialMenu(){
        $data = input('param.');
        (new WechatOfficialMenuService())->updateWechatOfficialMenu($data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/wechat/official/menus/publish",
     *     summary="发布微信公众号菜单",
     *     tags={"admin-api/wechat/WechatOfficialMenu"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function publishWechatOfficialMenu(){
        $result = (new WechatOfficialMenuService())->publishWechatOfficialMenu();
        return ds_json_success('操作成功',$result);
    }

}