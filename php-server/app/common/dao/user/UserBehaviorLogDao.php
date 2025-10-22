<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserBehaviorLogModel;

/**
 * 用户操作行为记录数据访问对象
 * 
 * 负责用户操作行为记录的数据库交互操作
 */
class UserBehaviorLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserBehaviorLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserBehaviorLogModel();
    }

    /**
     * 创建用户行为记录
     * 
     * @param array $data 行为数据
     * @return int 新创建的记录ID
     */
    public function createUserBehaviorLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户行为记录
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteUserBehaviorLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新用户行为记录
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateUserBehaviorLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户行为记录列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 行为记录列表
     */
    public function getUserBehaviorLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取用户行为记录分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getUserBehaviorLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['behavior_type_desc', 'behavior_status_desc', 'risk_level_desc', 'is_abnormal_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取用户行为记录分页列表 带关联查询
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelUserBehaviorLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )->append(['behavior_type_desc', 'behavior_status_desc', 'risk_level_desc', 'is_abnormal_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条用户行为记录信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 行为记录信息
     */
    public function getUserBehaviorLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
        ->append(['behavior_type_desc', 'behavior_status_desc', 'risk_level_desc', 'is_abnormal_desc'])
        ->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取用户行为记录信息
     * 
     * @param int $id 记录ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 行为记录信息
     */
    public function getUserBehaviorLogInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取用户行为记录数量
     * 
     * @param array $condition 查询条件
     * @return int 记录数量
     */
    public function getUserBehaviorLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取用户行为记录列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getUserBehaviorLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
