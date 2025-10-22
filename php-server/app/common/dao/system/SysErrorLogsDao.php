<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysErrorLogsModel;

/**
 * 系统错误日志数据访问对象
 * 
 * 负责系统错误日志的数据库交互操作
 */
class SysErrorLogsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysErrorLogsModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysErrorLogsModel();
    }
    
    /**
     * 创建错误日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createErrorLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除错误日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteErrorLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新错误日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateErrorLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取错误日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getErrorLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取错误日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getErrorLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条错误日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getErrorLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取错误日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getErrorLogInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取错误日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getErrorLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取错误日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getErrorLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    

}
