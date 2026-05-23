<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\wechat\WechatAppSettingService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatAppSetting", description="微信移动应用设置管理接口")
 */
class WechatAppSetting extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/wechat/app/settings",
     *     summary="获取微信移动应用设置",
     *     tags={"admin-api/wechat/WechatAppSetting"},
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
    public function getWechatAppSetting(){


        $list = (new WechatAppSettingService())->getWechatAppSetting();
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Put(
     *     path="/adminapi/wechat/app/settings",
     *     summary="更新微信移动应用设置",
     *     tags={"admin-api/wechat/WechatAppSetting"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="微信移动应用配置信息",
     *         @OA\JsonContent(
     *             required={"app_id", "app_secret"},
     *             @OA\Property(property="app_id", type="string", example="wx1234567890abcdef", description="移动应用AppID"),
     *             @OA\Property(property="app_secret", type="string", example="abcdef1234567890", description="移动应用AppSecret")
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
    public function updateWechatAppSetting(){
        $data = array(
            'app_id' => input('param.app_id'),
            'app_secret' => input('param.app_secret'),
        );


        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatAppSettingValidate.update');

        (new WechatAppSettingService())->updateWechatAppSetting($data);

        return ds_json_success('操作成功');
    }

}
