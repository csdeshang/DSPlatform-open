<?php

namespace app\common\model\admin;

use app\deshang\base\BaseModel;
use app\common\model\admin\AdminRoleModel;


class AdminModel extends BaseModel{


    /**
     * 模型名称
     * @var string
     */
    protected $name = 'admin';



    // 管理员角色 关联
    public function adminRole()
    {
        return $this->hasOne(AdminRoleModel::class, 'id', 'role_id');
    }

    


    // 登录时间 获取器
    public function getLoginTimeAttr($value, $data)
    {
        return $this->formatTime($data['login_time']);
    }





}