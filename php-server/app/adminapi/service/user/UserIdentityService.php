<?php


namespace app\adminapi\service\user;

use app\deshang\base\service\BaseAdminService;



use app\common\dao\user\UserIdentityDao;

class UserIdentityService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserIdentityList(array $data): array
    {
        $condition = [];
        if(isset($data['user_id']) && $data['user_id'] != ''){
            $condition[] = ['user_id', '=', $data['user_id']];
        }

        $result = (new UserIdentityDao())->getUserIdentityList($condition);
        return $result;
    }

    


}