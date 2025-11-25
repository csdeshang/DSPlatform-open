<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\wechat\WechatMiniSettingService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatMiniSetting", description="微信小程序设置管理接口")
 */
class WechatMiniSetting extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/wechat/mini/settings",
     *     summary="获取微信小程序设置",
     *     tags={"admin-api/wechat/WechatMiniSetting"},
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
    public function getWechatMiniSetting(){


        $list = (new WechatMiniSettingService())->getWechatMiniSetting();
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Put(
     *     path="/adminapi/wechat/mini/settings",
     *     summary="更新微信小程序设置",
     *     tags={"admin-api/wechat/WechatMiniSetting"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="微信小程序配置信息",
     *         @OA\JsonContent(
     *             required={"app_id", "app_secret"},
     *             @OA\Property(property="app_id", type="string", example="wx1234567890abcdef", description="小程序AppID"),
     *             @OA\Property(property="app_secret", type="string", example="abcdef1234567890", description="小程序AppSecret")
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
    public function updateWechatMiniSetting(){
        $data = array(
            'app_id' => input('param.app_id'),
            'app_secret' => input('param.app_secret'),
        );


        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatMiniSettingValidate.update');

        (new WechatMiniSettingService())->updateWechatMiniSetting($data);

        return ds_json_success('操作成功');
    }

}