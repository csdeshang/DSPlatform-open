<?php

namespace app\adminapi\service\admin;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\TokenCache;
use app\common\dao\admin\AdminDao;


class AdminService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->dao = new AdminDao();
    }

    public function getAdminPages($data)
    {
        $condition = [];
        if (isset($data['username']) && !empty($data['username'])) {
            $condition[] = ['username', 'like', "%" . $data['username'] . "%"];
        }
        return $this->dao->getAdminPages($condition);
    }



    public function getAdminInfo($id)
    {
        $condition = [
            ['id', '=', $id],
        ];
        $field = 'id,username,login_time,login_num,is_super,role_id';
        return $this->dao->getWithRelAdminInfo($condition, $field);
    }


    public function createAdmin($data)
    {
        $admin_data = array(
            'username' => $data['username'],
            'password' => create_password($data['password']),
            'role_id' => $data['role_id'],
        );
        
        $id = $this->dao->createAdmin($admin_data);
        return $id;

    }

    public function updateAdmin($data){
        $admin_data = array(
            'id' => $data['id'],
            'role_id' => $data['role_id'],
        );
        if (!empty($data['password'])) {
            $admin_data['password'] = create_password($data['password']);
        }

        $condition = [
            ['id', '=', $data['id']],
        ];

        $result = $this->dao->updateAdmin($condition, $admin_data);
        
        // 如果修改了密码，使该管理员的所有Token失效
        if (!empty($data['password'])) {
            (new TokenCache())->invalidateToken('admin', $data['id']);
        }
        
        return $result;
    }


    public function deleteAdmin($id){
        $admin_info = $this->dao->getAdminInfoById($id);

        if ($admin_info['is_super'] == 1) {
            throw new CommonException('超级管理员不能删除');
        }
        $condition = [
            ['id', '=', $id],
            ['is_super', '!=', 1]
        ];
        $result = $this->dao->deleteAdmin($condition);
        return $result;
    }




}
