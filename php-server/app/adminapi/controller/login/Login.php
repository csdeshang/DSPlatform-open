<?php

namespace app\adminapi\controller\login;

use app\deshang\base\controller\BaseAdminController;
use app\deshang\utils\TokenCache;
use app\deshang\utils\JwtToken;
use app\adminapi\service\authorize\LoginService;

/**
 * @OA\Tag(
 *     name="admin-api/login/Login",
 *     description="登录和认证相关接口"
 * )
 */

class Login extends BaseAdminController
{

    /**
     * @OA\Post(
     *     path="/adminapi/login/login",
     *     summary="管理员登录接口",
     *     tags={"admin-api/login/Login"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="登录所需的参数",
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="登录成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object",
     *                 example={"token": "your_jwt_token", "user_info": {"id": 1, "username": "admin"}}
     *             )
     *         )
     *     )
     * )
     */
    public function login()
    {
        $data = array(
            'username' => input('param.username'),
            'password' => input('param.password'),
        );
        
        // 使用验证器进行验证
        $this->validate($data, 'app\adminapi\controller\login\validate\Login.login');


        $LoginService = new LoginService();
        $result = $LoginService->login($data['username'], $data['password']);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/login/refresh_token",
     *     summary="刷新 Token 接口",
     *     tags={"admin-api/login/Login"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="刷新 token 的请求数据",
     *         @OA\JsonContent(
     *             required={"refresh_token"},
     *             @OA\Property(property="refresh_token", type="string", example="your_refresh_token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token 刷新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object",
     *                 example={"token": "new_jwt_token"}
     *             )
     *         )
     *     )
     * )
     */
    public function refreshToken()
    {
        $refresh_token = input('param.refresh_token');
        $LoginService = new LoginService();
        $result = $LoginService->refreshAdminToken($refresh_token);

        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/login/logout",
     *     summary="管理员退出登录接口",
     *     tags={"admin-api/login/Login"},
     *     @OA\Response(
     *         response=200,
     *         description="退出成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="退出成功")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        // 获取refresh_token参数
        $refresh_token = input('param.refresh_token');
        
        // 退出登录处理：使该管理员在所有设备上的所有Token失效 防止账号被盗用后其他设备仍可访问
        // 这种"全平台退出"机制被微信、QQ、支付宝、淘宝等主流平台采用  
        if ($refresh_token) {
            // 验证refresh_token的有效性
            $refresh_token_info = (new JwtToken())->validateToken($refresh_token);
            if ($refresh_token_info && isset($refresh_token_info['role']) && isset($refresh_token_info['user_id'])) {
                (new TokenCache())->invalidateToken($refresh_token_info['role'], $refresh_token_info['user_id']);
            }
        }
        
        $data = array(
            'code' => 10000,
            'msg' => '退出成功'
        );
        return json($data);
    }
}
