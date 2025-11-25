<?php

namespace app\adminapi\controller\user;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\user\UserService;

/**
 * @OA\Tag(
 *     name="admin-api/user/User",
 *     description="会员管理接口"
 * )
 */

class User extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/user/users/pages",
     *     summary="获取会员分页列表",
     *     tags={"admin-api/user/User"},
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         required=false,
     *         description="手机号",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="inviter_id",
     *         in="query",
     *         required=false,
     *         description="邀请人ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_enabled",
     *         in="query",
     *         required=false,
     *         description="是否启用（0:禁用 1:启用）",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_min",
     *         in="query",
     *         required=false,
     *         description="最小可用金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_max",
     *         in="query",
     *         required=false,
     *         description="最大可用金额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_in_min",
     *         in="query",
     *         required=false,
     *         description="最小总收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_in_max",
     *         in="query",
     *         required=false,
     *         description="最大总收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_out_min",
     *         in="query",
     *         required=false,
     *         description="最小总支出",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="balance_out_max",
     *         in="query",
     *         required=false,
     *         description="最大总支出",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_min",
     *         in="query",
     *         required=false,
     *         description="最小分销商余额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_max",
     *         in="query",
     *         required=false,
     *         description="最大分销商余额",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_in_min",
     *         in="query",
     *         required=false,
     *         description="最小分销商收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_in_max",
     *         in="query",
     *         required=false,
     *         description="最大分销商收入",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_out_min",
     *         in="query",
     *         required=false,
     *         description="最小分销商支出",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="distributor_balance_out_max",
     *         in="query",
     *         required=false,
     *         description="最大分销商支出",
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
    public function getUserPages()
    {
        $data = array(
            'username' => input('param.username',''),
            'mobile' => input('param.mobile',''),
            'inviter_id' => input('param.inviter_id',''),
            'is_enabled' => input('param.is_enabled',''),
            'balance_min' => input('param.balance_min',''),
            'balance_max' => input('param.balance_max',''),
            'balance_in_min' => input('param.balance_in_min',''),
            'balance_in_max' => input('param.balance_in_max',''),
            'balance_out_min' => input('param.balance_out_min',''),
            'balance_out_max' => input('param.balance_out_max',''),
            'distributor_balance_min' => input('param.distributor_balance_min',''),
            'distributor_balance_max' => input('param.distributor_balance_max',''),
            'distributor_balance_in_min' => input('param.distributor_balance_in_min',''),
            'distributor_balance_in_max' => input('param.distributor_balance_in_max',''),
            'distributor_balance_out_min' => input('param.distributor_balance_out_min',''),
            'distributor_balance_out_max' => input('param.distributor_balance_out_max',''),
            'is_distributor' => input('param.is_distributor',''),
        );
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserValidate.pages');
        
        $result = (new UserService())->getUserPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/user/users/{id}",
     *     summary="获取会员详细信息",
     *     tags={"admin-api/user/User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="会员ID",
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
     *     ),
     *     @OA\Response(response=400, description="会员不存在")
     * )
     */
    public function getUserInfo($id)
    {
        $data = array('id' => $id);
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserValidate.info');
        
        $result = (new UserService())->getUserInfo((int)$id);
        if (empty($result)) {
            return ds_json_error('会员不存在');
        }
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/user/users",
     *     summary="添加会员",
     *     tags={"admin-api/user/User"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="会员添加所需信息",
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="JohnDoe"),
     *             @OA\Property(property="password", type="string", example="secret123"),
     *             @OA\Property(property="confirm_password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="添加成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="添加成功")
     *         )
     *     )
     * )
     */
    public function createUser()
    {
        $data = array(
            'username' => input('param.username',''),
            'password' => input('param.password',''),
            'confirm_password' => input('param.confirm_password',''),
        );
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserValidate.create');
        
        $result = (new UserService())->createUser($data);
        return ds_json_success('添加成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/user/users/{id}",
     *     summary="修改会员信息",
     *     tags={"admin-api/user/User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="会员修改所需信息",
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="password", type="string", example="newpassword"),
     *             @OA\Property(property="pay_password", type="string", example="payPass123"),
     *             @OA\Property(property="nickname", type="string", example="Johnny"),
     *             @OA\Property(property="sex", type="string", example="male"),
     *             @OA\Property(property="birthday", type="integer", example=1609459200, description="生日时间戳"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="email_bind", type="boolean", example=true),
     *             @OA\Property(property="mobile", type="string", example="13800138000"),
     *             @OA\Property(property="mobile_bind", type="boolean", example=true),
     *             @OA\Property(property="qq", type="string", example="123456789"),
     *             @OA\Property(property="idcard_name", type="string", example="John Doe"),
     *             @OA\Property(property="is_enabled", type="integer", example=1, description="会员状态")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="修改成功")
     *         )
     *     )
     * )
     */
    public function updateUser($id)
    {
        $data = array(
            'password' => input('param.password'),
            'pay_password' => input('param.pay_password'),
            'nickname' => input('param.nickname'),
            'sex' => input('param.sex'),
            'birthday' => strtotime(input('param.birthday')),
            'email' => input('param.email') ?: null,
            'email_bind' => (int) input('param.email_bind'),
            'mobile' => input('param.mobile') ?: null,
            'mobile_bind' => (int) input('param.mobile_bind'),
            'qq' => input('param.qq'),
            'idcard_name' => input('param.idcard_name'),
            'is_enabled' => (int) input('param.is_enabled'),
        );

        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserValidate.update');
        
        $result = (new UserService())->updateUser((int)$id, $data);
        return ds_json_success('修改成功', $result);
    }


    /**
     * @OA\Get(
     *     path="/adminapi/user/users/relation/list",
     *     summary="获取会员推广关系列表",
     *     tags={"admin-api/user/User"},
     *     @OA\Parameter(
     *         name="inviter_id",
     *         in="query",
     *         description="推广人ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="修改成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="修改成功")
     *         )
     *     )
     * )
     */
    public function getUserRelationList(){
        $data = array(
            'inviter_id' => input('param.inviter_id'),
        );
        
        // 验证参数
        $this->validate($data, 'app\adminapi\controller\user\validate\UserValidate.relation');
        
        $result = (new UserService())->getUserRelationList($data);
        return ds_json_success('操作成功', $result);
    }


    

}
