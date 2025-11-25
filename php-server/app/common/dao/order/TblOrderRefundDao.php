<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderRefundModel;

/**
 * 订单退款数据访问对象
 * 
 * 负责订单退款的数据库交互操作
 */
class TblOrderRefundDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderRefundModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderRefundModel();
    }

    /**
     * 创建订单退款
     * 
     * @param array $data 退款数据
     * @return int 新创建的退款ID
     */
    public function createOrderRefund(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单退款
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrderRefund(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新订单退款
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrderRefund(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取订单退款列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 退款列表
     */
    public function getOrderRefundList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取订单退款分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getOrderRefundPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['refund_type_desc', 'refund_status_desc', 'refund_method_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的订单退款分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */

    public function getWithRelOrderRefundPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name');
                    },
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->append(['refund_type_desc', 'refund_status_desc', 'refund_method_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条订单退款信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 退款信息
     */
    public function getOrderRefundInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联的订单退款信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 退款信息
     */

    public function getWithRelOrderRefundInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name');
                    },
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'orderRefundLogList' => function ($query) {
                        $query->append(['refund_status_desc'])->order('create_at desc');
                    },
                ]
            )
            ->append(['refund_type_desc', 'refund_status_desc', 'refund_method_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }




    /**
     * 根据ID获取订单退款信息
     * 
     * @param int $id 退款ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 退款信息
     */
    public function getOrderRefundInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }


    /**
     * 获取订单退款数量
     * 
     * @param array $condition 查询条件
     * @return int 退款数量
     */
    public function getOrderRefundCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取订单退款列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderRefundColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


    /**
     * 获取订单退款总额
     * 
     * @param array $condition 查询条件
     * @param string $field 金额字段名，默认为refund_amount
     * @return float 总金额
     */
    public function getOrderRefundSum(array $condition, string $field = 'refund_amount'): float
    {
        return $this->model->where($condition)->sum($field);
    }
}
