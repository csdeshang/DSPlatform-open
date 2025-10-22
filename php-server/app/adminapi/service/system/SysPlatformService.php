<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;



use app\common\dao\system\SysPlatformDao;


// 安装系统
class SysPlatformService extends BaseAdminService
{



    public function getSysPlatformInfo(int $id)
    {

        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysPlatformDao())->getSysPlatformInfo($condition);
        return $result;
    }



    public function getSysPlatformList($data)
    {
        $condition = array();
        if (isset($data['scene']) && !empty($data['scene'])) {
            $condition[] = ['scene', '=', $data['scene']];
        }
        $result = (new SysPlatformDao())->getSysPlatformList($condition);
        return $result;
    }


    public function updateSysPlatform(int $id,array $data):bool
    {


        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new SysPlatformDao())->updateSysPlatform($condition,$data);
        return $result;
    }



}