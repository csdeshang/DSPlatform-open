<?php

namespace app\common\dao\trade;

use app\common\dao\BaseDao;
use app\common\model\trade\TradePaymentConfigModel;

/**
 * 交易支付配置数据访问对象
 * 
 * 负责交易支付配置的数据库交互操作
 */
class TradePaymentConfigDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TradePaymentConfigModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TradePaymentConfigModel();
    }
    
    /**
     * 创建交易支付配置
     * 
     * @param array $data 配置数据
     * @return int 新创建的配置ID
     */
    public function createPaymentConfig(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除交易支付配置
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePaymentConfig(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新交易支付配置
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updatePaymentConfig(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取交易支付配置列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 配置列表
     */
    public function getPaymentConfigList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    

    /**
     * 获取单条交易支付配置信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 配置信息
     */
    public function getPaymentConfigInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取交易支付配置信息
     * 
     * @param int $id 配置ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 配置信息
     */
    public function getPaymentConfigInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据支付方式代码获取配置信息
     * 
     * @param string $paymentCode 支付方式代码
     * @param string $field 查询字段，默认为所有字段
     * @return array 配置信息
     */
    public function getPaymentConfigByCode(string $paymentCode, string $field = '*'): array
    {
        return $this->model->where('payment_code', $paymentCode)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取交易支付配置数量
     * 
     * @param array $condition 查询条件
     * @return int 配置数量
     */
    public function getPaymentConfigCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取交易支付配置列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getPaymentConfigColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    
}
