<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\system\SysErrorLogsDao;


class SysErrorLogsService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
        $this->dao = new SysErrorLogsDao();
    }

    public function getSysErrorLogsPages($data)
    {
        $condition = [];
        
        // 控制器搜索
        if (!empty($data['controller'])) {
            $condition[] = ['controller', 'like', '%' . $data['controller'] . '%'];
        }
        
        // 根目录搜索
        if (!empty($data['root'])) {
            $condition[] = ['root', 'like', '%' . $data['root'] . '%'];
        }
        
        // IP地址搜索
        if (!empty($data['ip'])) {
            $condition[] = ['ip', 'like', '%' . $data['ip'] . '%'];
        }
        
        // 错误代码搜索
        if (!empty($data['code'])) {
            $condition[] = ['code', 'like', '%' . $data['code'] . '%'];
        }
        
        // 请求耗时区间搜索
        if (isset($data['duration_min']) && $data['duration_min'] !== '') {
            $condition[] = ['duration', '>=', $data['duration_min']];
        }
        if (isset($data['duration_max']) && $data['duration_max'] !== '') {
            $condition[] = ['duration', '<=', $data['duration_max']];
        }
        
        $result = $this->dao->getErrorLogPages($condition);
        return $result;
    }

    public function createSysErrorLogs($data)
    {
        $result = $this->dao->createErrorLog($data);
        return $result;
    }



    public function getSysErrorLogsInfo($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = $this->dao->getErrorLogInfo($condition);
        return $result;
    }
}
