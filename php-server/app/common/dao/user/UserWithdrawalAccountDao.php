<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserWithdrawalAccountModel;

/**
 * 用户提现账户数据访问对象
 * 
 * 负责用户提现账户的数据库交互操作
 */
class UserWithdrawalAccountDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserWithdrawalAccountModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserWithdrawalAccountModel();
    }
    
    /**
     * 创建用户提现账户
     * 
     * @param array $data 账户数据
     * @return int 新创建的账户ID
     */
    public function createWithdrawalAccount(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户提现账户
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteWithdrawalAccount(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新用户提现账户
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateWithdrawalAccount(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取用户提现账户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 账户列表
     */
    public function getWithdrawalAccountList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取用户提现账户分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithdrawalAccountPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取关联用户信息的提现账户分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelWithdrawalAccountPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->with(
            [
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                },
            ]
        )
        ->append(['account_type_desc'])
        ->field($field)
        ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条用户提现账户信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 账户信息
     */
    public function getWithdrawalAccountInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取用户提现账户信息
     * 
     * @param int $id 账户ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 账户信息
     */
    public function getWithdrawalAccountInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取用户提现账户数量
     * 
     * @param array $condition 查询条件
     * @return int 账户数量
     */
    public function getWithdrawalAccountCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取用户提现账户列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getWithdrawalAccountColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
