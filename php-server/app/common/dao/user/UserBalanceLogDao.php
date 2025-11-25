<?php

namespace app\common\dao\user;

use app\common\dao\BaseDao;
use app\common\model\user\UserBalanceLogModel;

/**
 * 用户余额日志数据访问对象
 * 
 * 负责用户余额变动日志的数据库交互操作
 */
class UserBalanceLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化UserBalanceLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserBalanceLogModel();
    }

    /**
     * 创建用户余额日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createBalanceLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户余额日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteBalanceLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新用户余额日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateBalanceLog(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取用户余额日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getBalanceLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取用户余额日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['change_type_desc', 'change_mode_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取用户余额日志分页列表 带关联查询
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )->append(['change_type_desc', 'change_mode_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条用户余额日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getBalanceLogInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->append(['change_type_desc', 'change_mode_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取用户余额日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getBalanceLogInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取用户余额日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getBalanceLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取用户余额日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getBalanceLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
