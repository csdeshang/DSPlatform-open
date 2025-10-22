<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserAddressModel;

/**
 * 用户地址数据访问对象
 * 
 * 负责用户地址的数据库交互操作
 */
class UserAddressDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserAddressModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserAddressModel();
    }
    
    /**
     * 创建用户地址
     * 
     * @param array $data 地址数据
     * @return int 新创建的地址ID
     */
    public function createAddress(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户地址
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAddress(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新用户地址
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateAddress(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户地址列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按是否默认地址降序，ID降序
     * @return array 地址列表
     */
    public function getAddressList(array $condition, string $field = '*', string $order = 'is_default desc, id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取用户地址分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按是否默认地址降序，ID降序
     * @return array 分页数据
     */
    public function getAddressPages(array $condition, string $field = '*', string $order = 'is_default desc, id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条用户地址信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 地址信息
     */
    public function getAddressInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取用户地址信息
     * 
     * @param int $id 地址ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 地址信息
     */
    public function getAddressInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取用户地址数量
     * 
     * @param array $condition 查询条件
     * @return int 地址数量
     */
    public function getAddressCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取用户地址列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAddressColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
