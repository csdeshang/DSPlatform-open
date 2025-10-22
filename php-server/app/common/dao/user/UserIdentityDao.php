<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserIdentityModel;

/**
 * 用户身份数据访问对象
 * 
 * 负责用户身份的数据库交互操作
 */
class UserIdentityDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserIdentityModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserIdentityModel();
    }

    /**
     * 创建用户身份
     * 
     * @param array $data 身份数据
     * @return int 新创建的身份ID
     */
    public function createUserIdentity(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户身份
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteUserIdentity(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新用户身份
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateUserIdentity(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户身份列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 身份列表
     */
    public function getUserIdentityList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条用户身份信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 身份信息
     */
    public function getUserIdentityInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取用户身份信息
     * 
     * @param int $id 身份ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 身份信息
     */
    public function getUserIdentityInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
}
