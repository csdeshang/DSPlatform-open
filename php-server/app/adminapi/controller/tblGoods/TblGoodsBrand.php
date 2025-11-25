<?php

namespace app\adminapi\controller\tblGoods;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\goods\TblGoodsBrandService;

/**
 * @OA\Tag(name="admin-api/tblGoods/TblGoodsBrand", description="商品品牌管理相关接口")
 */
class TblGoodsBrand extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/brands/tree",
     *     tags={"admin-api/tblGoods/TblGoodsBrand"},
     *     summary="获取商品品牌树组结构",
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取商品品牌分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblGoodsBrandTree()
    {
        $data = array(
            'platform' => input('param.platform'),
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsBrandValidate.tree');
        

        $result = (new TblGoodsBrandService())->getTblGoodsBrandList($data);
        $result = linearToTree($result,'id','pid');

        return ds_json_success('操作成功', $result);
    }

    
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-goods/brands/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsBrand"},
     *     summary="获取商品品牌信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", example="平台名称")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取商品品牌信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="品牌名称"),
     *                 @OA\Property(property="description", type="string", example="品牌描述"),
     *                 @OA\Property(property="logo", type="string", example="logo链接"),
     *                 @OA\Property(property="sort", type="integer", example=1),
     *                 @OA\Property(property="is_recommend", type="boolean", example=true),
     *                 @OA\Property(property="is_show", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="品牌未找到"
     *     )
     * )
     */
    public function getTblGoodsBrandInfo($id)
    {
        $data = array(
            'id' => (int) $id,
        );  
        $result = (new TblGoodsBrandService())->getTblGoodsBrandInfo($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-goods/brands",
     *     tags={"admin-api/tblGoods/TblGoodsBrand"},
     *     summary="创建新商品品牌",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"platform", "name"},
     *             @OA\Property(property="platform", type="string", example="平台名称"),
     *             @OA\Property(property="pid", type="integer", example=0, description="父级品牌ID"),
     *             @OA\Property(property="name", type="string", example="新品牌名称"),
     *             @OA\Property(property="description", type="string", example="品牌描述"),
     *             @OA\Property(property="logo", type="string", example="logo链接"),
     *             @OA\Property(property="sort", type="integer", example=1),
     *             @OA\Property(property="is_recommend", type="boolean", example=true),
     *             @OA\Property(property="is_show", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="成功创建新商品品牌",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function createTblGoodsBrand()
    {
        $data = array(
            'platform' => input('param.platform'),
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'description' => input('param.description'),
            'logo' => input('param.logo'),
            'sort' => input('param.sort'),
            'is_recommend' => input('param.is_recommend'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsBrandValidate.create');
        $result = (new TblGoodsBrandService())->createTblGoodsBrand($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/tbl-goods/brands/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsBrand"},
     *     summary="更新商品品牌信息",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="更新后的品牌名称", description="品牌名称"),
     *             @OA\Property(property="pid", type="integer", example=0, description="父级品牌ID"),
     *             @OA\Property(property="description", type="string", example="更新后的品牌描述", description="品牌描述"),
     *             @OA\Property(property="logo", type="string", example="更新后的logo链接", description="logo链接"),
     *             @OA\Property(property="sort", type="integer", example=1, description="排序"),
     *             @OA\Property(property="is_recommend", type="boolean", example=true, description="是否推荐"),
     *             @OA\Property(property="is_show", type="boolean", example=true, description="是否显示")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功更新商品品牌信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateTblGoodsBrand($id)
    {
        $data = array(
            'id' => (int) $id,
            'pid' => input('param.pid'),
            'name' => input('param.name'),
            'description' => input('param.description'),
            'logo' => input('param.logo'),
            'sort' => input('param.sort'),
            'is_recommend' => input('param.is_recommend'),
            'is_show' => input('param.is_show'),
        );

        $this->validate($data, 'app\adminapi\controller\tblGoods\validate\TblGoodsBrandValidate.update');

        $result = (new TblGoodsBrandService())->updateTblGoodsBrand($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/tbl-goods/brands/{id}",
     *     tags={"admin-api/tblGoods/TblGoodsBrand"},
     *     summary="删除商品品牌",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功删除商品品牌",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="品牌未找到"
     *     )
     * )
     */
    public function deleteTblGoodsBrand($id)
    {
        $result = (new TblGoodsBrandService())->deleteTblGoodsBrand((int) $id);
        if ($result) {
            return ds_json_success('操作成功', $result);
        } else {
            return ds_json_error('操作失败', $result);
        }
    }
}
