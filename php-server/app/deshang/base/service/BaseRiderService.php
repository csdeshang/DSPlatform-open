<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseUserService;
use app\deshang\exceptions\CommonException;


// 骑手管理 基础服务层
class BaseRiderService extends BaseUserService
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->rider_id) {
            throw new CommonException('您不是骑手');
        }

        if (!$this->rider_is_enabled) {
            throw new CommonException('您骑手账户已被禁用');
        }
    }
}
