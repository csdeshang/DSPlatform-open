<?php


namespace app\deshang\service\store;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\dao\store\TblStoreAuthUserDao;

class DeshangTblStoreAuthUserService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // 获取店铺授权的用户
    public function getTblStoreAuthUserList(array $data): array
    {
        $condition = [];
        if(!empty($data['store_id'])){
            $condition[] = ['store_id', '=', $data['store_id']];
        }

        if(!empty($data['user_id'])){
            $condition[] = ['user_id', '=', $data['user_id']];
        }

        $result = (new TblStoreAuthUserDao())->getWithRelStoreAuthUserList($condition);
        return $result;
    }



    // 添加店铺授权用户
    public function createTblStoreAuthUser(array $data): bool
    {

        // 主要验证 store_id 和 user_id 是否存在
        $this->validate($data, 'app\deshang\service\store\validate\TblStoreAuthUserValidate.create');

        // 验证数据是否存在
        $condition = [
            ['store_id', '=', $data['store_id']],
            ['user_id', '=', $data['user_id']],
        ];
        $result = (new TblStoreAuthUserDao())->getStoreAuthUserInfo($condition);
        if ($result) {
            throw new CommonException('店铺已经添加了此用户');
        }
        $result = (new TblStoreAuthUserDao())->createStoreAuthUser($data);
        return true;
    }

    // 删除店铺授权用户
    public function deleteTblStoreAuthUser(array $data): bool
    {
        $condition = [
            ['store_id', '=', $data['store_id']],
            ['user_id', '=', $data['user_id']],
        ];
        $result = (new TblStoreAuthUserDao())->deleteStoreAuthUser($condition);
        if (!$result) {
            throw new CommonException('删除店铺授权用户失败');
        }
        return true;
    }

    

}