<?php

namespace app\deshang\base\service;

use app\deshang\base\BaseService;


// 非adminapi 基础服务层, 用户  商户  店铺 基础服务层 

class BaseApiService extends BaseService
{

    protected $user_id;// 用户ID
    protected $user_is_enabled;// 用户是否启用

    protected $merchant_id;// 商户ID
    protected $merchant_is_enabled;// 商户是否启用

    protected $rider_id;// 骑手ID
    protected $rider_is_enabled;// 骑手是否启用

    protected $technician_id;// 师傅ID
    protected $technician_is_enabled;// 师傅是否启用

    protected $blogger_id;// 博主ID
    protected $blogger_is_enabled;// 博主是否启用

    public function __construct()
    {
        parent::__construct();
        $this->user_id = $this->request->user_id;
        $this->user_is_enabled = $this->request->user_is_enabled;

        $this->merchant_id = $this->request->merchant_id;
        $this->merchant_is_enabled = $this->request->merchant_is_enabled;

        $this->rider_id = $this->request->rider_id;
        $this->rider_is_enabled = $this->request->rider_is_enabled;

        $this->technician_id = $this->request->technician_id;
        $this->technician_is_enabled = $this->request->technician_is_enabled;

        $this->blogger_id = $this->request->blogger_id;
        $this->blogger_is_enabled = $this->request->blogger_is_enabled;
    }
}
