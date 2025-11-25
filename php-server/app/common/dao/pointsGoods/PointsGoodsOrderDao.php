<?php

namespace app\common\dao\pointsGoods;

use app\common\dao\BaseDao;
use app\common\model\pointsGoods\PointsGoodsOrderModel;

/**
 * 积分兑换订单数据访问对象
 * 
 * 负责积分兑换订单的数据库交互操作
 */
class PointsGoodsOrderDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化PointsGoodsOrderModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PointsGoodsOrderModel();
    }

    /**
     * 创建积分兑换订单
     * 
     * @param array $data 订单数据
     * @return int 新创建的订单ID
     */
    public function createPointsGoodsOrder(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除积分兑换订单
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePointsGoodsOrder(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 软删除积分兑换订单
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function softDeletePointsGoodsOrder(array $condition): int
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 更新积分兑换订单
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updatePointsGoodsOrder(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取积分兑换订单列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 订单列表
     */
    public function getPointsGoodsOrderList(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->order($order)->select()->toArray();
    }

    /**
     * 获取积分兑换订单分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getPointsGoodsOrderPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条积分兑换订单信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 订单信息
     */
    public function getPointsGoodsOrderInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->lock($lock)
            ->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取积分兑换订单信息
     * 
     * @param int $id 订单ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 订单信息
     */
    public function getPointsGoodsOrderInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->lock($lock)
            ->findOrEmpty()->toArray();
    }

    /**
     * 获取积分兑换订单数量
     * 
     * @param array $condition 查询条件
     * @return int 订单数量
     */
    public function getPointsGoodsOrderCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取积分兑换订单列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getPointsGoodsOrderColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setPointsGoodsOrderInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }


    /**
     * 自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setPointsGoodsOrderDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
