<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserGrowthLevelModel;

/**
 * 用户成长等级数据访问对象
 * 
 * 负责用户成长等级的数据库交互操作
 */
class UserGrowthLevelDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserGrowthLevelModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserGrowthLevelModel();
    }

    /**
     * 创建用户成长等级
     * 
     * @param array $data 等级数据
     * @return int 新创建的等级ID
     */
    public function createUserGrowthLevel(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户成长等级
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteUserGrowthLevel(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新用户成长等级
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateUserGrowthLevel(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户成长等级列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 等级列表
     */
    public function getUserGrowthLevelList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }


    /**
     * 获取单条用户成长等级信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 等级信息
     */
    public function getUserGrowthLevelInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取用户成长等级信息
     * 
     * @param int $id 等级ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 等级信息
     */
    public function getUserGrowthLevelInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

 
}
