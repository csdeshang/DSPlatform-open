<?php
namespace app\adminapi\controller\distributor;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\distributor\DistributorLevelService;

/**
 * @OA\Tag(
 *     name="admin-api/distributor/DistributorLevel",
 *     description="分销商等级管理接口"
 * )
 */
class DistributorLevel extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/distributor/levels/list",
     *     summary="获取分销商等级列表",
     *     tags={"admin-api/distributor/DistributorLevel"},
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
    public function getDistributorLevelList(){

        $list = (new DistributorLevelService())->getDistributorLevelList();
        return ds_json_success('操作成功',$list);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/distributor/levels/{id}",
     *     summary="获取分销商等级详情",
     *     tags={"admin-api/distributor/DistributorLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分销商等级ID",
     *         required=true,
     *         @OA\Schema(type="integer")
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
    public function getDistributorLevelInfo($id){

        $info = (new DistributorLevelService())->getDistributorLevelInfo($id);
        return ds_json_success('操作成功',$info);
    }


    /**
     * @OA\Post(
     *     path="/adminapi/distributor/levels",
     *     summary="创建分销商等级",
     *     tags={"admin-api/distributor/DistributorLevel"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="分销商等级信息",
     *         @OA\JsonContent(
     *              required={"name", "sort"},
     *              @OA\Property(property="name", type="string", example="初级分销商", description="等级名称"),
     *              @OA\Property(property="sort", type="integer", example=1, description="排序"),
     *              @OA\Property(property="description", type="string", example="初级分销商等级", description="等级描述"),
     *              @OA\Property(property="base_self_ratio", type="number", format="decimal", example=0.10, description="基础自购比例"),
     *              @OA\Property(property="base_parent1_ratio", type="number", format="decimal", example=0.05, description="基础一级分成比例"),
     *              @OA\Property(property="base_parent2_ratio", type="number", format="decimal", example=0.02, description="基础二级分成比例"),
     *              @OA\Property(property="self_single_amount", type="number", format="decimal", example=1000.00, description="单笔自购金额条件"),
     *              @OA\Property(property="self_single_amount_is", type="integer", example=1, description="是否启用单笔自购金额条件"),
     *              @OA\Property(property="self_total_amount", type="number", format="decimal", example=10000.00, description="累计自购金额条件"),
     *              @OA\Property(property="self_total_amount_is", type="integer", example=1, description="是否启用累计自购金额条件"),
     *              @OA\Property(property="self_total_count", type="integer", example=10, description="累计自购次数条件"),
     *              @OA\Property(property="self_total_count_is", type="integer", example=1, description="是否启用累计自购次数条件"),
     *              @OA\Property(property="parent1_total_amount", type="number", format="decimal", example=5000.00, description="一级用户累计消费金额条件"),
     *              @OA\Property(property="parent1_total_amount_is", type="integer", example=1, description="是否启用一级用户累计消费金额条件"),
     *              @OA\Property(property="parent1_total_count", type="integer", example=5, description="一级用户累计消费次数条件"),
     *              @OA\Property(property="parent1_total_count_is", type="integer", example=1, description="是否启用一级用户累计消费次数条件"),
     *              @OA\Property(property="invite_count", type="integer", example=5, description="邀请人数条件"),
     *              @OA\Property(property="invite_count_is", type="integer", example=1, description="是否启用邀请人数条件")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *              @OA\Property(property="code", type="integer", example=10000),
     *              @OA\Property(property="msg", type="string", example="操作成功"),
     *              @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createDistributorLevel(){

        $data = array(
            'name' => input('param.name'),
            'sort' => input('param.sort'),
            'description' => input('param.description'),
            'base_self_ratio' => input('param.base_self_ratio'),
            'base_parent1_ratio' => input('param.base_parent1_ratio'),
            'base_parent2_ratio' => input('param.base_parent2_ratio'),
            'self_single_amount' => input('param.self_single_amount'),
            'self_single_amount_is' => input('param.self_single_amount_is'),
            'self_total_amount' => input('param.self_total_amount'),
            'self_total_amount_is' => input('param.self_total_amount_is'),
            'self_total_count' => input('param.self_total_count'),
            'self_total_count_is' => input('param.self_total_count_is'),
            'parent1_total_amount' => input('param.parent1_total_amount'),
            'parent1_total_amount_is' => input('param.parent1_total_amount_is'),
            'parent1_total_count' => input('param.parent1_total_count'),
            'parent1_total_count_is' => input('param.parent1_total_count_is'),
            'invite_count' => input('param.invite_count'),
            'invite_count_is' => input('param.invite_count_is'),
        );

        $this->validate($data, 'app\adminapi\controller\distributor\validate\DistributorLevelValidate.create');

        $info = (new DistributorLevelService())->createDistributorLevel($data);
        return ds_json_success('操作成功',$info);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/distributor/levels/{id}",
     *     summary="更新分销商等级",
     *     tags={"admin-api/distributor/DistributorLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分销商等级ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="更新分销商等级信息",
     *         @OA\JsonContent(
     *              required={"name", "sort"},
     *              @OA\Property(property="name", type="string", example="初级分销商", description="等级名称"),
     *              @OA\Property(property="sort", type="integer", example=1, description="排序"),
     *              @OA\Property(property="description", type="string", example="初级分销商等级", description="等级描述"),
     *              @OA\Property(property="base_self_ratio", type="number", format="decimal", example=0.10, description="基础自购比例"),
     *              @OA\Property(property="base_parent1_ratio", type="number", format="decimal", example=0.05, description="基础一级分成比例"),
     *              @OA\Property(property="base_parent2_ratio", type="number", format="decimal", example=0.02, description="基础二级分成比例"),
     *              @OA\Property(property="self_single_amount", type="number", format="decimal", example=1000.00, description="单笔自购金额条件"),
     *              @OA\Property(property="self_single_amount_is", type="integer", example=1, description="是否启用单笔自购金额条件"),
     *              @OA\Property(property="self_total_amount", type="number", format="decimal", example=10000.00, description="累计自购金额条件"),
     *              @OA\Property(property="self_total_amount_is", type="integer", example=1, description="是否启用累计自购金额条件"),
     *              @OA\Property(property="self_total_count", type="integer", example=10, description="累计自购次数条件"),
     *              @OA\Property(property="self_total_count_is", type="integer", example=1, description="是否启用累计自购次数条件"),
     *              @OA\Property(property="parent1_total_amount", type="number", format="decimal", example=5000.00, description="一级用户累计消费金额条件"),
     *              @OA\Property(property="parent1_total_amount_is", type="integer", example=1, description="是否启用一级用户累计消费金额条件"),
     *              @OA\Property(property="parent1_total_count", type="integer", example=5, description="一级用户累计消费次数条件"),
     *              @OA\Property(property="parent1_total_count_is", type="integer", example=1, description="是否启用一级用户累计消费次数条件"),
     *              @OA\Property(property="invite_count", type="integer", example=5, description="邀请人数条件"),
     *              @OA\Property(property="invite_count_is", type="integer", example=1, description="是否启用邀请人数条件")
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
    public function updateDistributorLevel($id){

        $data = array(
            'name' => input('param.name'),
            'sort' => input('param.sort'),
            'description' => input('param.description'),
            'base_self_ratio' => input('param.base_self_ratio'),
            'base_parent1_ratio' => input('param.base_parent1_ratio'),
            'base_parent2_ratio' => input('param.base_parent2_ratio'),
            'self_single_amount' => input('param.self_single_amount'),
            'self_single_amount_is' => input('param.self_single_amount_is'),
            'self_total_amount' => input('param.self_total_amount'),
            'self_total_amount_is' => input('param.self_total_amount_is'),
            'self_total_count' => input('param.self_total_count'),
            'self_total_count_is' => input('param.self_total_count_is'),
            'parent1_total_amount' => input('param.parent1_total_amount'),
            'parent1_total_amount_is' => input('param.parent1_total_amount_is'),
            'parent1_total_count' => input('param.parent1_total_count'),
            'parent1_total_count_is' => input('param.parent1_total_count_is'),
            'invite_count' => input('param.invite_count'),
            'invite_count_is' => input('param.invite_count_is'),
        );

        $this->validate($data, 'app\adminapi\controller\distributor\validate\DistributorLevelValidate.update');

        $info = (new DistributorLevelService())->updateDistributorLevel($id,$data);
        return ds_json_success('操作成功',$info);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/distributor/levels/{id}",
     *     summary="删除分销商等级",
     *     tags={"admin-api/distributor/DistributorLevel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="分销商等级ID",
     *         required=true,
     *         @OA\Schema(type="integer")
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
    public function deleteDistributorLevel($id){

        $info = (new DistributorLevelService())->deleteDistributorLevel($id);
        return ds_json_success('操作成功',$info);
    }

}