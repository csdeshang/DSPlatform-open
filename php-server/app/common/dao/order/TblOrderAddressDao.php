<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderAddressModel;

/**
 * 订单地址数据访问对象
 * 
 * 负责订单收货地址的数据库交互操作
 */
class TblOrderAddressDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderAddressModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderAddressModel();
    }
    
    /**
     * 创建订单地址
     * 
     * @param array $data 地址数据
     * @return int 新创建的地址ID
     */
    public function createOrderAddress(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单地址
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderAddress(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单地址
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrderAddress(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取订单地址列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 地址列表
     */
    public function getOrderAddressList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条订单地址信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 地址信息
     */
    public function getOrderAddressInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订单地址信息
     * 
     * @param int $id 地址ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 地址信息
     */
    public function getOrderAddressInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取订单地址数量
     * 
     * @param array $condition 查询条件
     * @return int 地址数量
     */
    public function getOrderAddressCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订单地址列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderAddressColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
