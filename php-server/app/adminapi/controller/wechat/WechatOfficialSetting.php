<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\wechat\WechatOfficialSettingService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatOfficialSetting", description="微信公众号设置管理接口")
 */
class WechatOfficialSetting extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/wechat/official-setting/info",
     *     summary="获取微信公众号设置",
     *     tags={"admin-api/wechat/WechatOfficialSetting"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getWechatOfficialSetting(){


        $list = (new WechatOfficialSettingService())->getWechatOfficialSetting();
        return ds_json_success('操作成功',$list);

    }


    /**
     * @OA\Post(
     *     path="/adminapi/wechat/official-setting/update",
     *     summary="更新微信公众号设置",
     *     tags={"admin-api/wechat/WechatOfficialSetting"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="微信公众号配置信息",
     *         @OA\JsonContent(
     *             required={"app_id", "app_secret"},
     *             @OA\Property(property="app_id", type="string", example="wx1234567890abcdef", description="微信AppID"),
     *             @OA\Property(property="app_secret", type="string", example="abcdef1234567890", description="微信AppSecret"),
     *             @OA\Property(property="token", type="string", example="mytoken", description="微信Token"),
     *             @OA\Property(property="encoding_aes_key", type="string", example="abcdefghijklmnopqrstuvwxyz1234567890ABC", description="消息加解密密钥"),
     *             @OA\Property(property="encryption_type", type="string", example="1", description="加密类型")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateWechatOfficialSetting(){
        $data = array(
            'app_id' => input('param.app_id'),
            'app_secret' => input('param.app_secret'),
            'token' => input('param.token'),
            'encoding_aes_key' => input('param.encoding_aes_key'),
            'encryption_type' => input('param.encryption_type'),
        );



        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatOfficialSettingValidate.update');

        (new WechatOfficialSettingService())->updateWechatOfficialSetting($data);

        return ds_json_success('操作成功');
    }

}