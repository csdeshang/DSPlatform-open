<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\system\SysNoticeSmsLogDao;
use app\deshang\utils\SearchHelper;


class SysNoticeSmsLogService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
    }

    public function getSysNoticeSmsLogPages($data)
    {
        $condition = [];
        if (!empty($data['user_id'])) {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        if (!empty($data['key'])) {
            $condition[] = ['key', '=', $data['key']];
        }





        $result = (new SysNoticeSmsLogDao())->getSysNoticeSmsLogPages($condition);
        return $result;
    }


    public function getSysNoticeSmsLogInfo($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysNoticeSmsLogDao())->getSysNoticeSmsLogInfo($condition);
        return $result;
    }
}
