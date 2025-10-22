<?php

namespace app\adminapi\controller\tblGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\goods\TblGoodsService;

/**
 * @OA\Tag(name="admin-api/tblGoods/TblGoods", description="商品管理接口")
 */
class TblGoods extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/pages",
     *     summary="获取商品分页列表",
     *     tags={"admin-api/tblGoods/TblGoods"},
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=false,
     *         description="平台类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tab_selected",
     *         in="query",
     *         required=false,
     *         description="选项卡",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="goods_name",
     *         in="query",
     *         required=false,
     *         description="商品名称",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_distributor_goods",
     *         in="query",
     *         required=false,
     *         description="是否分销商品",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="store_name",
     *         in="query",
     *         required=false,
     *         description="店铺名称",
     *         @OA\Schema(type="string")
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
    public function getTblGoodsPages()
    {
        $data = array(
            'platform' => input('param.platform'),
            'tab_selected' => input('param.tab_selected'),
            'goods_name' => input('param.goods_name'),
            'is_distributor_goods' => input('param.is_distributor_goods'),
            'store_name' => input('param.store_name'),
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsValidate.pages');

        $result = (new TblGoodsService())->getTblGoodsPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/info/{id}",
     *     summary="获取商品详情",
     *     tags={"admin-api/tblGoods/TblGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="商品ID",
     *         @OA\Schema(type="integer")
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
    public function getTblGoodsInfo($id)
    {
        $data = array(
            'id' => $id,
        );

        $result = (new TblGoodsService())->getTblGoodsInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-goods/update-sys-status/{id}",
     *     summary="更新商品系统状态",
     *     tags={"admin-api/tblGoods/TblGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="商品ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="系统状态信息",
     *         @OA\JsonContent(
     *             required={"sys_status"},
     *             @OA\Property(property="sys_status", type="integer", example=1, description="系统状态"),
     *             @OA\Property(property="sys_status_reason", type="string", example="审核通过", description="状态原因")
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
    public function updateTblGoodsSysStatus($id)
    {
        $data = array(
            'sys_status' => input('param.sys_status'),
            'sys_status_reason' => input('param.sys_status_reason'),
        );

        $result = (new TblGoodsService())->updateTblGoodsSysStatus($id, $data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-goods/update-sys-recommend/{id}",
     *     summary="更新商品系统推荐状态",
     *     tags={"admin-api/tblGoods/TblGoods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="商品ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="推荐状态信息",
     *         @OA\JsonContent(
     *             required={"sys_recommend_status"},
     *             @OA\Property(property="sys_recommend_status", type="integer", example=1, description="系统推荐状态")
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
    public function updateSysRecommend($id)
    {
        $data = array(
            'sys_recommend_status' => input('param.sys_recommend_status'),
        );

        $result = (new TblGoodsService())->updateSysRecommend($id, $data);
        return ds_json_success('操作成功', $result);
    }

}