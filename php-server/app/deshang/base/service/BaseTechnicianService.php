<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseUserService;
use app\deshang\exceptions\CommonException;


// 师傅管理 基础服务层
class BaseTechnicianService extends BaseUserService
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->technician_id) {
            throw new CommonException('您不是师傅');
        }

        if (!$this->technician_is_enabled) {
            throw new CommonException('您师傅账户已被禁用');
        }
    }
}
