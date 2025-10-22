<?php


namespace app\adminapi\service\admin;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\TokenCache;

use app\common\dao\admin\AdminDao;
use app\common\dao\admin\AdminRoleDao;
use app\common\dao\admin\AdminMenuDao;



class CurrentAdminService extends BaseAdminService
{

    /**
     * 获取当前用户可用的apiurl
     */
    public function getAuthorizeApiurlList()
    {

        $admin_info = (new AdminDao())->getAdminInfoById($this->user_id);
        $role_id = $admin_info['role_id'];

        // 获取角色信息
        $role_info = (new AdminRoleDao())->getAdminRoleInfo([['id', '=', $role_id]]);
        $rules = $role_info['rules'];

        // 获取apiurl 列表
        $condition = array();
        $condition[] = array('api_url', '<>', '');
        $condition[] = ['id', 'in', unserialize($rules)];
        $result = (new AdminMenuDao())->getAdminMenuColumn($condition, 'api_url');

        return $result;
    }


    //获取当前用户信息
    public function getCurrentAdminInfo()
    {
        $admin_info = (new AdminDao())->getAdminInfoById($this->user_id);


        if ($admin_info['is_super'] != 1) {
            $role_id = $admin_info['role_id'];
            // 获取角色信息
            $role_info = (new AdminRoleDao())->getAdminRoleInfo([['id', '=', $role_id]]);
            $rules = $role_info['rules'];

            if (empty($rules)) {
                // $condition[] = ['id', 'in', array(0)];
            } else {
                // $condition[] = ['id', 'in', unserialize($rules)];
            }
        } else {
            //超级管理员

        }



        $userinfo = [
            'id' => $admin_info['id'],
            'role' => 'admin',
            'user_id' => $admin_info['id'],
            'username' => $admin_info['username'],
        ];




        return $userinfo;
    }



    public function getCurrentAdminMenus()
    {


        $condition = [];
        $condition[] = ['type', 'in', ['DIRECTORY', 'MENU']];


        $field = '*';
        $menu = (new AdminMenuDao())->getAdminMenuList($condition, $field);

        // 将菜单数据转换为树形结构
        $userMenus = linearToTree($menu, 'id', 'pid');

        // $userMenus = $this->demoAuthorizeMenuList();
        $data = array(
            'userMenus' => $userMenus,
        );

        return $data;
    }





    public function demoAuthorizeMenuList()
    {
        $userMenus = array(
            array(
                'component' => 'pages-admin/main/views/dashboard/index',
                'path' => 'workbench',
                'name' => '工作台',
                'icon' => 'element ArrowDown',
                'is_show' => 1,
                'type' => 'MENU'
            ),
            array(
                'component' => '',
                'path' => 'setting',
                'name' => '系统设置',
                'icon' => 'element ArrowDown',
                'is_show' => 1,
                'type' => 'DIRECTORY',
                'children' => array(
                    array(
                        'component' => 'pages-admin/main/views/setting/config/base',
                        'path' => 'config/base',
                        'name' => '基础设置',
                        'icon' => 'element ArrowDown',
                        'is_show' => 1,
                        'type' => 'MENU'
                    ),
                    array(
                        'component' => 'pages-admin/main/views/setting/config/logo',
                        'path' => 'config/logo',
                        'name' => 'Logo设置',
                        'icon' => 'element ArrowDown',
                        'is_show' => 1,
                        'type' => 'MENU'
                    )
                )
            ),
            array(
                'component' => '',
                'path' => 'permission',
                'name' => '权限管理',
                'icon' => 'element ArrowDown',
                'is_show' => 1,
                'type' => 'DIRECTORY',
                'children' => array(
                    array(
                        'component' => 'pages-admin/main/views/admin/admin/index',
                        'path' => 'permission/admin',
                        'name' => '管理员',
                        'icon' => 'element ArrowDown',
                        'is_show' => 1,
                        'type' => 'MENU'
                    ),
                    array(
                        'component' => 'pages-admin/main/views/admin/role/index',
                        'path' => 'permission/role',
                        'name' => '角色',
                        'icon' => 'element ArrowDown',
                        'is_show' => 1,
                        'type' => 'MENU'
                    ),
                    array(
                        'component' => 'pages-admin/main/views/admin/menu/index',
                        'path' => 'permission/menu',
                        'name' => '菜单',
                        'icon' => 'element ArrowDown',
                        'is_show' => 1,
                        'type' => 'MENU'
                    )
                )
            )
        );
        return $userMenus;
    }

    /**
     * 修改当前管理员密码
     * @param array $data
     * @return array
     */
    public function editCurrentAdminPassword($data)
    {
        // 获取当前管理员信息
        $adminDao = new AdminDao();
        $admin_info = $adminDao->getAdminInfoById($this->user_id);
        
        if (!$admin_info) {
            throw new CommonException('管理员不存在');
        }

        // 验证原密码
        if (!password_verify($data['old_password'], $admin_info['password'])) {
            throw new CommonException('原密码错误');
        }

        // 更新密码
        $update_data = [
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];
        
        $result = $adminDao->updateAdmin(['id' => $this->user_id], $update_data);
        
        if (!$result) {
            throw new CommonException('密码修改失败');
        }

        // 修改密码后，使该管理员的所有Token失效
        (new TokenCache())->invalidateToken('admin', $this->user_id);

        return ['success' => true];
    }
}
