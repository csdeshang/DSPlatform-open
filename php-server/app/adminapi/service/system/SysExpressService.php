<?php


namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;

use app\deshang\exceptions\CommonException;

use app\common\dao\system\SysExpressDao;

class SysExpressService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
        $this->dao = new SysExpressDao();
    }

    public function getSysExpressPages(array $data)
    {
        $condition = [];
        
        // 添加关键词搜索条件
        if (!empty($data['keyword'])) {
            $condition[] = ['name', 'like', '%' . $data['keyword'] . '%'];
        }


        $result = $this->dao->getExpressPages($condition);
        return $result;
    }

    public function getSysExpressList(array $data)
    {
        $condition = [];
        
        // 添加关键词搜索条件
        if (!empty($data['keyword'])) {
            $condition[] = ['name', 'like', '%' . $data['keyword'] . '%'];
        }
        
        
        $result = $this->dao->getExpressList($condition, '*', 'sort asc, id asc');
        return $result;
    }



    public function getSysExpressInfo($id)
    {
        $condition = array();
        $condition[] = array('id', '=', $id);
        $field = '*';
        $result = $this->dao->getExpressInfo($condition, $field);

        if (empty($result)) {
            throw new CommonException('物流不存在');
        }

        return $result;
    }


    public function createSysExpress(array $data)
    {

        $result = $this->dao->createExpress($data);
        return $result;
    }


    public function updateSysExpress(array $data)
    {
        $condition = [];
        $condition[] = ['id', '=', $data['id']];
        $result = $this->dao->updateExpress($condition, $data);
        return $result;
    }


    // 单个删除
    public function deleteSysExpress($id)
    {

        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = $this->dao->deleteExpress($condition);

        return $result;



    }

}
