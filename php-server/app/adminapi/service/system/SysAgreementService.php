<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\system\SysAgreementDao;

class SysAgreementService extends BaseAdminService
{

    public function __construct(){
        parent::__construct();
    }

    public function getSysAgreementList($data){

        $condition = [];

        $result = (new SysAgreementDao())->getSysAgreementList($condition);
        return $result;

    }

    public function createSysAgreement($data){
        $result = (new SysAgreementDao())->createSysAgreement($data);
        return $result;
    }


    public function getSysAgreementInfo($id){
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysAgreementDao())->getSysAgreementInfo($condition);
        return $result;
    }

    public function updateSysAgreement($id, $data){
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysAgreementDao())->updateSysAgreement($condition, $data);
        return $result;
    }



}