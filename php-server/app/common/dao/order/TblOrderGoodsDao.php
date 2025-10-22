<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderGoodsModel;

/**
 * 订单商品数据访问对象
 * 
 * 负责订单商品的数据库交互操作
 */
class TblOrderGoodsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderGoodsModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderGoodsModel();
    }
    
    /**
     * 创建订单商品
     * 
     * @param array $data 商品数据
     * @return int 新创建的订单商品ID
     */
    public function createOrderGoods(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }
    
    /**
     * 批量创建订单商品
     * 
     * @param array $dataList 商品数据列表
     * @return bool 是否成功
     */
    public function createOrderGoodsAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除订单商品
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderGoods(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单商品
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateOrderGoods(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取订单商品列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 商品列表
     */
    public function getOrderGoodsList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取订单商品分页
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 商品分页
     */
    public function getOrderGoodsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条订单商品信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getOrderGoodsInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订单商品信息
     * 
     * @param int $id 订单商品ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getOrderGoodsInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取订单商品数量
     * 
     * @param array $condition 查询条件
     * @return int 商品数量
     */
    public function getOrderGoodsCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订单商品列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderGoodsColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    /**
     * 获取订单商品总数量
     * 
     * @param int $orderId 订单ID
     * @return int 商品总数量
     */
    public function getOrderGoodsTotalNum(int $orderId): int
    {
        return $this->model->where('order_id', $orderId)->sum('goods_num');
    }
    
    /**
     * 获取订单商品总金额
     * 
     * @param int $orderId 订单ID
     * @return float 商品总金额
     */
    public function getOrderGoodsTotalAmount(int $orderId): float
    {
        return $this->model->where('order_id', $orderId)->sum('goods_total');
    }
}
