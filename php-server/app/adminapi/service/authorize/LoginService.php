<?php


namespace app\adminapi\service\authorize;

use app\deshang\base\BaseService;
use app\deshang\exceptions\CommonException;

use app\common\dao\admin\AdminDao;

use app\deshang\utils\JwtToken;

class LoginService extends BaseService
{


    public function login(string $username, string $password)
    {

        $admin_info = (new AdminDao())->getAdminInfo([['username', '=', $username]]);



        if (empty($admin_info)) {
            throw new CommonException('用户不存在');
        }


        if (!check_password($password, $admin_info['password'])) {
            throw new CommonException('密码错误');
        }


        // 更新登录信息
        $update_data = [];
        $update_data['login_time'] = time();
        $update_data['login_ip'] = request()->ip();
        $update_data['login_num'] = $admin_info['login_num'] + 1;

        (new AdminDao())->updateAdmin([['id', '=', $admin_info['id']]], $update_data);


        $result = $this->generateAdminToken($admin_info);


        return $result;
    }



    public function generateAdminToken($adminInfo)
    {
        //生成 access_token
        $access_payload_data = array(
            'role' => 'admin',
            'user_id' => $adminInfo['id'],
            'username' => $adminInfo['username'],
            'admin_is_super' => $adminInfo['is_super'],
        );

        // 有效期 1 小时
        $access_token = (new JwtToken())->generateToken($access_payload_data, 3600);

        //生成 refresh_token   
        $refresh_payload_data = array(
            'role' => 'admin',
            'user_id' => $adminInfo['id'],
        );
        // 有效期 7 天
        $refresh_token = (new JwtToken())->generateToken($refresh_payload_data, 3600 * 24 * 7);



        $data = [
            'access_token' => $access_token['token'],
            'access_expires_time' => $access_token['exp'],
            'refresh_token' => $refresh_token['token'],
            'refresh_expires_time' => $refresh_token['exp'],
            'userinfo' => [
                'role' => 'admin',
                'user_id' => $adminInfo['id'],
                'username' => $adminInfo['username'],
            ]
        ];
        return $data;
    }



    /**

     * 刷新token
     */
    public function refreshAdminToken(string $refresh_token)
    {

        // 验证 refresh_token
        $token_info = (new JwtToken())->validateToken($refresh_token);

        if ($token_info['role'] != 'admin') {
            throw new CommonException('refresh_token 无效');
        }


        $user_id = $token_info['user_id'] ?? 0;

        if (!$user_id) {
            throw new CommonException('refresh_token 无效');
        }

        $admin_info = (new AdminDao())->getAdminInfo([
            ['id', '=', $user_id],
            ['is_deleted', '=', 0]
        ]);
        if (empty($admin_info)) {
            throw new CommonException('用户不存在');
        }


        $data = $this->generateAdminToken($admin_info);
        return $data;
    }
}
