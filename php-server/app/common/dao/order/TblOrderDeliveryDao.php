<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderDeliveryModel;

/**
 * 订单配送数据访问对象
 * 
 * 负责订单配送信息的数据库交互操作
 */
class TblOrderDeliveryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderDeliveryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderDeliveryModel();
    }
    
    /**
     * 创建订单配送
     * 
     * @param array $data 配送数据
     * @return int 新创建的配送ID
     */
    public function createOrderDelivery(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单配送
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderDelivery(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订单配送
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateOrderDelivery(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取订单配送列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 配送列表
     */
    public function getOrderDeliveryList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    /**
     * 获取订单配送分页
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 配送分页
     */
    public function getOrderDeliveryPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的订单配送分页
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 配送分页
     */
    public function getWithRelOrderDeliveryPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'orderAddress' => function ($query) {
                        $query->field('*');
                    }
                ]
            )
            ->append(['delivery_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条订单配送信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 配送信息
     */
    public function getOrderDeliveryInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联的订单配送信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 配送信息
     */
    public function getWithRelOrderDeliveryInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'order' => function ($query) {
                        $query->field('*');
                    },
                    'orderAddress' => function ($query) {
                        $query->field('*');
                    },
                    'orderGoodsList' => function ($query) {
                        $query->field('*');
                    },
                    'orderFinance' => function ($query) {
                        $query->field('*');
                    },
                ]
            )
            ->append(['delivery_status_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }
    
    /**
     * 根据ID获取订单配送信息
     * 
     * @param int $id 配送ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 配送信息
     */
    public function getOrderDeliveryInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取订单配送数量
     * 
     * @param array $condition 查询条件
     * @return int 配送数量
     */
    public function getOrderDeliveryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订单配送列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderDeliveryColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
