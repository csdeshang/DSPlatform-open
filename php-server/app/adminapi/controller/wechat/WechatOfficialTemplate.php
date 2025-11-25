<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\wechat\WechatOfficialTemplateService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatOfficialTemplate", description="微信公众号消息模板管理接口")
 */
class WechatOfficialTemplate extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/wechat/official/templates/list",
     *     summary="获取微信公众号模板列表",
     *     tags={"admin-api/wechat/WechatOfficialTemplate"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array")
     *         )
     *     )
     * )
     */
    public function getWechatOfficialTemplateList()
    {
        $result = (new WechatOfficialTemplateService())->getWechatOfficialTemplateList();
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/wechat/official/templates/sync",
     *     summary="同步微信公众号模板",
     *     tags={"admin-api/wechat/WechatOfficialTemplate"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="同步参数",
     *         @OA\JsonContent(
     *             @OA\Property(property="keys", type="array", @OA\Items(type="string"), description="模板keys数组")
     *         )
     *     ),
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
    public function syncWechatOfficialTemplate()
    {
        $data = [
            'keys' => input('param.keys', []),
        ];

        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatOfficialTemplateValidate.sync');

        $result = (new WechatOfficialTemplateService())->syncWechatOfficialTemplate($data['keys']);
        return ds_json_success('同步成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/wechat/official/templates/{key}",
     *     summary="删除微信公众号模板配置",
     *     tags={"admin-api/wechat/WechatOfficialTemplate"},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         required=true,
     *         description="模板key",
     *         @OA\Schema(type="string")
     *     ),
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
    public function deleteWechatOfficialTemplate($key = '')
    {
        $key = $key ?: input('param.key', '');

        $data = ['key' => $key];
        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatOfficialTemplateValidate.delete');

        $result = (new WechatOfficialTemplateService())->deleteWechatOfficialTemplate($key);
        return ds_json_success('删除成功', $result);
    }
}