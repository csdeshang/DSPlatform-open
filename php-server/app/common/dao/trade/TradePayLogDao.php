<?php

namespace app\common\dao\trade;

use app\common\dao\BaseDao;
use app\common\model\trade\TradePayLogModel;

/**
 * 交易支付日志数据访问对象
 * 
 * 负责交易支付日志的数据库交互操作
 */
class TradePayLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TradePayLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TradePayLogModel();
    }
    
    /**
     * 创建交易支付日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createTradePayLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除交易支付日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTradePayLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新交易支付日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateTradePayLog(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取交易支付日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getTradePayLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->append(['pay_status_desc', 'source_type_desc'])
        ->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取交易支付日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getTradePayLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['pay_status_desc', 'source_type_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条交易支付日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getTradePayLogInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->append(['pay_status_desc', 'source_type_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取交易支付日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getTradePayLogInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取交易支付日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getTradePayLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取交易支付日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getTradePayLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
