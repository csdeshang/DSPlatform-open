<?php

namespace app\common\model\store;

use app\deshang\base\BaseModel;
use app\common\model\user\UserModel;
use app\common\model\store\TblStoreModel;


class TblStoreAuthUserModel extends BaseModel{

    // 表名
    protected $name = 'tbl_store_auth_user';



    // 关联用户
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    // 关联店铺
    public function store()
    {
        return $this->hasOne(TblStoreModel::class, 'id', 'store_id');
    }


}