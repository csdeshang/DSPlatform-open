<?php
namespace app\adminapi\controller\tblStore;
use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\store\TblStoreAuthUserService;

/**
 * @OA\Tag(name="admin-api/tblStore/TblStoreAuthUser", description="店铺授权用户管理接口")
 */
class TblStoreAuthUser extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/auth-user/list",
     *     summary="获取店铺授权用户列表",
     *     tags={"admin-api/tblStore/TblStoreAuthUser"},
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         required=false,
     *         description="店铺ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getTblStoreAuthUserList(){

        $data = array(
            'store_id' => input('param.store_id'),
            'user_id' => input('param.user_id'),
        );

        $result = (new TblStoreAuthUserService())->getTblStoreAuthUserList($data);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/tbl-store/auth-user/create",
     *     summary="添加店铺授权用户",
     *     tags={"admin-api/tblStore/TblStoreAuthUser"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="授权用户信息",
     *         @OA\JsonContent(
     *             required={"store_id", "user_id"},
     *             @OA\Property(property="store_id", type="integer", example=1, description="店铺ID"),
     *             @OA\Property(property="user_id", type="integer", example=1, description="用户ID")
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
    public function createTblStoreAuthUser(){
        $data = array(
            'store_id' => input('param.store_id'),
            'user_id' => input('param.user_id'),
        );
        
        $result = (new TblStoreAuthUserService())->createTblStoreAuthUser($data);
        return ds_json_success('操作成功',$result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/tbl-store/auth-user/delete",
     *     summary="删除店铺授权用户",
     *     tags={"admin-api/tblStore/TblStoreAuthUser"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="删除授权信息",
     *         @OA\JsonContent(
     *             required={"store_id", "user_id"},
     *             @OA\Property(property="store_id", type="integer", example=1, description="店铺ID"),
     *             @OA\Property(property="user_id", type="integer", example=1, description="用户ID")
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
    public function deleteTblStoreAuthUser(){
        $data = array(
            'store_id' => (int)input('param.store_id'),
            'user_id' => (int)input('param.user_id'),
        );
        $result = (new TblStoreAuthUserService())->deleteTblStoreAuthUser($data);
        
        return ds_json_success('操作成功',$result);
    }
    



}