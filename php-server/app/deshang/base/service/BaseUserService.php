<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseApiService;
use app\deshang\exceptions\CommonException;

// 用户基础服务层  用户必须登录
class BaseUserService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
 
        //验证是否获取到user_id
        if (!$this->user_id) {
            throw new CommonException('请先登录',403);
        }

        if (!$this->user_is_enabled) {
            throw new CommonException('您账户已被禁用');
        }
    }
}
