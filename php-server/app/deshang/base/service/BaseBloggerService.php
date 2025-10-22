<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseUserService;
use app\deshang\exceptions\CommonException;


// 博主管理 基础服务层
class BaseBloggerService extends BaseUserService
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->blogger_id) {
            throw new CommonException('您不是博主');
        }

        if (!$this->blogger_is_enabled) {
            throw new CommonException('您博主账户已被禁用');
        }
    }
}
