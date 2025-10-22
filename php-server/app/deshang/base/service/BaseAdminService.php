<?php

namespace app\deshang\base\service;
use app\deshang\base\BaseService;
/**
 * 后台基础服务层
 * Class BaseAdminService
 * @package app\service\admin
 */
class BaseAdminService extends BaseService
{

    protected $user_id;
    protected $username;
    protected $admin_is_super;

    public function __construct()
    {
        parent::__construct();
        $this->user_id = $this->request->user_id;
        $this->username = $this->request->username;
        $this->admin_is_super = $this->request->admin_is_super;
    }
}
