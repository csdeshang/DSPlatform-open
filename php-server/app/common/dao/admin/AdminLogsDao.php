<?php

namespace app\common\dao\admin;

use app\common\dao\BaseDao;
use app\common\model\admin\AdminLogsModel;

/**
 * 管理员日志数据访问对象
 * 
 * 负责管理员操作日志的数据库交互操作
 */
class AdminLogsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AdminLogsModel模型实例
     */
    public function __construct()
    {
        $this->model = new AdminLogsModel();
    }

    /**
     * 创建管理员日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createAdminLogs(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除管理员日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAdminLogs(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新管理员日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateAdminLogs(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取管理员日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 日志列表
     */
    public function getAdminLogsList(array $condition, string $field = '*', string $order = 'id desc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取管理员日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getAdminLogsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条管理员日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getAdminLogsInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取管理员日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getAdminLogsInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取管理员日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getAdminLogsCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }


    /**
     * 获取管理员日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAdminLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


}
