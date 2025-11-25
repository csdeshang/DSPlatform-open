<?php


namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\system\SysPlatformService;

/**
 * @OA\Tag(name="admin-api/system/SysPlatform", description="系统平台管理接口")
 */
class SysPlatform extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/system/platforms/list",
     *     summary="获取平台列表",
     *     tags={"admin-api/system/SysPlatform"},
     *     @OA\Parameter(
     *         name="scene",
     *         in="query",
     *         required=false,
     *         description="应用场景，system-系统级，store-店铺级",
     *         @OA\Schema(type="string", example="system", enum={"system", "store"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    // 获取平台列表
    public function getSysPlatformList(){

        $data = array(
            // 应用场景  system 系统级(比如聊天交友)   store 店铺级(基于店铺的拓展 mall food kms 店铺类型相关的)
            'scene' => input('param.scene'),
        );
        $result = (new SysPlatformService())->getSysPlatformList($data);
        return ds_json_success('操作成功',$result);
    }


    /**
     * @OA\Put(
     *     path="/adminapi/system/platforms/{id}",
     *     summary="更新平台状态",
     *     tags={"admin-api/system/SysPlatform"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="平台ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="平台状态信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="is_enable", type="integer", description="是否启用，1-启用，0-禁用", example=1)
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
     *     ),

     * )
     */
    // 更新平台状态
    public function updateSysPlatform($id){
        $data = array(
            // 是否启用
            'is_enable' => input('param.is_enable', ''),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysPlatformValidate.update');

        $result = (new SysPlatformService())->updateSysPlatform($id,$data);
        return ds_json_success('操作成功',$result);
    }
}