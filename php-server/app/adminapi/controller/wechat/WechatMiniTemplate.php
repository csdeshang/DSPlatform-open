<?php

namespace app\adminapi\controller\wechat;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\wechat\WechatMiniTemplateService;

/**
 * @OA\Tag(name="admin-api/wechat/WechatMiniTemplate", description="微信小程序消息模板管理接口")
 */
class WechatMiniTemplate extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/wechat/mini/template/list",
     *     summary="获取微信小程序模板列表",
     *     tags={"admin-api/wechat/WechatMiniTemplate"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array")
     *         )
     *     )
     * )
     */
    public function getWechatMiniTemplateList()
    {
        $result = (new WechatMiniTemplateService())->getWechatMiniTemplateList();
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/wechat/mini/template/sync",
     *     summary="同步微信小程序模板",
     *     tags={"admin-api/wechat/WechatMiniTemplate"},
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
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function syncWechatMiniTemplate()
    {
        $data = [
            'keys' => input('param.keys', []),
        ];

        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatMiniTemplateValidate.sync');

        $result = (new WechatMiniTemplateService())->syncWechatMiniTemplate($data['keys']);
        return ds_json_success('同步成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/wechat/mini/template/delete",
     *     summary="删除微信小程序模板配置",
     *     tags={"admin-api/wechat/WechatMiniTemplate"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="删除参数",
     *         @OA\JsonContent(
     *             @OA\Property(property="keys", type="array", @OA\Items(type="string"), description="模板keys数组")
     *         )
     *     ),
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
    public function deleteWechatMiniTemplate()
    {
        $key = input('param.key', '');

        $data = ['key' => $key];
        $this->validate($data, 'app\adminapi\controller\wechat\validate\WechatMiniTemplateValidate.delete');

        $result = (new WechatMiniTemplateService())->deleteWechatMiniTemplate($key);
        return ds_json_success('删除成功', $result);
    }
}