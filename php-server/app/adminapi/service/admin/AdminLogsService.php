<?php

namespace app\adminapi\service\admin;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\admin\AdminLogsDao;


class AdminLogsService extends BaseAdminService
{

    public function __construct(){
        parent::__construct();
        $this->dao = new AdminLogsDao();
    }

    public function getAdminLogsPages($data){
        $condition = [];
        
        // 只有非空值才添加搜索条件
        if (!empty($data['username'])) {
            $condition[] = ['username', 'like', '%' . $data['username'] . '%'];
        }
        if (!empty($data['http_code'])) {
            $condition[] = ['http_code', 'like', '%' . $data['http_code'] . '%'];
        }
        if (!empty($data['controller'])) {
            $condition[] = ['controller', 'like', '%' . $data['controller'] . '%'];
        }
        
        $result = $this->dao->getAdminLogsPages($condition);
        return $result;
    }


    public function getAdminLogsInfo($id){
        $result = $this->dao->getAdminLogsInfoById($id);
        return $result;
    }


}