<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\wechat\WechatWebSettingService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatWebSetting", description="微信网站应用设置管理接口")
 */
class WechatWebSetting extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/wechat/web/settings",
     *     summary="获取微信网站应用设置",
     *     tags={"admin-api/wechat/WechatWebSetting"},
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
    public function getWechatWebSetting(){


        $list = (new WechatWebSettingService())->getWechatWebSetting();
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Put(
     *     path="/adminapi/wechat/web/settings",
     *     summary="更新微信网站应用设置",
     *     tags={"admin-api/wechat/WechatWebSetting"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="微信网站应用配置信息",
     *         @OA\JsonContent(
     *             required={"app_id", "app_secret"},
     *             @OA\Property(property="app_id", type="string", example="wx1234567890abcdef", description="网站应用AppID"),
     *             @OA\Property(property="app_secret", type="string", example="abcdef1234567890", description="网站应用AppSecret")
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
    public function updateWechatWebSetting(){
        $data = array(
            'app_id' => input('param.app_id'),
            'app_secret' => input('param.app_secret'),
        );


        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatWebSettingValidate.update');

        (new WechatWebSettingService())->updateWechatWebSetting($data);

        return ds_json_success('操作成功');
    }

}
