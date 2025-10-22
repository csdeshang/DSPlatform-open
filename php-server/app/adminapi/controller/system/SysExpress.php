<?php

namespace app\adminapi\controller\system;

use app\adminapi\service\system\SysExpressService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/system/SysExpress", description="物流管理相关接口")
 */
class SysExpress extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/express/list",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="获取物流列表",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取物流列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysExpressPages()
    {
        $data = array(
            'keyword' => input('param.keyword'),
        );
        $result = (new SysExpressService())->getSysExpressPages($data);
        return ds_json_success('操作成功', $result);
    }


    /**
     * @OA\Get(
     *     path="/adminapi/system/express/list",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="获取物流列表（不分页）",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取物流列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysExpressList()
    {
        $data = array(
            'keyword' => input('param.keyword'),
        );
        $result = (new SysExpressService())->getSysExpressList($data);
        return ds_json_success('操作成功', $result);
    }




    /**
     * @OA\Get(
     *     path="/adminapi/system/express/{id}",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="获取物流信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取物流信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="物流未找到"
     *     )
     * )
     */
    public function getSysExpressInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );
        $this->validate($data, 'app\adminapi\controller\system\validate\SysExpressValidate.info');

        $result = (new SysExpressService())->getSysExpressInfo($data['id']);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/express",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="创建新物流",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "code"},
     *             @OA\Property(property="name", type="string", example="新物流名称"),
     *             @OA\Property(property="code", type="string", example="express_code"),
     *             @OA\Property(property="logo", type="string", example="/uploads/express/logo.png"),
     *             @OA\Property(property="url", type="string", example="https://www.example.com"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *             @OA\Property(property="is_show", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功创建新物流",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createSysExpress()
    {
        $data = array(
            'name' => input('param.name'),
            'code' => input('param.code'),
            'kdniao_code' => input('param.kdniao_code'),
            'kd100_code' => input('param.kd100_code'),
            'logo' => input('param.logo'),
            'url' => input('param.url'),
            'sort' => input('param.sort'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysExpressValidate.create');

        $list = (new SysExpressService())->createSysExpress($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/express/{id}",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="更新物流信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "code"},
     *             @OA\Property(property="name", type="string", example="更新后的物流名称"),
     *             @OA\Property(property="code", type="string", example="express_code"),
     *             @OA\Property(property="logo", type="string", example="/uploads/express/logo.png"),
     *             @OA\Property(property="url", type="string", example="https://www.example.com"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *             @OA\Property(property="is_show", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功更新物流信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateSysExpress($id)
    {
        $data = array(
            'id' => (int) $id,
            'name' => input('param.name'),
            'code' => input('param.code'),
            'kdniao_code' => input('param.kdniao_code'),
            'kd100_code' => input('param.kd100_code'),
            'logo' => input('param.logo'),
            'url' => input('param.url'),
            'sort' => input('param.sort'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysExpressValidate.update');

        $list = (new SysExpressService())->updateSysExpress($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/system/express/{id}",
     *     tags={"admin-api/system/SysExpress"},
     *     summary="删除物流",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除物流",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="物流未找到"
     *     )
     * )
     */
    public function deleteSysExpress($id)
    {
        $result = (new SysExpressService())->deleteSysExpress((int) $id);
        return ds_json_success('操作成功', $result);
    }
}
