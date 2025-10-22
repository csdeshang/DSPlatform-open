<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\system\SysAccessLogsDao;

class SysAccesslogsService extends BaseAdminService
{

    public function __construct(){
        parent::__construct();
    }

    public function getSysAccesslogsPages($data){

        $condition = [];
        
        // 用户名搜索
        if (!empty($data['username'])) {
            $condition[] = ['username', 'like', '%' . $data['username'] . '%'];
        }
        
        // IP地址搜索
        if (!empty($data['ip'])) {
            $condition[] = ['ip', 'like', '%' . $data['ip'] . '%'];
        }
        
        // 请求耗时区间搜索
        if (isset($data['duration_min']) && $data['duration_min'] !== '') {
            $condition[] = ['duration', '>=', $data['duration_min']];
        }
        if (isset($data['duration_max']) && $data['duration_max'] !== '') {
            $condition[] = ['duration', '<=', $data['duration_max']];
        }

        $result = (new SysAccessLogsDao())->getAccessLogPages($condition);
        return $result;

    }




    public function getSysAccesslogsInfo($id){
        $result = (new SysAccessLogsDao())->getAccessLogInfoById($id);
        return $result;
    }



}