<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseUserService;
use app\deshang\exceptions\CommonException;
use app\common\model\store\TblStoreModel;
use app\common\model\store\TblStoreAuthUserModel;

// 店铺管理 基础服务层
class BaseStoreMgmtService extends BaseUserService
{
    protected $store_id; // 店铺ID

    public function __construct()
    {
        parent::__construct();


        // ------------此处代码可以丢 storeapi 拦截器 现在是直接使用api拦截器

        // 获取请求的店铺ID 一个商户可管理多个店铺，所以需要传入店铺ID
        $param_store_id = $this->request->header('manage-store-id');
        if (!$param_store_id) {
            throw new CommonException('您未有权限操作店铺，系统错误 header 缺少 manage-store-id',403);
        }


        // 未通过用户类型去判断，而是先通过商户ID去判断，然后通过店铺ID去判断，最后通过用户ID去判断

        if ($this->merchant_id) {
            // TblStoreModel 公共店铺表
            $store_list = TblStoreModel::where('merchant_id', $this->merchant_id)->column('id');

            // 判断请求的店铺ID是否在商户下
            if (!in_array($param_store_id, $store_list)) {
                throw new CommonException('您没有权限操作此店铺');
            } else {
                $this->store_id = $param_store_id;
            }
        } else if ($this->user_id) {
            // 获取用户授权的店铺ID
            $store_list = TblStoreAuthUserModel::where('user_id', $this->user_id)->column('store_id');


            // 判断请求的店铺ID是否在用户授权的店铺下
            if (!in_array($param_store_id, $store_list)) {
                throw new CommonException('您没有权限操作此店铺');
            } else {
                $this->store_id = $param_store_id;
            }
        } else {
            throw new CommonException('请先登录');
        }



    }
}
