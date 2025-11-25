<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreAuthUserModel;

/**
 * 店铺授权用户数据访问对象
 * 
 * 负责店铺授权用户的数据库交互操作
 */
class TblStoreAuthUserDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreAuthUserModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreAuthUserModel();
    }

    /**
     * 创建店铺授权用户
     * 
     * @param array $data 授权用户数据
     * @return int 新创建的授权用户ID
     */
    public function createStoreAuthUser(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺授权用户
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreAuthUser(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新店铺授权用户
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateStoreAuthUser(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取店铺授权用户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 授权用户列表
     */
    public function getStoreAuthUserList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺授权用户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 授权用户列表
     */
    public function getWithRelStoreAuthUserList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'store' => function ($query) {
                        $query->field('id,store_name');
                    },
                ]
            )
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }


    /**
     * 获取单条店铺授权用户信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 授权用户信息
     */
    public function getStoreAuthUserInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取店铺授权用户信息
     * 
     * @param int $id 授权用户ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 授权用户信息
     */
    public function getStoreAuthUserInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取店铺授权用户数量
     * 
     * @param array $condition 查询条件
     * @return int 授权用户数量
     */
    public function getStoreAuthUserCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取店铺授权用户列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreAuthUserColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
