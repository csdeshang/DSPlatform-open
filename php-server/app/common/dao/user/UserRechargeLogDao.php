<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserRechargeLogModel;

/**
 * 用户充值日志数据访问对象
 * 
 * 负责用户充值操作日志的数据库交互操作
 */
class UserRechargeLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserRechargeLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserRechargeLogModel();
    }
    
    /**
     * 创建用户充值日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createRechargeLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户充值日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteRechargeLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新用户充值日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateRechargeLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户充值日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getRechargeLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取用户充值日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getRechargeLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取用户充值日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelRechargeLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->with(
            [
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                },
            ]
        )
        ->append(['pay_channel_desc', 'pay_scene_desc', 'recharge_status_desc'])
        ->field($field)
        ->order($order);
        return $this->getPaginate($result);
    }




    /**
     * 获取单条用户充值日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getRechargeLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取用户充值日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getRechargeLogInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取用户充值日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getRechargeLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取用户充值日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getRechargeLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
