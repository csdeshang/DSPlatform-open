<?php

namespace app\adminapi\controller\system;

use app\adminapi\service\system\SysAreaService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/system/SysArea", description="地区管理相关接口")
 */
class SysArea extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/area/list",
     *     tags={"admin-api/system/SysArea"},
     *     summary="获取地区列表",
     *     @OA\Parameter(
     *         name="deep",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")

     *     ),
     *     @OA\Parameter(
     *         name="pid",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取地区列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysAreaList()
    {
        $data = array(
            'deep' => input('param.deep'),
            'pid' => input('param.pid'),
        );
        $result = (new SysAreaService())->getSysAreaList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/area/options",
     *     tags={"admin-api/system/SysArea"},
     *     summary="获取地区选项",
     *     @OA\Parameter(
     *         name="deep",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")

     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取地区选项",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysAreaOptions()
    {
        $data = array(
            'deep' => input('param.deep'),
        );
        $result = (new SysAreaService())->getSysAreaList($data);
        $result = linearToTree($result, 'id', 'pid');
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/area/{id}",
     *     tags={"admin-api/system/SysArea"},
     *     summary="获取地区信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取地区信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="地区未找到"
     *     )
     * )
     */
    public function getSysAreaInfo($id)
    {

        $data = array(
            'id' => (int) $id,
        );
        $this->validate($data, 'app\adminapi\controller\system\validate\SysAreaValidate.info');


        $result = (new SysAreaService())->getSysAreaInfo($data['id']);
        return ds_json_success('操作成功', $result);

    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/area",
     *     tags={"admin-api/system/SysArea"},
     *     summary="创建新地区",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pid", "name"},
     *             @OA\Property(property="pid", type="integer", example=0),

     *             @OA\Property(property="name", type="string", example="新地区名称"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *             @OA\Property(property="longitude", type="string", example="123.456"),
     *             @OA\Property(property="latitude", type="string", example="78.910"),
     *             @OA\Property(property="is_show", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功创建新地区",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createSysArea()
    {
        $data = array(
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'sort' => input('param.sort'),
            'longitude' => input('param.longitude'),
            'latitude' => input('param.latitude'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysAreaValidate.create');



        $list = (new SysAreaService())->createSysArea($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/area/{id}",
     *     tags={"admin-api/system/SysArea"},
     *     summary="更新地区信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")

     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", example=0),
     *             @OA\Property(property="name", type="string", example="更新后的地区名称"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *             @OA\Property(property="longitude", type="string", example="123.456"),
     *             @OA\Property(property="latitude", type="string", example="78.910"),
     *             @OA\Property(property="is_show", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功更新地区信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateSysArea($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'sort' => input('param.sort'),
            'longitude' => input('param.longitude'),
            'latitude' => input('param.latitude'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysAreaValidate.update');


        $list = (new SysAreaService())->updateSysArea($data);
        return ds_json_success('操作成功', $list);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/system/area/{id}",
     *     tags={"admin-api/system/SysArea"},
     *     summary="删除地区",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除地区",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="地区未找到"
     *     )
     * )
     */
    public function deleteSysArea($id)
    {
        $result = (new SysAreaService())->deleteSysArea((int) $id);
        return ds_json_success('操作成功', $result);
    }
}
