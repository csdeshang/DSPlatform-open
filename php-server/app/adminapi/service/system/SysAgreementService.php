<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

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
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_AGREEMENT_TAG);
        
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
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_AGREEMENT_TAG);
        
        return $result;
    }



}