<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\system\SysAgreementService;

/**
 * @OA\Tag(name="admin-api/system/SysAgreement", description="系统协议管理接口")
 */
// 协议
class SysAgreement extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/agreements/list",
     *     summary="获取系统协议列表",
     *     tags={"admin-api/system/SysAgreement"},
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=false,
     *         description="协议代码",
     *         @OA\Schema(type="string", example="user_agreement")
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
    // 获取协议列表
    public function getSysAgreementList()
    {

        $data = array(
            'code' => input('param.code'),
        );

        $result = (new SysAgreementService())->getSysAgreementList($data);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/agreements/{id}",
     *     summary="获取系统协议详情",
     *     tags={"admin-api/system/SysAgreement"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="协议ID",
     *         @OA\Schema(type="integer", example=1)
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
    // 获取短信日志详情
    public function getSysAgreementInfo($id)
    {
        $result = (new SysAgreementService())->getSysAgreementInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/agreements/{id}",
     *     summary="更新系统协议",
     *     tags={"admin-api/system/SysAgreement"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="协议ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="协议信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", description="协议标题", example="用户协议"),
     *             @OA\Property(property="content", type="string", description="协议内容", example="协议内容详情"),
     *             @OA\Property(property="sort", type="integer", description="排序", example=255),
     *             @OA\Property(property="is_show", type="integer", description="是否显示", example=1)
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
    // 更新协议
    public function updateSysAgreement($id)
    {
        $data = array(
            'title' => input('param.title'),
            'content' => input('param.content'),
            'sort' => input('param.sort'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysAgreementValidate.update');

        $result = (new SysAgreementService())->updateSysAgreement((int)$id, $data);
        return ds_json_success('操作成功', $result);
    }
    
}
