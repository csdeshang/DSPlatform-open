<?php


namespace app\adminapi\service\store;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\store\DeshangTblStoreAuthUserService;


class TblStoreAuthUserService extends BaseAdminService
{

    public function getTblStoreAuthUserList($data){
        $result = (new DeshangTblStoreAuthUserService())->getTblStoreAuthUserList($data);
        return $result;
    }

    


    public function createTblStoreAuthUser($data){
        $data['create_by'] = $this->username;
        $result = (new DeshangTblStoreAuthUserService())->createTblStoreAuthUser($data);
        return $result;
    }

    public function deleteTblStoreAuthUser($data){
        $result = (new DeshangTblStoreAuthUserService())->deleteTblStoreAuthUser($data);
        return $result;
    }

    
    
    



}