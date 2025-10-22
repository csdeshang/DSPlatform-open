<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseUserService;
use app\deshang\exceptions\CommonException;


// 商户管理 基础服务层
class BaseMerchantService extends BaseUserService
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->merchant_id) {
            throw new CommonException('您不是商户');
        }

        if (!$this->merchant_is_enabled) {
            throw new CommonException('您商户账户已被禁用');
        }
    }
}
