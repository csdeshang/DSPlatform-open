<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserModel;

/**
 * 用户数据访问对象
 * 
 * 负责用户的数据库交互操作
 */
class UserDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }
    
    /**
     * 创建用户
     * 
     * @param array $data 用户数据
     * @return int 新创建的用户ID
     */
    public function createUser(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteUser(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新用户
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateUser(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 用户列表
     */
    public function getUserList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取用户分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getUserPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条用户信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 用户信息
     */
    public function getUserInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取用户信息
     * 
     * @param int $id 用户ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 用户信息
     */
    public function getUserInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取用户数量
     * 
     * @param array $condition 查询条件
     * @return int 用户数量
     */
    public function getUserCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取用户列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getUserColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


    /**
     * 用户统计数据自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setUserInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }

    /**
     * 用户统计数据自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setUserDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }


    
}
