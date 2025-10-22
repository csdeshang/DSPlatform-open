<?php



namespace app\adminapi\service\admin;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\admin\AdminRoleDao;

class AdminRoleService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new AdminRoleDao();
    }

    public function getAdminRoleList($data)
    {
        $condition = array();
        $result = $this->dao->getAdminRoleList($condition);
        return $result;
    }


    public function getAdminRoleInfo($id)
    {
        $condition = [
            ['id', '=', $id],
        ];
        $field = '*';
        $admin_role = $this->dao->getAdminRoleInfo($condition, $field);

        if(!empty($admin_role['rules'])){
            $admin_role['rules'] = unserialize($admin_role['rules']);
            
        }
        return $admin_role;
    }




    public function createAdminRole($data)
    {
        $data['rules'] = serialize(array());
        return $this->dao->createAdminRole($data);
    }



    public function updateAdminRole($data)
    {
        $condition = [
            ['id', '=', $data['id']],
        ];
        $result = $this->dao->updateAdminRole($condition, $data);
        return $result;
    }


    public function deleteAdminRole($id)
    {
        $condition = [
            ['id', '=', $id],
        ];
        $result = $this->dao->deleteAdminRole($condition);

        if(!$result){
            throw new CommonException('删除失败');
        }
        return true;
    }
}
